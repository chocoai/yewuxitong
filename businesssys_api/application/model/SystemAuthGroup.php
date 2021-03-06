<?php
/**
 *
 * @since   2018-04-21
 * @author  CGenJ
 */

namespace app\model;


class SystemAuthGroup extends Base
{

    public function rules()
    {
        return $this->hasMany('SystemAuthRule', 'groupid', 'id');
    }

    public function getAuthGroup()
    {
        $groups = cache('auth_group');
        if (!$groups) {
            $result = self::where(['status'=>1])->column('sign', 'id');
            $groups = array_flip(array_filter($result));
            cache('auth_group',$groups,['expire' => 3600]);
        }
        return $groups;
    }

}
