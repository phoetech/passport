<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $nextSendCode = 60;
    protected $expiredMinutes = 10;
    protected $defaultCode = '000000';

    /**
     * 检查手机号码是否注册
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function checkPhone(Request $request)
    {
        try {
            $user = User::where('ccc', $request->input('ccc'))
                ->where('phone', $request->input('phone'))
                ->firstOrFail();

            return response()->api([
                'display_id' => $user->display_id,
                'id_verify' => $user->id_verify == User::STATUS_VERICATION_VERIFIED,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->api(['display_id' => 0]);
        }
    }

    /**
     * 发送验证码
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function sendSMSCode(SendVerificationCode $request)
    {
        $validated = $request->validated();

        if (env('APP_DEBUG')) {
            $code = $this->defaultCode;
        } else {
            $code = (string) rand(0, 999999);
            $code = substr('000000', 0, 6 - strlen($code)) . $code;
        }
        $data = [
            "ccc" => $validated['ccc'],
            "phone" => $validated['phone'],
            "message" => sprintf("%s is your Youth Shine verification code and login password, expires in 10 minutes.", $code),
        ];

        // SendSMS::dispatch($data)->onQueue(env('AWS_QUEUE_PUSHER'));

        // try {
        //     $result = AuthCode::where('ccc', $validated['ccc'])
        //         ->where('phone', $validated['phone'])
        //         ->where('status', 'unused')
        //         ->orderBy('id', 'desc')
        //         ->firstOrFail();
        //     $result->created_at = Carbon::now();
        //     $result->save();
        //     return response()->api(['time' => $this->nextSendCode]);
        // } catch (ModelNotFoundException $e) {
        //     //
        // }

        AuthCode::create([
            'ccc' => $validated['ccc'],
            'phone' => $validated['phone'],
            'code' => $code,
        ]);

        return response()->api(['time' => $this->nextSendCode]);
    }

    /**
     * 检查验证码是否正确
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function checkSMSCode(Request $request)
    {
        try {
            $result = AuthCode::where('ccc', $request->input('ccc'))
                ->where('phone', $request->input('phone'))
                ->where('status', 'unused')
                ->orderBy('id', 'desc')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            abort(404, __('error.code'));
        }

        if ($request->input('code') != $result->code) {
            abort(404, __('error.code'));
        }

        if (!Carbon::createFromFormat('Y-m-d H:i:s', $result['created_at'])->between(Carbon::now()->subMinutes($this->expiredMinutes), Carbon::now())) {
            abort(400, "Code is expired.");
        }

        $result->status = 'used';
        $result->save();

        try {
            $user = User::where('ccc', $request->input('ccc'))
                ->where('phone', $request->input('phone'))
                ->firstOrFail();
            return response()->api([
                'code' => $result->code,
                'display_id' => $user->display_id,
                'id_verify' => $user->id_verify == USER::STATUS_VERICATION_VERIFIED,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->api(['code' => $result->code]);
        }
    }

    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $request['display_id'] = User::generateDisplayId();
        $request->request->remove('password_confirmation');
        $request['identifier']='18053001239';
        $request['ccc']='1';
        $request['phone']='18053001239';
        $request['salt']='1234abcd';
        $user = User::create($request->toArray());
        $token = $user->createToken('Phoetech Password Grant Client')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }

    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('identifier', $request->identifier)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Phoetech Password Grant Client')->accessToken;
                $response = ['token' => $token];
                return redirect('/home')->with(['token' => $token]);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
