<?php


function dbg($var){
    var_dump($var);
    die();
}
/**
 * 生成随机字符串
 * @param  integer $length [description]
 * @return [type]          [description]
 */

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomPassword()
{
    $pwd = "Ys" . mt_rand(100000, 999999);
    return $pwd;

    $length = 8;
    $characters = [];
    $characters[] = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $characters[] = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    $characters[] = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    $i = 1;
    $pwd_array = [];
    foreach ($characters as $character) {
        if (count($characters) == $i) {
            $len = $length;
        } else {
            $len = mt_rand(1, $length - (count($characters) - $i));
            $length = $length - $len;
            $i++;
        }

        $keys = array_rand($character, $len);
        if ($len == 1) {
            $pwd_array[] = $character[$keys];
        } else {

            foreach ($keys as $key) {
                $pwd_array[] = $character[$key];
            }
        }
    }

    // $len1 = mt_rand(1, 6);
    // $pwd1 = array_rand($characters1, $len1);
    // $len2 = mt_rand(1, 7 - $len1);
    // $pwd2 = array_rand($characters2, $len2);
    // $len3 = 8 - $len1 - $len2;
    // $pwd3 = array_rand($characters3, $len3);
    // $pwd_array = array_merge($pwd1, $pwd2, $pwd3);
    shuffle($pwd_array);
    return implode($pwd_array);

}

function ap($path)
{
    return sprintf("/%s%s", config('app.panelPath'), $path);
}

function realPanelPath($path)
{
    return str_replace(config('app.panelPath'), '', $path);
}

function __fillString($str, $fillStr, $isFront = true)
{
    if ($isFront) {
        return substr($fillStr, 0, strlen($fillStr) - strlen($str)) . $str;
    } else {
        return $str . substr($fillStr, 0, strlen($fillStr) - strlen($str));
    }
}

function __hiddenString($str, $front = 0, $back = 0)
{
    $ret = str_repeat('*', mb_strlen($str) - ($front + $back));
    if ($front > 0) {
        $ret = mb_substr($str, 0, $front) . $ret;
    }

    if ($back > 0) {
        $ret = $ret . mb_substr($str, -$back);
    }
    return $ret;
}

function __isMobile($request)
{
    $client = strtoupper($request->header('CLIENT'));
    if ($client == 'GC' || $client == 'ANDROID' || $client == 'IOS' || $client == 'MS') {
        return true;
    }
    return false;
}

function getUserUploadFilePath($name, $id, $ext = '')
{
    if ($ext) {
        $ext = "." . $ext;
    }

    $filename = sprintf("%s/u/%s/%s/%s%s", $name, substr(md5($id), 0, 2), $id, time(), $ext);
    return $filename;
}

function getUploadFilename($name, $id = '', $ext = '')
{
    if ($ext) {
        $ext = "." . $ext;
    }
    if ($id) {
        $filename = sprintf("%s/%s/%s/%s%s", $name, substr(md5($id), 0, 2), $id, time(), $ext);
    } else {
        $filename = sprintf("%s/%s%s", $name, time(), $ext);
    }

    return $filename;
}

function getUploadFilePath($name, $id = '')
{
    if ($id) {
        $filename = sprintf("%s/%s/%s", $name, substr(md5($id), 0, 2), $id);
    } else {
        $filename = sprintf("%s", $name);
    }

    return $filename;
}

function __isMobileOS()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function __getClient($request)
{
    return strtoupper($request->header('CLIENT'));
}

function __timezone($request)
{
    if ($request->header('TIMEZONE')) {
        return $request->header('TIMEZONE');
    }
    return "America/Los_Angeles";
}

function getGroupName($group)
{
    return __('sys.name_group_' . $group);
}

function getGradeName($group, $grade)
{
    return __('sys.name_grade_' . $group . '_' . $grade);
}

function getGroupGradeName($group, $grade)
{
    // if ($group == 0) {
    //     return getGroupName($group);
    // }

    return getGroupName($group) . ' (' . getGradeName($group, $grade) . ')';
}