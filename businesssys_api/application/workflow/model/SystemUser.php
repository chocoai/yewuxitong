<?php
namespace app\workflow\model;

class SystemUser extends Base
{
    public function department(){
        return $this->belongsTo('SystemDept','deptid');
    }
}
