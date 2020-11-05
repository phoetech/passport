<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $guarded = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'remember_token', 'trade_password', 'identifier', 'salt', 'last_ip', 'login_time',
    ];


    const STATUS_VERICATION_UNSET = 0;
    const STATUS_VERICATION_PENDING = 1;
    const STATUS_VERICATION_VERIFIED = 2;
    const STATUS_VERICATION_REJECT = 10;

    public static function getUserByDisplayId($displayId)
    {
        if (strlen($displayId) != 8) {
            return null;
        }
        return User::where('display_id', $displayId)->first();
    }

    /**
     * 获取用户头像地址
     *
     * @param
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        // return asset($this->avatar);
        if ($this->avatar != '') {
            return Storage::url($this->avatar);
        }
        return config('app.url') . '/img/default_avatar.png';
    }

    /**
     * 根据用户ID获取用户头像地址
     *
     * @param  int $user_id 用户ID
     * @return string
     */
    public static function getAvatar($user_id)
    {
        if (!$user_id) {
            return '';
        }
        $userinfo = User::getUserInfo($user_id);
        if (isset($userinfo['avatar']) && $userinfo['avatar'] != '') {
            return Storage::url($userinfo['avatar']);
        }
        return config('app.url') . '/img/default_avatar.png';
    }

    /**
     * Redis 获取用户信息
     *
     * @param  int $user_id  user_id
     * @return Array         UserInfo
     */
    public static function getUserInfo($user_id, $fresh = false)
    {
        if (!$user_id) {
            return null;
        }
        $key = sprintf("U:%d:I", $user_id);
        if (!$fresh) {
            if (Redis::exists($key)) {
                return Redis::hgetall($key);
            }
        }
        $user = User::where('id', $user_id)->first();
        if ($user) {
            $userinfo = [
                'name' => $user->name,
                'avatar' => $user->avatar,
                'group' => $user->team->group,
                'grade' => $user->team->grade,
                'country' => $user->country,
                'state' => $user->state,
                'display_id' => $user->display_id,
            ];
            Redis::hmset($key, $userinfo);
            return $userinfo;
        }
        return null;
    }

    public static function getUserDisplayId($user_id, $fresh = false)
    {
        $key = sprintf("U:%d:I", $user_id);
        if (!$fresh) {

            if (Redis::hexists($key, 'display_id')) {
                return intVal(Redis::hget($key, 'display_id'));
            }
        }
        $userinfo = self::getUserInfo($user_id, true);
        if ($userinfo) {
            return intVal($userinfo['display_id']);
        }
        return 0;
    }

    /**
     * Redis 清除用户缓存信息
     *
     * @param
     * @return
     */
    public function clearCache()
    {
        $key = sprintf("U:%d:I", $this->id);
        if (Redis::exists($key)) {
            Redis::del($key);
        }
    }

    /**
     * 根据用户ID获取用户名
     * @param string id
     * @return string
     */
    public static function getUsername($user_id)
    {
        if (!$user_id) {
            return 'no name';
        }
        $userinfo = User::getUserInfo($user_id);
        if ($userinfo) {
            return $userinfo['name'];
        }
        return '';
    }

    /**
     * 获取一组用户名
     * @param string id字符串，用.分隔
     * @return array
     */
    public static function getUsernames($userIds): array
    {
        $ids = explode('.', $userIds);
        $usernames = [];
        foreach ($ids as $id) {
            if ($id > 0) {
                $usernames[] = self::getUsername($id);
            }
        }
        return $usernames;
    }

    public static function generateDisplayId()
    {
        $did_y = Carbon::now()->diffInYears('2017-01-01 00:00:00');
        $did_d = 10;
        $has = true;
        while ($has) {
            if ($did_d > 9) {
                $did_d = 0;
                $did_f = __fillString(rand(1, 999), '000');
                $did_s = __fillString(rand(1, 999), '000');
            }
            $displayId = $did_y . $did_d . $did_f . $did_s;
            $has = self::where('display_id', $displayId)->exists();
            $did_d++;
        }
        return $displayId;
    }

    public function lockOperation($action, $second = 5)
    {
        $key = sprintf("Op:%s:%d", $action, $this->id);
        Redis::set($key, $this->id);
        Redis::expire($key, $second);
        return true;
    }

    public function checkOperation($action)
    {
        $key = sprintf("Op:%s:%d", $action, $this->id);
        return Redis::exists($key);
    }

    public function releaseOperation($action)
    {
        $key = sprintf("Op:%s:%d", $action, $this->id);
        Redis::del($key);
        return true;
    }
}
