<?php

namespace app\workflow\model;


/**
 * Class SystemDept 部门
 * @package app\workflow\model
 * @author: bordon
 */
class SystemDept extends Base
{
    public function manager()
    {
        return $this->belongsTo('SystemUser', 'superid');
    }
}
