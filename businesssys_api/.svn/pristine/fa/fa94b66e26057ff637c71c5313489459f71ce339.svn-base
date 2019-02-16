<?php

namespace app\workflow\model;

class WorkflowProc extends Base
{
    protected $autoWriteTimestamp = true;

    const APPROVAL_STATUS_PASS = 9;
    const APPROVAL_STATUS_UNPASS = -1;
    const APPROVAL_STATUS_PENDING = 0;
    public static $approvalStatus = [
        self::APPROVAL_STATUS_PENDING => '待处理',
        self::APPROVAL_STATUS_UNPASS => '驳回',
        self::APPROVAL_STATUS_PASS => '通过',
    ];

    public function emp()
    {
        return $this->belongsTo("Emp", "emp_id");
    }

    public function entry()
    {
        return $this->belongsTo("WorkflowEntry", "entry_id");
    }

    public function process()
    {
        return $this->belongsTo("WorkflowProcess", "process_id");
    }

    public function flow()
    {
        return $this->belongsTo("WorkflowFlow", "flow_id");
    }

    public function procs()
    {
        return $this->hasMany('WorkflowProc', 'entry_id');
    }
}
