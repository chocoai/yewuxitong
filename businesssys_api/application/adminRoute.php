<?php

$afterBehavior = [
    /*'\app\admin\behavior\ApiAuth',
    '\app\admin\behavior\ApiPermission',
    '\app\admin\behavior\SystemLog',*/
];

return [
    '[admin]' => [
        'Login/index' => [
            'admin/Login/index',
            ['method' => 'post'],
        ],
        'Index/upload' => [
            'admin/Index/upload',
            ['method' => 'post']
        ],
        'Login/logout' => [
            'admin/Login/logout',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /*         * ***************************************系统菜单**************************************** */
        'Menu/index' => [
            'admin/Menu/index',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'Menu/changeStatus' => [
            'admin/Menu/changeStatus',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'Menu/add' => [
            'admin/Menu/add',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'Menu/edit' => [
            'admin/Menu/edit',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'Menu/del' => [
            'admin/Menu/del',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        /*         * ***************************************系统用户**************************************** */
        'User/index' => [
            'admin/User/index',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'User/getUsers' => [
            'admin/User/getUsers',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'User/changeStatus' => [
            'admin/User/changeStatus',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'User/add' => [
            'admin/User/add',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'User/own' => [
            'admin/User/own',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'User/edit' => [
            'admin/User/edit',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'User/del' => [
            'admin/User/del',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'User/getUserAuthList' => [
            'admin/User/getUserAuthList',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'User/getSyncUser' => [
            'admin/User/getSyncUser',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'User/getSyncDept' => [
            'admin/User/getSyncDept',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * ***************************************系统权限**************************************** */
        'Auth/index' => [
            'admin/Auth/index',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'Auth/changeStatus' => [
            'admin/Auth/changeStatus',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'Auth/delMember' => [
            'admin/Auth/delMember',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'Auth/addMember' => [
            'admin/Auth/addMember',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'Auth/add' => [
            'admin/Auth/add',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'Auth/edit' => [
            'admin/Auth/edit',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'Auth/del' => [
            'admin/Auth/del',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'Auth/getGroups' => [
            'admin/Auth/getGroups',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'Auth/getRuleList' => [
            'admin/Auth/getRuleList',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        /*         * ***************************************系统日志**************************************** */
        'Log/index' => [
            'admin/Log/index',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        'Log/del' => [
            'admin/Log/del',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        //赵光帅
        /*         * ************费用申请***************** */
        //撤单确定退费
        'CancellationsApply/addCancellRefund' => [
            'admin/CancellationsApply/addCancellRefund',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //撤单退费管理列表
        'CancellationsApply/cancellManagementList' => [
            'admin/CancellationsApply/cancellManagementList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //撤单审批详情
        'CancellationsApply/cancellApplyDetail' => [
            'admin/CancellationsApply/cancellApplyDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //撤单审批列表
        'CancellationsApply/cancellApprovalList' => [
            'admin/CancellationsApply/cancellApprovalList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加撤单获取基本信息
        'CancellationsApply/getCancellOrderInfo' => [
            'admin/CancellationsApply/getCancellOrderInfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //编辑撤单
        'CancellationsApply/editCancell' => [
            'admin/CancellationsApply/editCancell',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //撤单列表
        'CancellationsApply/cancellationsList' => [
            'admin/CancellationsApply/cancellationsList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加撤单
        'CancellationsApply/addCancellatApply' => [
            'admin/CancellationsApply/addCancellatApply',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //放尾款管理列表
        'CostApply/balancemanagementList' => [
            'admin/CostApply/balancemanagementList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //放尾款审批列表
        'CostApply/balanceApprovalList' => [
            'admin/CostApply/balanceApprovalList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //放尾款申请列表
        'CostApply/tailSectionList' => [
            'admin/CostApply/tailSectionList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //判断改订单是否有效
        'CostApply/isCheckOrder' => [
            'admin/CostApply/isCheckOrder',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //沟通回复列表
        'CostApply/communicationReplyList' => [
            'admin/CostApply/communicationReplyList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //沟通回复
        'CostApply/communicaReply' => [
            'admin/CostApply/communicaReply',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //发起沟通
        'CostApply/initiateCommunica' => [
            'admin/CostApply/initiateCommunica',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //编辑展期费
        'CostApply/editRenewal' => [
            'admin/CostApply/editRenewal',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //展期申请审批列表
        'CostApply/renewalAppList' => [
            'admin/CostApply/renewalAppList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //展期申请
        'CostApply/addRenewal' => [
            'admin/CostApply/addRenewal',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //展期申请列表
        'CostApply/rollOverList' => [
            'admin/CostApply/rollOverList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加费用申请
        'CostApply/addCostApply' => [
            'admin/CostApply/addCostApply',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //信息费支付申请列表
        'CostApply/infoCostList' => [
            'admin/CostApply/infoCostList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //其他退费申请列表
        'CostApply/otherRefundList' => [
            'admin/CostApply/otherRefundList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //费用支付详情
        'CostApply/costApplyDetail' => [
            'admin/CostApply/costApplyDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //审批详情
        'CostApply/costApprovalDetail' => [
            'admin/CostApply/costApprovalDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //编辑费用申请
        'CostApply/editCostApply' => [
            'admin/CostApply/editCostApply',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //信息费审批列表
        'CostApply/costAuditList' => [
            'admin/CostApply/costAuditList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //其他费用审批列表
        'CostApply/otherApprovalList' => [
            'admin/CostApply/otherApprovalList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加订单订单基本信息
        'CostApply/getOrderInfo' => [
            'admin/CostApply/getOrderInfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //审批相关信息
        'CostApply/appFlowInfo' => [
            'admin/CostApply/appFlowInfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //处理审批
        'CostApply/subDealWith' => [
            'admin/CostApply/subDealWith',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //信息费支付账户信息
        'CostApply/getAccountDetail' => [
            'admin/CostApply/getAccountDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //提交信息费
        'CostApply/addSubmission' => [
            'admin/CostApply/addSubmission',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //审批完驳回
        'CostApply/subRejected' => [
            'admin/CostApply/subRejected',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //支付详情驳回
        'CostApply/payDetailRejected' => [
            'admin/CostApply/payDetailRejected',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * ************银行账户***************** */
        //银行账号列表
        'AccountManagement/bankAccountList' => [
            'admin/AccountManagement/bankAccountList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加银行账户
        'AccountManagement/addAccount' => [
            'admin/AccountManagement/addAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //更新银行账户
        'AccountManagement/editAccount' => [
            'admin/AccountManagement/editAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //查询出当个银行账户信息
        'AccountManagement/getOneAccount' => [
            'admin/AccountManagement/getOneAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //查询出当个银行账户信息
        'AccountManagement/getOneAccount' => [
            'admin/AccountManagement/getOneAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //账号设置列表
        'AccountManagement/accountSettingList' => [
            'admin/AccountManagement/accountSettingList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加账户银行卡号列表
        'AccountManagement/bankCardList' => [
            'admin/AccountManagement/bankCardList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //新增账号
        'AccountManagement/addBankAccount' => [
            'admin/AccountManagement/addBankAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //移除账号
        'AccountManagement/delBankAccount' => [
            'admin/AccountManagement/delBankAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * ************现金业务 新增接口***************** */
        //待结单列表
        'CashBusiness/finanStateList' => [
            'admin/CashBusiness/finanStateList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //获取所有担保人
        'CashBusiness/getGuarantee' => [
            'admin/CashBusiness/getGuarantee',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //提交结单
        'CashBusiness/submitOrder' => [
            'admin/CashBusiness/submitOrder',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //待发送指令列表(渠道)/
        'CashBusiness/channelsInstructionList' => [
            'admin/CashBusiness/channelsInstructionList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //已发送指令列表(渠道)/
        'CashBusiness/channelsHasList' => [
            'admin/CashBusiness/channelsHasList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //垫资出账表
        'CashBusiness/channelsInfo' => [
            'admin/CashBusiness/channelsInfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //指令发送(渠道)
        'CashBusiness/channelsSend' => [
            'admin/CashBusiness/channelsSend',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //获取所有资金渠道信息
        'CashBusiness/getFundChannel' => [
            'admin/CashBusiness/getFundChannel',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //渠道放款审核列表/
        'CashBusiness/channelsAuditList' => [
            'admin/CashBusiness/channelsAuditList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //渠道放款审核提交审核
        'CashBusiness/channelsSubAudit' => [
            'admin/CashBusiness/channelsSubAudit',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //渠道放款待入账列表/
        'CashBusiness/channelLendList' => [
            'admin/CashBusiness/channelLendList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //渠道放款已入账列表/
        'CashBusiness/channelHasList' => [
            'admin/CashBusiness/channelHasList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //渠道放款已入账列表导出
        'CashBusiness/exportHasList' => [
            'admin/CashBusiness/exportHasList',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        //增加渠道放款入账
        'CashBusiness/addChannelWater' => [
            'admin/CashBusiness/addChannelWater',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //渠道放款入账详情
        'CashBusiness/showChannelLendDetail' => [
            'admin/CashBusiness/showChannelLendDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //渠道放款入账复核
        'CashBusiness/channelReview' => [
            'admin/CashBusiness/channelReview',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * *************资料入架 财务审核***************** */
        //资料入架已入架列表
        'Foreclo/hasBeenList' => [
            'admin/Foreclo/hasBeenList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //资料入架待入架列表
        'Foreclo/dataList' => [
            'admin/Foreclo/dataList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //财务待审核列表
        'Foreclo/finauditList' => [
            'admin/Foreclo/finauditList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //财务已审核列表
        'Foreclo/haveOnList' => [
            'admin/Foreclo/haveOnList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //财务审核详情页
        'Foreclo/caiwuInfo' => [
            'admin/Foreclo/caiwuInfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //财务审核流程
        'Foreclo/foreProcList' => [
            'admin/Foreclo/foreProcList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //财务审核提交
        'Foreclo/submitFinancial' => [
            'admin/Foreclo/submitFinancial',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * *************财务管理***************** */
        //费用待入账列表
        'Financial/bookedList' => [
            'admin/Financial/bookedList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //费用已入账列表
        'Financial/bookedHasList' => [
            'admin/Financial/bookedHasList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //财务已入账导出
        'Financial/exportFinanceList' => [
            'admin/Financial/exportFinanceList',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        //增加入账流水
        'Financial/addBooksWater' => [
            'admin/Financial/addBooksWater',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //入账流水明细
        'Financial/showBooksDetail' => [
            'admin/Financial/showBooksDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //银行放款待入账列表
        'Financial/bankLendList' => [
            'admin/Financial/bankLendList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //银行放款已入账列表
        'Financial/bankHasList' => [
            'admin/Financial/bankHasList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //银行放款已入账导出
        'Financial/exportBankHas' => [
            'admin/Financial/exportBankHas',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //查询出收款账户
        'Financial/paymentAccount' => [
            'admin/Financial/paymentAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //增加银行放款入账流水
        'Financial/addBankWater' => [
            'admin/Financial/addBankWater',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //担保费是否收齐
        'Financial/isCollected' => [
            'admin/Financial/isCollected',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //银行放款入账是否收齐
        'Financial/isLoanFinish' => [
            'admin/Financial/isLoanFinish',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //渠道放款入账是否收齐
        'CashBusiness/isQdFinish' => [
            'admin/CashBusiness/isQdFinish',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //银行放款入账流水明细
        'Financial/showBankLendDetail' => [
            'admin/Financial/showBankLendDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //银行放款入账审核
        'Financial/editReview' => [
            'admin/Financial/editReview',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //待发送指令列表(额度)
        'Financial/instructionList' => [
            'admin/Financial/instructionList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //已发送指令列表(额度)
        'Financial/instructionHasList' => [
            'admin/Financial/instructionHasList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //赎楼出账表
        'Financial/foreclosureInfo' => [
            'admin/Financial/foreclosureInfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //指令发送
        'Financial/instructionsSend' => [
            'admin/Financial/instructionsSend',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * *************数据字典管理***************** */
        //获取数据字典列表
        'Dictionary/getPrimaryData' => [
            'admin/Dictionary/getPrimaryData',
            ['method' => 'post']
        ],
        //数据字典查询
        'Dictionary/showDictionary' => [
            'admin/Dictionary/showDictionary',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //数据字典编辑
        'Dictionary/editDictionary' => [
            'admin/Dictionary/editDictionary',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //数据字典禁用 删除
        'Dictionary/delDictionary' => [
            'admin/Dictionary/delDictionary',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加数据字典
        'Dictionary/addDictionary' => [
            'admin/Dictionary/addDictionary',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * *************组织结构****************** */
        //获取组织结构信息
        'OrganiZation/strucList' => [
            'admin/OrganiZation/strucList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加组织结构
        'OrganiZation/addOrgani' => [
            'admin/OrganiZation/addOrgani',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //部门信息
        'OrganiZation/bumenInfo' => [
            'admin/OrganiZation/bumenInfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //删除部门
        'OrganiZation/delOrgani' => [
            'admin/OrganiZation/delOrgani',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //编辑组织结构
        'OrganiZation/editOrgani' => [
            'admin/OrganiZation/editOrgani',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //递归查询组织结构
        'OrganiZation/showDigui' => [
            'admin/OrganiZation/showDigui',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * *************支票管理接口****************** */
        //获取支票列表
        'Check/showCheckList' => [
            'admin/Check/showCheckList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //模糊查询部门人员列表
        'Check/showUser' => [
            'admin/Check/showUser',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //支票详情
        'Check/showCheckDetail' => [
            'admin/Check/showCheckDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //支票入库
        'Check/addCheckStorage' => [
            'admin/Check/addCheckStorage',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //支票操作验证
        'Check/verifyOperation' => [
            'admin/Check/verifyOperation',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //修改支票信息
        'Check/modifyCheck' => [
            'admin/Check/modifyCheck',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //领取支票
        'Check/getCheck' => [
            'admin/Check/getCheck',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //转让支票
        'Check/CheckTransfer' => [
            'admin/Check/CheckTransfer',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //转让确认
        'Check/transferDetermine' => [
            'admin/Check/transferDetermine',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //支票撤回
        'Check/checkWithdraw' => [
            'admin/Check/checkWithdraw',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //支票核销
        'Check/checkCancel' => [
            'admin/Check/checkCancel',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //支票作废
        'Check/checkInvalid' => [
            'admin/Check/checkInvalid',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //支票删除
        'Check/CheckDelete' => [
            'admin/Check/CheckDelete',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //操作记录列表
        'Check/operationList' => [
            'admin/Check/operationList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * ************风控管理接口********************* */
        //待审列表
        'Approval/showApprovalList' => [
            'admin/Approval/showApprovalList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //已审列表
        'Approval/haveOnlList' => [
            'admin/Approval/haveOnlList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //所有列表
        'Approval/getAlllList' => [
            'admin/Approval/getAlllList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //审批页面信息
        'Approval/approvalRecords' => [
            'admin/Approval/approvalRecords',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //提交审批
        'Approval/proceMaterialNode' => [
            'admin/Approval/proceMaterialNode',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //提交审批
        'Approval/subApproval' => [
            'admin/Approval/subApproval',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //查询初审结果
        'Approval/showResult' => [
            'admin/Approval/showResult',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //提交初审结果
        'Approval/addResult' => [
            'admin/Approval/addResult',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //删除初审结果(问题汇总与缺少资料)
        'Approval/delProblem' => [
            'admin/Approval/delProblem',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //删除初审结果(反担保房产担保)
        'Approval/delGuarantee' => [
            'admin/Approval/delGuarantee',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //查询出缺少的资料
        'Approval/dataList' => [
            'admin/Approval/dataList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //提交缺少的资料
        'Approval/addData' => [
            'admin/Approval/addData',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //删除附件
        'Approval/delAttachment' => [
            'admin/Approval/delAttachment',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*         * *********公共接口************** */
        //上传文件接口
        'Index/fileUpload' => [
            'admin/Index/fileUpload',
            ['method' => 'post'],
        ],
        //base64多文件上传图片
        'Index/app_Uploads' => [
            'admin/Appupload/app_Uploads',
            ['method' => 'post'],
        ],
        //多文件文件上传
        'Index/appFileUploads' => [
            'admin/Appupload/appFileUploads',
            ['method' => 'post'],
        ],
        //根据订单号查询所有的房产名称
        'Index/allPropertyNames' => [
            'admin/Index/allPropertyNames',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //获取所有银行
        'Index/getBanks' => [
            'admin/Index/getBanks',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //验证单点登录
        'Index/verifyTheLogin' => [
            'admin/Index/verifyTheLogin',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //跳转到楼盘字典
        'Index/jumpBuilding' => [
            'admin/Index/jumpBuilding',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        //单点退出登录清空缓存
        'Index/logOut' => [
            'admin/Index/logOut',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 阿琪 */
        /* 获取对应类型字典数据 */
        'Dictionary/getDictionaryByType' => [
            'admin/Dictionary/getDictionaryByType',
            ['method' => 'post'],
        ],
        /* 征信列表页 */
        'Credit/creditList' => [
            'admin/Credit/creditList',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        /* 新增征信 */
        'Credit/addCredit' => [
            'admin/Credit/addCredit',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 征信申请编辑 */
        'Credit/editCredit' => [
            'admin/Credit/editCredit',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 征信申请删除 */
        'Credit/delCredit' => [
            'admin/Credit/delCredit',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        /* 提交至人行 */
        'Credit/submitTobank' => [
            'admin/Credit/submitTobank',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 获取征信信息（编辑） */
        'Credit/getCreditinfo' => [
            'admin/Credit/getCreditinfo',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        /* 征信详情 */
        'Credit/creditDetail' => [
            'admin/Credit/creditDetail',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        /* 模糊获取业务单号 */
        'Credit/ordersnList' => [
            'admin/Credit/ordersnList',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        /* 模糊获取所有理财经理 */
        'SystemUser/managerList' => [
            'admin/SystemUser/managerList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 编辑审核信息 */
        'Credit/editReviewinfo' => [
            'admin/Credit/editReviewinfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 上传征信信息 */
        'Credit/uploadCredit' => [
            'admin/Credit/uploadCredit',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 下载征信报告 */
        'Credit/downCredit' => [
            'admin/Credit/downCredit',
            ['method' => 'get']
        ],
        /* 获取征信内容 */
        'Credit/lookCredit' => [
            'admin/Credit/lookCredit',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 编辑派单 */
        'Credit/editOrder' => [
            'admin/Credit/editOrder',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 新增用户 */
        'Customer/addcreditCustomer' => [
            'admin/Customer/addcreditCustomer',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 新增证件 */
        'Customer/addcard' => [
            'admin/Customer/addcard',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 根据证件号码获取用户信息 */
        'Customer/getcusinfo' => [
            'admin/Customer/getcusinfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 获取查档信息 */
        'CheckFile/getEstateinfo' => [
            'admin/CheckFile/getEstateinfo',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 再次查档 */
        'CheckFile/checkAgain' => [
            'admin/CheckFile/checkAgain',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 获取查档操作记录 */
        'CheckFile/checkRecords' => [
            'admin/CheckFile/checkRecords',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 获取顶级部门 */
        'SystemDept/getTopdept' => [
            'admin/SystemDept/getTopdept',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 获取下级部门 */
        'SystemDept/getDowndept' => [
            'admin/SystemDept/getDowndept',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 获取上级部门 */
        'SystemDept/getUpdept' => [
            'admin/SystemDept/getUpdept',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 模糊获取职位 */
        'SystemPosition/getAllposition' => [
            'admin/SystemPosition/getAllposition',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 根据部门获取下级部门已经下级部门下面的所有人 */
        'SystemUser/getDowndeptperson' => [
            'admin/SystemUser/getDowndeptperson',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 获取用户信息 */
        'User/getUserinfo' => [
            'admin/User/getUserinfo',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 支票出账列表 */
        'BankAccount/checkList' => [
            'admin/BankAccount/checkList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 出账详情页 */
        'BankAccount/checkDetail' => [
            'admin/BankAccount/checkDetail',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 出账流水 */
        'BankAccount/accountFlow' => [
            'admin/BankAccount/accountFlow',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 确认出账 */
        'BankAccount/determineAccount' => [
            'admin/BankAccount/determineAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 审核出账 */
        'BankAccount/reviewAccount' => [
            'admin/BankAccount/reviewAccount',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 驳回出账 */
        'BankAccount/turndownAccount' => [
            'admin/BankAccount/turndownAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 退回派单 */
        'BankAccount/backAccount' => [
            'admin/BankAccount/backAccount',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 现金出账列表 */
        'BankAccount/cashList' => [
            'admin/BankAccount/cashList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 出账跟踪 */
        'BankAccount/trackacountList' => [
            'admin/BankAccount/trackacountList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 获取出账银行 */
        'BankCard/getAllbank' => [
            'admin/BankCard/getAllbank',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 赎楼列表 */
        'Foreclosure/ransomList' => [
            'admin/Foreclosure/ransomList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 赎楼详情 */
        'Foreclosure/ransomDetail' => [
            'admin/Foreclosure/ransomDetail',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 查看详情 */
        'Foreclosure/lookdetail' => [
            'admin/Foreclosure/lookdetail',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 获取账户类型 */
        'Foreclosure/getOrderreceipt' => [
            'admin/Foreclosure/getOrderreceipt',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 退回派单 */
        'Foreclosure/backOrder' => [
            'admin/Foreclosure/backOrder',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 模糊获取赎楼员 */
        'Foreclosure/getRomsomer' => [
            'admin/Foreclosure/getRomsomer',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 改派赎楼员 */
        'Foreclosure/changeRomsomer' => [
            'admin/Foreclosure/changeRomsomer',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 完成赎楼 */
        'Foreclosure/completeRomsom' => [
            'admin/Foreclosure/completeRomsom',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 确认扣款 */
        'Foreclosure/determineMoney' => [
            'admin/Foreclosure/determineMoney',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 重新编辑扣款金额 */
        'Foreclosure/reEditmoney' => [
            'admin/Foreclosure/reEditmoney',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 上传回执 */
        'Foreclosure/uploadReceipt' => [
            'admin/Foreclosure/uploadReceipt',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 申请出账 */
        'Foreclosure/applyAccount' => [
            'admin/Foreclosure/applyAccount',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 财务核销列表 */
        'FinancialWriteoff/financialOff' => [
            'admin/FinancialWriteoff/financialOff',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 财务核销详情 */
        'FinancialWriteoff/financialDetail' => [
            'admin/FinancialWriteoff/financialDetail',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 确认核销 */
        'FinancialWriteoff/determineWriteoff' => [
            'admin/FinancialWriteoff/determineWriteoff',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 获取所有后台系统 */
        'System/getAllsystem' => [
            'admin/System/getAllsystem',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 核卡列表 */
        'Nuclearcard/nuclearcardList' => [
            'admin/Nuclearcard/nuclearcardList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 赎楼卡信息 */
        'Nuclearcard/redemptioncardInfo' => [
            'admin/Nuclearcard/redemptioncardInfo',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 录入核卡信息 */
        'Nuclearcard/addnuclearCarddata' => [
            'admin/Nuclearcard/addnuclearCarddata',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 核卡查询记录 */
        'Nuclearcard/nuclearRecord' => [
            'admin/Nuclearcard/nuclearRecord',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 新增查账记录 */
        'Nuclearcard/addnuclearRecord' => [
            'admin/Nuclearcard/addnuclearRecord',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 核卡审核通过 */
        'Nuclearcard/nuclearReview' => [
            'admin/Nuclearcard/nuclearReview',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取核卡录入信息 */
        'Nuclearcard/nuclearEntryinfo' => [
            'admin/Nuclearcard/nuclearEntryinfo',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 核卡审核驳回 */
        'Nuclearcard/nuclearBack' => [
            'admin/Nuclearcard/nuclearBack',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取录卡信息 */
        'Nuclearcard/getOtherinfo' => [
            'admin/Nuclearcard/getOtherinfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取所有公司 */
        'System/getAllcompany' => [
            'admin/System/getAllcompany',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /*         * *                                                       资金管理start                                       ** */
        /* 银行额度管理列表 */
        'FundManagement/bankquotaList' => [
            'admin/FundManagement/bankquotaList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 新增额度 */
        'FundManagement/addQuota' => [
            'admin/FundManagement/addQuota',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 编辑额度 */
        'FundManagement/getquotaInfo' => [
            'admin/FundManagement/getquotaInfo',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 额度详情 */
        'FundManagement/quotaDetail' => [
            'admin/FundManagement/quotaDetail',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 增存保证金 */
        'FundManagement/addorcutDeposit' => [
            'admin/FundManagement/addorcutDeposit',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 设置净资产值 */
        'FundManagement/setassetValue' => [
            'admin/FundManagement/setassetValue',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 设置启用禁用 */
        'FundManagement/setStatus' => [
            'aadmin/FundManagement/setStatus',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 新增渠道 */
        'FundManagement/addChannel' => [
            'admin/FundManagement/addChannel',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取渠道信息 */
        'FundManagement/getchannelInfo' => [
            'admin/FundManagement/getchannelInfo',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 渠道现金列表 */
        'FundManagement/channelcashList' => [
            'admin/FundManagement/channelcashList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 渠道现金详情 */
        'FundManagement/channelDetail' => [
            'admin/FundManagement/channelDetail',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* 新增渠道账户 */
        'FundManagement/addChannelacount' => [
            'admin/FundManagement/addChannelacount',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取渠道账户信息 */
        'FundManagement/getchannelacountInfo' => [
            'admin/FundManagement/getchannelacountInfo',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /*         * *                                                       资金管理end                                       ** */
        /* 林桂均 */
        /* 获取银行 */
        'Bank/getBank' => [
            'admin/Bank/getBank',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取支行 */
        'Bank/getBranch' => [
            'admin/Bank/getBranch',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取城市 */
        'Regions/getCity' => [
            'admin/Regions/getCity',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 获取省 */
        'Regions/getProvince' => [
            'admin/Regions/getProvince',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 获取楼盘城区 */
        'Regions/getBuildingCity' => [
            'admin/Regions/getBuildingCity',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取城区片区 */
        'Regions/getDistrict' => [
            'admin/Regions/getDistrict',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 搜索用户获取部门 */
        'User/userSearch' => [
            'admin/User/userSearch',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 获取楼盘信息 */
        'BuildingInfo/getBuilding' => [
            'admin/BuildingInfo/getBuilding',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 新增楼盘信息 */
        'BuildingInfo/addBuilding' => [
            'admin/BuildingInfo/addBuilding',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取栋阁信息 */
        'BuildingUnit/getUnit' => [
            'admin/BuildingUnit/getUnit',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 新增栋阁信息 */
        'BuildingUnit/addUnit' => [
            'admin/BuildingUnit/addUnit',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取楼层信息 */
        'BuildingUnit/getFloor' => [
            'admin/BuildingUnit/getFloor',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 新增楼层信息 */
        'BuildingUnit/addFloor' => [
            'admin/BuildingUnit/addFloor',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取房号信息 */
        'BuildingUnit/getHouse' => [
            'admin/BuildingUnit/getHouse',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 新增房号信息 */
        'BuildingUnit/addHouse' => [
            'admin/BuildingUnit/addHouse',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 新增订单 */
        'Orders/addOrder' => [
            'admin/Orders/addOrder',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 编辑订单 */
        'Orders/orderEdit' => [
            'admin/Orders/orderEdit',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 订单列表 */
        'Orders/orderList' => [
            'admin/Orders/orderList',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 短期借款列表 */
        'Orders/dqjkList' => [
            'admin/Orders/dqjkList',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 订单详情 */
        'Orders/orderDetails' => [
            'admin/Orders/orderDetails',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 订单状态 */
        'Orders/orderStatus' => [
            'admin/Orders/orderStatus',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 订单来源 */
        'Orders/orderSource' => [
            'admin/Orders/orderSource',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 订单日志 */
        'Log/orderLog' => [
            'admin/Log/orderLog',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 订单产证类型 */
        'Orders/houseType' => [
            'admin/Orders/houseType',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 担保赎楼信息 */
        'Orders/orderGuarantee' => [
            'admin/Orders/orderGuarantee',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 现金垫资信息 */
        'Orders/cashMatInfo' => [
            'admin/Orders/cashMatInfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 撤回订单 */
        'Orders/recallOrder' => [
            'admin/Orders/recallOrder',
            ['method' => 'post']
        ],
        /* 查询客户管理客户信息 */
        'Customer/ZCCustomer' => [
            'admin/Customer/ZCCustomer',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 新增客户管理客户信息 */
        'Customer/addZCCustomer' => [
            'admin/Customer/addZCCustomer',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 更新客户管理客户信息 */
        'Customer/updateZCCustomer' => [
            'admin/Customer/updateZCCustomer',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取部门组织架构 */
        'SystemDept/index' => [
            'admin/SystemDept/index',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取部门人员 */
        'User/userByDeptId' => [
            'admin/User/userByDeptId',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 展期费申请沟通获取部门人员 */
        'User/newUserByDeptId' => [
            'admin/User/newUserByDeptId',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 获取合作中介公司 */
        'Index/companyAgency' => [
            'admin/Index/companyAgency',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 赎楼员正常派单列表 */
        'Ransomer/index' => [
            'admin/Ransomer/index',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 赎楼员其他派单列表 */
        'Ransomer/otherList' => [
            'admin/Ransomer/otherList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 指派赎楼员派单 */
        'Ransomer/dispatchList' => [
            'admin/Ransomer/dispatchList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        'Ransomer/addDispatch' => [
            'admin/Ransomer/addDispatch',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 退回派单列表 */
        'Ransomer/returnDispatchList' => [
            'admin/Ransomer/returnDispatchList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 退回派单指派 */
        'Ransomer/updateDispatch' => [
            'admin/Ransomer/updateDispatch',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 派单详情 */
        'Ransomer/dispatchDetails' => [
            'admin/Ransomer/dispatchDetails',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 权证列表 */
        'OrderWarrant/index' => [
            'admin/OrderWarrant/index',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 注销抵押列表 */
        'OrderWarrant/mortgageList' => [
            'admin/OrderWarrant/mortgageList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 递件过户列表 */
        'OrderWarrant/ownershipList' => [
            'admin/OrderWarrant/ownershipList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 领取新证列表 */
        'OrderWarrant/newCertList' => [
            'admin/OrderWarrant/newCertList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 抵押新证列表 */
        'OrderWarrant/newMortgageList' => [
            'admin/OrderWarrant/newMortgageList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 完成取证 */
        'OrderWarrant/update' => [
            'admin/OrderWarrant/update',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 完成注销抵押 */
        'OrderWarrant/updateMortgage' => [
            'admin/OrderWarrant/updateMortgage',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 完成递件过户 */
        'OrderWarrant/updateOwnership' => [
            'admin/OrderWarrant/updateOwnership',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 完成领取新证 */
        'OrderWarrant/updateNewCert' => [
            'admin/OrderWarrant/updateNewCert',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 完成新证抵押 */
        'OrderWarrant/updateNewMortgage' => [
            'admin/OrderWarrant/updateNewMortgage',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 权证详情 */
        'OrderWarrant/details' => [
            'admin/OrderWarrant/details',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 资料送审 */
        'OrderWarrant/dataSendList' => [
            'admin/OrderWarrant/dataSendList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 确认送审 */
        'OrderWarrant/updateData' => [
            'admin/OrderWarrant/updateData',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 送审审核 */
        'OrderWarrant/reviewData' => [
            'admin/OrderWarrant/reviewData',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 变更渠道 */
        'OrderWarrant/changeChannel' => [
            'admin/OrderWarrant/changeChannel',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 获取资金渠道 */
        'OrderRelated/fundChannel' => [
            'admin/OrderRelated/fundChannel',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 获取订单账户类型 */
        'OrderRelated/orderAccountType' => [
            'admin/OrderRelated/orderAccountType',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 获取订单状态 */
        'OrderRelated/orderStage' => [
            'admin/OrderRelated/orderStage',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /* 综合订单列表 */
        'Orders/totalOrderList' => [
            'admin/Orders/totalOrderList',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 更新 */
        'AppUpdate/index' => [
            'admin/AppUpdate/index',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* APP版本更新 -列表 */
        'AppUpdate/index' => [
            'admin/AppUpdate/index',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* APP版本更新 -编辑页获取信息 */
        'AppUpdate/getinfo' => [
            'admin/AppUpdate/getinfo',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /* APP版本更新 -添加/编辑 */
        'AppUpdate/modify' => [
            'admin/AppUpdate/modify',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* APP版本更新 -删除 */
        'AppUpdate/delete' => [
            'admin/AppUpdate/delete',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 回款管理列表 */
        'FinancialReceipts/index' => [
            'admin/FinancialReceipts/index',
            ['method' => 'get']
        ],
        /* 费用信息列表 */
        'FinancialReceipts/collectList' => [
            'admin/FinancialReceipts/collectList',
            ['method' => 'get']
        ],
        /* 展期信息列表 */
        'FinancialReceipts/extensionList' => [
            'admin/FinancialReceipts/extensionList',
            ['method' => 'get']
        ],
        /* 回款入账审核列表 */
        'FinancialReceipts/payBackList' => [
            'admin/FinancialReceipts/payBackList',
            ['method' => 'get']
        ],
        /* 回款完成 */
        'FinancialReceipts/finishPayback' => [
            'admin/FinancialReceipts/finishPayback',
            ['method' => 'post']
        ],
        /* 回款完成待复核|回款完成待核算 */
        'FinancialReceipts/payBackRecheck' => [
            'admin/FinancialReceipts/payBackRecheck',
            ['method' => 'post']
        ],
        /* 回款入账复核 | 回款入账核算 */
        'FinancialReceipts/payBackEnterRecheck' => [
            'admin/FinancialReceipts/payBackEnterRecheck',
            ['method' => 'post']
        ],
        /*出账回款记录 */
        'FinancialReceipts/costList' => [
            'admin/FinancialReceipts/costList',
            ['method' => 'get']
        ],

        /* 财务回款详情 */
        'FinancialReceipts/FinancialBackdetail' => [
            'admin/FinancialReceipts/FinancialBackdetail',
            ['method' => 'post']
        ],
        /* 新增/编辑回款入账 */
        'FinancialReceipts/addBackmoneyrecord' => [
            'admin/FinancialReceipts/addBackmoneyrecord',
            ['method' => 'post']
        ],
        /* 获取所有回款卡账户信息 */
        'FinancialReceipts/getAllreceiptcard' => [
            'admin/FinancialReceipts/getAllreceiptcard',
            ['method' => 'get']
        ],
        /* 获取所有回款卡账户信息 */
        'FinancialReceipts/getAllreceiptincard' => [
            'admin/FinancialReceipts/getAllreceiptincard',
            ['method' => 'get']
        ],
        /* 获取回款记录信息 */
        'FinancialReceipts/getReceiptinfo' => [
            'admin/FinancialReceipts/getReceiptinfo',
            ['method' => 'get']
        ],
        /*导出征信列表*/
        'Credit/exportCredit' => [
            'admin/Credit/exportCredit',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /*导出风控管理-分单表列表*/
        'Approval/exportDistribute' => [
            'admin/Approval/exportDistribute',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*导出发送指令（额度）已发送列表*/
        'Financial/exportInstructionHas' => [
            'admin/Financial/exportInstructionHas',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*导出综合查询列表*/
        'Orders/exportOrderList' => [
            'admin/Orders/exportOrderList',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /* 跟进派单提成表导出 */
        'Foreclosure/ransomOutaccountExport' => [
            'admin/Foreclosure/ransomOutaccountExport',
            ['method' => 'get']
        ],
        /* 跟进派单出账表导出 */
        'Foreclosure/ransomAllOutaccountExport' => [
            'admin/Foreclosure/ransomAllOutaccountExport',
            ['method' => 'get']
        ],
        //导出风控管理-出保函列表
        'Approval/exportGuaranteeLetterOut' => [
            'admin/Approval/exportGuaranteeLetterOut',
            ['method' => 'post']
        ],
        /*************折扣资料修改-begin*************/
        /*折扣申请列表*/
        'OrderOthers/discountApplyList' => [
            'admin/OrderOthers/discountApplyList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /*折扣申请审批状态*/
        'OrderOthers/getStage' => [
            'admin/OrderOthers/getStage',
            ['method' => 'get']
        ],
        /*新增折扣申请订单基本信息*/
        'OrderOthers/discountOrderDetail' => [
            'admin/OrderOthers/discountOrderDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /*新增折扣申请*/
        'OrderOthers/addDiscount' => [
            'admin/OrderOthers/addDiscount',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /*编辑折扣申请*/
        'OrderOthers/editDiscount' => [
            'admin/OrderOthers/editDiscount',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /*折扣申请详情*/
        'OrderOthers/discountDetails' => [
            'admin/OrderOthers/discountDetails',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /*折扣申请审核列表*/
        'OrderOthers/discountApprovalList' => [
            'admin/OrderOthers/discountApprovalList',
            ['method' => 'get', 'after_behavior' => $afterBehavior]
        ],
        /*提交折扣申请审批*/
        'OrderOthers/subDealWith' => [
            'admin/OrderOthers/subDealWith',
            ['method' => 'post', 'after_behavior' => $afterBehavior]
        ],
        /*************折扣资料修改-end*************/
        /*要事审批列表*/
        'CostApply/importantMatterList' => [
            'admin/CostApply/importantMatterList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*添加要事审批申请*/
        'CostApply/addImportantMatter' => [
            'admin/CostApply/addImportantMatter',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*要事审批页面信息*/
        'CostApply/importantMatterRecords' => [
            'admin/CostApply/importantMatterRecords',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*编辑要事审批页面信息*/
        'CostApply/editImportantMatter' => [
            'admin/CostApply/editImportantMatter',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*提交编辑要事审批*/
        'CostApply/exitImportantMatter' => [
            'admin/CostApply/exitImportantMatter',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        
        /*要事审批提交审批*/
        'CostApply/importantMatSubDealWith' => [
            'admin/CostApply/importantMatSubDealWith',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],

        /*待派单列表*/
        'OrderWarrant/stayDispatchList' => [
            'admin/OrderWarrant/stayDispatchList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*已派单列表*/
        'OrderWarrant/dispatchList' => [
            'admin/OrderWarrant/dispatchList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        /*送审详情页*/
        'OrderWarrant/sendToReviewDetail' => [
            'admin/OrderWarrant/sendToReviewDetail',
            ['method' => 'get', 'after_behavior' => $afterBehavior],
        ],
        /*获取渠道员*/
        'OrderWarrant/ChannelMemList' => [
            'admin/OrderWarrant/ChannelMemList',
            ['method' => 'get'],
        ],
        /*指定渠道员*/
        'OrderWarrant/AssigningChannelMem' => [
            'admin/OrderWarrant/AssigningChannelMem',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        
        '__miss__' => ['admin/Miss/index'],
    ],
];