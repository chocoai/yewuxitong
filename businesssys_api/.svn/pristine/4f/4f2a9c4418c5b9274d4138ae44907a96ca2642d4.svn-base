<?php

namespace app\model;


class SystemUserData extends Base
{
    public static function updateLoginData($uid)
    {
        $data = self::find($uid);
        $ip = request()->ip(true);
        if ($data) {
            $data->logintimes++;
            $data->lastloginip = $ip;
            $data->lastlogintime = time();
            $data->save();
        } else {
            self::create([
                'logintimes' => 1,
                'uid' => $uid,
                'lastloginip' => $ip,
                'lastlogintime' => time()
            ]);
        }
    }
}
