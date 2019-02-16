<?php

namespace app\workflow\model;

class WorkflowFlowlink extends Base
{
    protected $autoWriteTimestamp = true;

    public function process()
    {
        return $this->belongsTo('WorkflowProcess', 'process_id');
    }

    public function nextProcess()
    {
        return $this->belongsTo('WorkflowProcess', 'next_process_id')
            ->field('id,flow_id,process_name,process_to,position,child_flow_id,child_after,child_back_process,is_sign,wf_status,wf_nextuser,approval_type,approval_num,backtype');
    }
}
