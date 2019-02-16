define({ "api": [
  {
    "type": "post",
    "url": "admin/AccountManagement/accountSettingList",
    "title": "账号设置列表[admin/AccountManagement/accountSettingList]",
    "version": "1.0.0",
    "name": "accountSettingList",
    "group": "AccountManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/AccountManagement/accountSettingList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1银行放款入账 2赎楼员账户 3渠道放款入账 4额度类出账 5现金类出账 6财务回款</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n    \"data\": [\n            {\n            \"id\": 13,                                     银行账户id\n            \"name\": \"中诚致信_建行_0773\",                  账户名称\n            \"bank_card\": \"44201532700052550773 \",          银行卡号\n            \"bank_account\": \"深圳市中诚致信融资担保有限公司 \",     银行户名\n            \"account_type\": 1,                                   账户类型 1公司 2个人\n            \"bank\": \"建设银行\",                                开户银行\n            \"open_city\": \"深圳\",                              所属城市\n            \"status\": 1                        账号状态 -1删除 1正常 2停用 3已销户 4已退回\n            },\n            {\n            \"id\": 14,\n            \"name\": \"中诚致信_建行_0117\",\n            \"bank_card\": \"1831014210000117\",\n            \"bank_account\": \"深圳市中诚致信融资担保有限公司 \",\n            \"account_type\": 1,\n            \"bank\": \"民生银行\",\n            \"open_city\": \"深圳\",\n            \"status\": 1\n            }\n        ]",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/AccountManagement.php",
    "groupTitle": "AccountManagement"
  },
  {
    "type": "post",
    "url": "admin/AccountManagement/addAccount",
    "title": "添加银行账户[admin/AccountManagement/addAccount]",
    "version": "1.0.0",
    "name": "addAccount",
    "group": "AccountManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/AccountManagement/addAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_type",
            "description": "<p>账户类型 1公司账户 2个人账户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_account",
            "description": "<p>银行户名</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "bank_card",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "open_city",
            "description": "<p>开户城市(传名称,比如 深圳)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>开户银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>开户支行</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>账号状态  -1删除 1正常 2停用 3已销户 4已退回</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "account_manager",
            "description": "<p>账号负责人</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_manager_uid",
            "description": "<p>账号负责人id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_nature",
            "description": "<p>账号性质(账户类型为公司需传此参数)  1基本账户 2一般账户 3临时账户 4专用庄户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "key_transactor",
            "description": "<p>经办key管理员(账户类型为公司需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "key_transactor_uid",
            "description": "<p>经办key管理员id(账户类型为公司需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "key_reviewer",
            "description": "<p>复核key管理员(账户类型为公司需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "key_reviewer_uid",
            "description": "<p>复核key管理员id(账户类型为公司需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "account_possess",
            "description": "<p>所属员工(账户类型为个人需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_possess_uid",
            "description": "<p>所属员工ID(账户类型为个人需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_way",
            "description": "<p>对账方式 1网上对账 2邮寄对账 3其他方式</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_review",
            "description": "<p>是否需要复核 0 否 1 是</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_cycle",
            "description": "<p>对账周期   1 每月 2 每季度</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_time",
            "description": "<p>对账时间    1 次日 2 月初</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "single_limit_public",
            "description": "<p>单笔限额 对公</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "single_limit_private",
            "description": "<p>单笔限额 对私</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "day_limit_public",
            "description": "<p>单日限额 对公</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "day_limit_private",
            "description": "<p>单日限额 对私</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "customer_manager",
            "description": "<p>客户经理姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "customer_manager_mobile",
            "description": "<p>客户经理联系电话</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch_address",
            "description": "<p>支行地址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "account_use",
            "description": "<p>账号用途(传对应用途的code组成的字符串，比如 2,3,4) 1收费 2出账 3过账 4赎楼 5保证金 6其它</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "card_front",
            "description": "<p>卡号照片正面地址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "card_back",
            "description": "<p>卡号照片反面地址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注说明</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/AccountManagement.php",
    "groupTitle": "AccountManagement"
  },
  {
    "type": "post",
    "url": "admin/AccountManagement/addBankAccount",
    "title": "新增账号[admin/AccountManagement/addBankAccount]",
    "version": "1.0.0",
    "name": "addBankAccount",
    "group": "AccountManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/AccountManagement/addBankAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>银行账号id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>账号名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>账户类型 1银行放款入账 2赎楼员账户 3渠道放款入账 4额度类出账 5现金类出账 6财务回款</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/AccountManagement.php",
    "groupTitle": "AccountManagement"
  },
  {
    "type": "post",
    "url": "admin/AccountManagement/bankAccountList",
    "title": "银行账户列表[admin/AccountManagement/bankAccountList]",
    "version": "1.0.0",
    "name": "bankAccountList",
    "group": "AccountManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/AccountManagement/bankAccountList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_type",
            "description": "<p>账户类型 1公司账户 2个人账户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>开户银行  中国银行 中国建设银行</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_use",
            "description": "<p>账号用途 1收费 2出账 3过账 4赎楼 5保证金 6其它</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>账号状态（-1删除 1正常 2停用 3已销户 4已退回）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n            \"total\": 2,\n            \"per_page\": 10,\n            \"current_page\": 1,\n            \"last_page\": 1,\n            \"data\": [\n                {\n                \"bank_card\": \"6218002532145268\",    银行卡号\n                \"bank_account\": \"武汉汇金\",         银行户名\n                \"account_type\": 1,                  账户类型 1公司账户 2个人账户\n                \"bank\": \"中国建设银行\",             开户银行\n                \"open_city\": \"深圳\",                所属城市\n                \"status\": 1,                       账号状态 -1删除 1正常 2停用 3已销户 4已退回\n                \"account_manager\": \"刘传英\",       账号负责人\n                \"update_time\": \"1974-11-28 17:55:46\"    更新时间\n                },\n                {\n                \"bank_card\": \"6217002870005119528\",\n                \"bank_account\": \"中诚致信\",\n                \"account_type\": 1,\n                \"bank\": \"中国银行\",\n                \"open_city\": \"1\",\n                \"status\": 1,\n                \"account_manager\": \"test\",\n                \"update_time\": \"1974-10-23 09:24:12\"\n                }\n            ]\n        }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/AccountManagement.php",
    "groupTitle": "AccountManagement"
  },
  {
    "type": "post",
    "url": "admin/AccountManagement/bankCardList",
    "title": "银行卡号列表[admin/AccountManagement/bankCardList]",
    "version": "1.0.0",
    "name": "bankCardList",
    "group": "AccountManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/AccountManagement/bankCardList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\n    \"data\": [\n        {\n        \"id\": 17,                                 银行账号id\n        \"bank_card\": \"6226220617619738\"           银行卡号\n        \"bank\": \"民生银行\",                       开户银行\n        \"bank_account\": \"丘翠娥\"                  银行户名\n        },\n        {\n        \"id\": 19,\n        \"bank_card\": \"6226220636060609\"\n        \"bank\": \"民生银行\",\n        \"bank_account\": \"丘翠娥\"\n        }\n     ]",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/AccountManagement.php",
    "groupTitle": "AccountManagement"
  },
  {
    "type": "post",
    "url": "admin/AccountManagement/delBankAccount",
    "title": "移除账号[admin/AccountManagement/delBankAccount]",
    "version": "1.0.0",
    "name": "delBankAccount",
    "group": "AccountManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/AccountManagement/delBankAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>银行账号id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/AccountManagement.php",
    "groupTitle": "AccountManagement"
  },
  {
    "type": "post",
    "url": "admin/AccountManagement/editAccount",
    "title": "编辑银行账户[admin/AccountManagement/editAccount]",
    "version": "1.0.0",
    "name": "editAccount",
    "group": "AccountManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/AccountManagement/editAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>银行账户id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_type",
            "description": "<p>账户类型 1公司账户 2个人账户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_account",
            "description": "<p>银行户名</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "bank_card",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "open_city",
            "description": "<p>开户城市(传名称,比如 深圳)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>开户银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>开户支行</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>账号状态  -1删除 1正常 2停用 3已销户 4已退回</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "account_manager",
            "description": "<p>账号负责人</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_manager_uid",
            "description": "<p>账号负责人id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_nature",
            "description": "<p>账号性质(账户类型为公司需传此参数)  1基本账户 2一般账户 3临时账户 4专用庄户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "key_transactor",
            "description": "<p>经办key管理员(账户类型为公司需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "key_transactor_uid",
            "description": "<p>经办key管理员id(账户类型为公司需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "key_reviewer",
            "description": "<p>复核key管理员(账户类型为公司需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "key_reviewer_uid",
            "description": "<p>复核key管理员id(账户类型为公司需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "account_possess",
            "description": "<p>所属员工(账户类型为个人需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_possess_uid",
            "description": "<p>所属员工ID(账户类型为个人需传此参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_way",
            "description": "<p>对账方式 1网上对账 2邮寄对账 3其他方式</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_review",
            "description": "<p>是否需要复核 0 否 1 是</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_cycle",
            "description": "<p>对账周期   1 每月 2 每季度</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_time",
            "description": "<p>对账时间    1 次日 2 月初</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "single_limit_public",
            "description": "<p>单笔限额 对公</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "single_limit_private",
            "description": "<p>单笔限额 对私</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "day_limit_public",
            "description": "<p>单日限额 对公</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "day_limit_private",
            "description": "<p>单日限额 对私</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "customer_manager",
            "description": "<p>客户经理姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "customer_manager_mobile",
            "description": "<p>客户经理联系电话</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch_address",
            "description": "<p>支行地址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "account_use",
            "description": "<p>账号用途(传选择对应用途的code组成的字符串，比如 2,3,4) 1收费 2出账 3过账 4赎楼 5保证金 6其它</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "card_front",
            "description": "<p>卡号照片正面地址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "card_back",
            "description": "<p>卡号照片反面地址</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注说明</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/AccountManagement.php",
    "groupTitle": "AccountManagement"
  },
  {
    "type": "post",
    "url": "admin/AccountManagement/getOneAccount",
    "title": "查询出单个银行账户信息[admin/AccountManagement/getOneAccount]",
    "version": "1.0.0",
    "name": "getOneAccount",
    "group": "AccountManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/AccountManagement/getOneAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>银行账户id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n            \"id\": 1,                                 银行账户id\n            \"account_type\": 1,                       账户类型 1公司账户 2个人账户\n            \"bank_account\": \"中诚致信\",              银行户名\n            \"bank_card\": \"6217002870005119528\",      银行卡号\n            \"open_city\": \"1\",                        开户城市\n            \"bank\": \"中国银行\",                      开户银行\n            \"bank_branch\": \"中国银行-车公庙支行\",    开户支行\n            \"account_manager\": \"test\",              账号负责人\n            \"account_manager_uid\": 1,\n            \"account_possess\": null,                所属员工\n            \"account_possess_uid\": null,\n            \"account_nature\": 1,                  账号性质  1基本账户 2一般账户 3临时账户 4专用庄户\n            \"key_transactor\": \"test\",             经办key管理员\n            \"key_transactor_uid\": 1,\n            \"key_reviewer\": \"test\",              复核key管理员\n            \"key_reviewer_uid\": 1,\n            \"account_way\": 1,                    对账方式 1网上对账 2邮寄对账 3其他方式\n            \"is_review\": 1,                      是否需要复核 0 否 1 是\n            \"account_cycle\": \"1\",                对账周期   1 每月 2 每季度\n            \"account_time\": \"1\",                 对账时间    1 次日 2 月初\n            \"single_limit_public\": \"15289.00\",    单笔限额 对公\n            \"single_limit_private\": \"15465.00\",   单笔限额 对私\n            \"day_limit_public\": \"54165.00\",       单日限额 对公\n            \"day_limit_private\": \"1465.00\",        单日限额 对私\n            \"customer_manager\": \"李玉刚\",          客户经理姓名\n            \"customer_manager_mobile\": \"15172341845\",    客户经理联系电话\n            \"bank_branch_address\": \"福田区车公庙\",        支行地址\n            \"account_use\": \"1,2,3\",                      账号用途 1收费 2出账 3过账 4赎楼 5保证金 6其它\n            \"card_front\": \"www.img.com/image.png\",       卡号照片正面地址\n            \"card_back\": \"www.img.com/image.png\",        卡号照片反面地址\n            \"remark\": \"测试数据测试数据\",                 备注说明\n            \"sort\": null,\n            \"status\": 1,                             账号状态  -1删除 1正常 2停用 3已销户 4已退回\n            \"create_uid\": 1,\n            \"create_time\": \"1974-10-23 09:24:12\",\n            \"update_time\": \"1974-10-23 09:24:12\",\n            \"delete_time\": null\n         }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/AccountManagement.php",
    "groupTitle": "AccountManagement"
  },
  {
    "type": "post",
    "url": "admin/Approval/addData",
    "title": "提交资料[admin/Approval/addData]",
    "version": "1.0.0",
    "name": "addData",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/addData"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "datainfo",
            "description": "<p>缺少的资料['id(int)'=&gt;'缺少的资料id','describe（string）'=&gt;'资料描述','status{int}'=&gt;'资料状态 0未收 1已收']</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/addResult",
    "title": "初审结果提交[admin/Approval/addResult]",
    "version": "1.0.0",
    "name": "addResult",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/addResult"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "balance_per",
            "description": "<p>负债成数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_normal",
            "description": "<p>是否正常单 0正常 1异常单</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "review_rating",
            "description": "<p>审查评级</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "risk_rating",
            "description": "<p>风险评级</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_material",
            "description": "<p>是否缺资料通过 0未选中,不缺资料   1选中,缺资料</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_guarantee",
            "description": "<p>是否提供反担保 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_asset_prove",
            "description": "<p>是否提供资产证明 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_guarantee_estate",
            "description": "<p>是否房产反担保 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_guarantee_money",
            "description": "<p>是否保证金反担保 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_guarantee_other",
            "description": "<p>是否其它方式反担保 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "guarantee_money",
            "description": "<p>反担保 （保证金）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "other_way",
            "description": "<p>其它方式</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "problem",
            "description": "<p>问题汇总['id(int)'=&gt;'问题汇总信息id,新增问题,则这个id可以为空','problem_describe（string）'=&gt;'问题描述','problem_status{int}'=&gt;'问题状态 0未解决 1已解决']</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>缺少的资料['id(int)'=&gt;'缺少的资料id,新增资料,则这个id为空','problem_describe（string）'=&gt;'资料描述','problem_status{int}'=&gt;'资料状态 0未收 1已收']</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "houseinfo",
            "description": "<p>反担保房产信息['id(int)'=&gt;'房产id,新增房产,则这个id为空','estate_owner(string)'=&gt;'产权人姓名','estate_owner_type（int）'=&gt;'产权人类型  1个人 2企业','estate_name{string}'=&gt;'房产名称', 'estate_region（string）'=&gt;所属城区,'estate_certtype（int）'=&gt;'产证类型','estate_certnum{int}'=&gt;'产证编码','house_type（int）'=&gt;房屋类型 1分户 2分栋, 'house_id（int）'=&gt;房号id ,'estate_ecity（int）'=&gt;城市简称,'estate_district（int）'=&gt;城区简称,'estate_area'=&gt;房产面积,'building_name'=&gt;楼盘名称,'estate_alias'=&gt;楼盘别名,'estate_unit'=&gt;楼阁名称,'estate_unit_alias'=&gt;楼阁别名,'estate_floor'=&gt;楼层,'estate_floor_plusminus'=&gt;楼层类型,'estate_house'=&gt;房号]</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "assetproof",
            "description": "<p>资产证明房产信息['id(int)'=&gt;'房产id,新增房产,则这个id为空','estate_owner(string)'=&gt;'产权人姓名','estate_owner_type（int）'=&gt;'产权人类型  1个人 2企业','estate_name{string}'=&gt;'房产名称','estate_region（string）'=&gt;所属城区,'estate_certtype（int）'=&gt;'产证类型','estate_certnum{int}'=&gt;'产证编码','house_type（int）'=&gt;房屋类型 1分户 2分栋,'house_id（int）'=&gt;房号id， 'estate_ecity（int）'=&gt;城市简称,'estate_district（int）'=&gt;城区简称,'estate_area'=&gt;房产面积,'building_name'=&gt;楼盘名称,'estate_alias'=&gt;楼盘别名,'estate_unit'=&gt;楼阁名称,'estate_unit_alias'=&gt;楼阁别名,'estate_floor'=&gt;楼层,'estate_floor_plusminus'=&gt;楼层类型,'estate_house'=&gt;房号]</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/approvalRecords",
    "title": "审批页面信息[admin/Approval/approvalRecords]",
    "version": "1.0.0",
    "name": "approvalRecords",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/approvalRecords"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n \"approval_records\": [\n     {\n      \"order_sn\": \"JYDB2018010001\",\n      \"create_time\": \"2018-05-08 15:24:57\",\n      \"process_name\": \"待跟单员补齐资料\",\n      \"auditor_name\": \"李依华\",\n      \"auditor_dept\": \"财务部\",\n      \"status\": \"通过\",\n      \"content\": \"同意\"\n      }\n  ],\n  \"other_information\": [\n    {\n      \"id\": 1,\n      \"order_sn\": \"JYDB2018010001\",\n      \"process_name\": \"收到公司的\",\n      \"item\": \"啥打法是否\",\n      \"fileinfo\": [\n          {\n          \"savename\": \"e259d9c4f11593187bf07f50418f6a22.jpg\",\n          \"path\": \"D:\\\\wamp\\\\www\\\\businesssys_api\\\\public\\\\\",\n          \"url\": \"\\\\uploads\\\\20180427\\\\e259d9c4f11593187bf07f50418f6a22.jpg\",\n          \"name\": \"222.xlsx\"\n          },\n          {\n          \"savename\": \"ad4091691f0f3995af2dcdb13bf5f5c6.jpg\",\n          \"path\": \"D:\\\\wamp\\\\www\\\\businesssys_api\\\\public\\\\\",\n          \"url\": \"\\\\uploads\\\\20180427\\\\ad4091691f0f3995af2dcdb13bf5f5c6.jpg\",\n           name\": \"222.xlsx\"\n          },\n          {\n          \"savename\": \"515af20f54072b6804f25c1e18d234a4.jpg\",\n          \"path\": \"D:\\\\wamp\\\\www\\\\businesssys_api\\\\public\\\\\",\n          \"url\": \"\\\\uploads\\\\20180427\\\\515af20f54072b6804f25c1e18d234a4.jpg\",\n          \"name\": \"222.xlsx\"\n          }\n       ]\n     },\n   ]\n }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>审批记录的时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "process_name",
            "description": "<p>审批节点</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "auditor_name",
            "description": "<p>操作人员名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "auditor_dept",
            "description": "<p>操作人员部门</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>操作</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>审批意见</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "proces_name",
            "description": "<p>流程信息(审批信息来源)</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "item",
            "description": "<p>注意事项</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "savename",
            "description": "<p>文件名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "path",
            "description": "<p>文件路径</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "url",
            "description": "<p>文件链接地址</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>原始文件名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/dataList",
    "title": "缺少的资料列表[admin/Approval/dataList]",
    "version": "1.0.0",
    "name": "dataList",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/dataList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "describe",
            "description": "<p>资料描述</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>0未收 1已收</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/delGuarantee",
    "title": "删除房产担保与资产证明[admin/Approval/delGuarantee]",
    "version": "1.0.0",
    "name": "delGuarantee",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/delGuarantee"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>房产表id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/delProblem",
    "title": "删除问题汇总与缺少资料[admin/Approval/delProblem]",
    "version": "1.0.0",
    "name": "delProblem",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/delProblem"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>数据的id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/exportDistribute",
    "title": "导出风控管理-分单表[admin/Approval/exportDistribute]",
    "version": "1.0.0",
    "name": "exportDistribute",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/exportDistribute"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "date_type",
            "description": "<p>1分单日期 2出保日期</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_ecity",
            "description": "<p>城市</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_district",
            "description": "<p>城区</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/exportGuaranteeLetterOut",
    "title": "导出风控管理-出保函表[admin/Approval/exportGuaranteeLetterOut]",
    "version": "1.0.0",
    "name": "exportGuaranteeLetterOut",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/exportGuaranteeLetterOut"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "date_type",
            "description": "<p>1分单日期 2出保日期</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_ecity",
            "description": "<p>城市</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_district",
            "description": "<p>城区</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/getAlllList",
    "title": "所有列表[admin/Approval/getAlllList]",
    "version": "1.0.0",
    "name": "getAlllList",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/getAlllList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_ecity",
            "description": "<p>城市</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_district",
            "description": "<p>城区</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "date_type",
            "description": "<p>日期类型（1分单日期 2出保日期）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/haveOnlList",
    "title": "已审列表[admin/Approval/haveOnlList]",
    "version": "1.0.0",
    "name": "haveOnlList",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/haveOnlList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_ecity",
            "description": "<p>城市</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_district",
            "description": "<p>城区</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/proceMaterialNode",
    "title": "待处理节点注意事项和附件材料[admin/Approval/proceMaterialNode]",
    "version": "1.0.0",
    "name": "proceMaterialNode",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/proceMaterialNode"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "proc_id",
            "description": "<p>处理明细表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"proc_id\":1\n      \"process_id\": 14,\n      \"id\": 1,\n      \"item\": \"啥打法是否\",\n      \"process_name\": \"待部门经理审批\",\n      \"next_process_name\": \"待审查主管审批\",\n      \"attachInfo\": [\n        {\n        \"id\": 2,\n        \"name\": \"40_2_full_res.jpg\",\n        \"url\": \"\\\\uploads\\\\20180427\\\\e259d9c4f11593187bf07f50418f6a22.jpg\"\n        },\n        {\n        \"id\": 1,\n        \"name\": \"40_4_full_res.jpg\",\n        \"url\": \"\\\\uploads\\\\20180427\\\\ad4091691f0f3995af2dcdb13bf5f5c6.jpg\"\n        },\n      ]\n   \"preprocess\": [\n      {\n      \"id\": 28,\n      \"entry_id\": 1,\n      \"flow_id\": 3,\n      \"process_id\": 20,\n      \"process_name\": \"待出保函\"\n      },\n      {\n      \"id\": 24,\n      \"entry_id\": 1,\n      \"flow_id\": 3,\n      \"process_id\": 16,\n      \"process_name\": \"待审查经理审批\"\n      },\n    ]\n    \"nextprocess_user\": [\n          {\n          \"id\": 345,\n          \"name\": \"李辉南1\"\n          },\n         {\n          \"id\": 346,\n          \"name\": \"李辉南2\"\n          },\n      ]\n      \"is_next_user\": 1,\n      \"stage\": \"1\"\n }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "proc_id",
            "description": "<p>处理明细表主键id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "process_id",
            "description": "<p>流程步骤表主键id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "process_name",
            "description": "<p>节点名称(当前步骤名称,审批节点)</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "next_process_name",
            "description": "<p>下一个审批节点名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_next_user",
            "description": "<p>是否需要选择下一步审查人员 0不需要 1需要</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>初审信息 注意事项表主键id</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "attachInfo",
            "description": "<p>缺少的资料['id(int)'=&gt;'附件表id','name（string）'=&gt;'附件名称','url{string}'=&gt;'文件链接地址']</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "preprocess",
            "description": "<p>退回节点下拉信息['id(int)'=&gt;'退回节点id','entry_id（int）'=&gt;'流程实例id','flow_id（int）'=&gt;'工作流定义表id','process_id（int）'=&gt;'流程步骤id','process_name（string）'=&gt;'返回节点名称']</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "nextprocess_user",
            "description": "<p>审查员信息['id(int)'=&gt;'下一步审批人员id','name（string）'=&gt;'下一步审批人员名称']</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "needingAttention",
            "description": "<p>处理审批里面的(注意事项)</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/showApprovalList",
    "title": "待审列表[admin/Approval/showApprovalList]",
    "version": "1.0.0",
    "name": "showApprovalList",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/showApprovalList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_ecity",
            "description": "<p>城市</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "estate_district",
            "description": "<p>城区</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"total\": 3,\n      \"per_page\": 20,\n      \"current_page\": 1,\n      \"last_page\": 1,\n      \"data\": [\n          {\n          \"proc_id\": 5,\n          \"id\": 30,\n          \"order_sn\": \"JYDB2018010001\",\n          \"create_time\": \"2018-04-20 14:23:51\",\n          \"type\": \"JYDB\",\n          \"money\": \"200.00\",\n          \"stage\": \"待业务报单\",\n          \"estate_name\": \"万达广场\",\n          \"estate_ecity\": \"440300\",\n          \"estate_district\": \"440304\",\n          \"name\": \"管理员\",\n          \"is_normal\": -1\n          \"inspector_name\": \"邓丽君\"    审查员名称\n          }\n      ]\n  }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "total",
            "description": "<p>总条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示的条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "current_page",
            "description": "<p>当前页</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "last_page",
            "description": "<p>总页数</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "create_time",
            "description": "<p>报单时间</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "money",
            "description": "<p>订单金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_ecity",
            "description": "<p>城市</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_district",
            "description": "<p>城区</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_normal",
            "description": "<p>是否正常 -1未知 0正常 1异常</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>订单表主键id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "proc_id",
            "description": "<p>处理明细表主键id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/showResult",
    "title": "查询初审结果[admin/Approval/showResult]",
    "version": "1.0.0",
    "name": "showResult",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/showResult"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n  \"code\": 1,\n  \"msg\": \"操作成功\",\n  \"data\": {\n  \"order_sn\": \"666666666\",\n  \"balance_per\": 66,\n  \"is_normal\": 66,\n  \"review_rating\": 66,\n  \"risk_rating\": 66,\n  \"is_material\": 1,\n  \"is_guarantee\": 1,\n  \"is_asset_prove\": 0,\n  \"is_guarantee_estate\": 0,\n  \"is_guarantee_money\": 1,\n  \"is_guarantee_other\": 1,\n  \"guarantee_money\": \"654.11\",\n  \"other_way\": \"我是谁\",\n  \"express_no\": null,\n  \"problem\": [\n      {\n     \"id\": 25,\n      \"describe\": \"呵呵1213\",\n      \"status\": 1\n      },\n      {\n     \"id\": 26,\n      \"describe\": \"呵呵456\",\n      \"status\": 0\n      },\n      {\n      \"id\": 27,\n      \"describe\": \"呵呵帅那个帅789\",\n      \"status\": 0\n      }\n  ],\n  \"data\": [\n      {\n      \"id\": 28,\n      \"describe\": \"初审1213\",\n      \"status\": 1\n      },\n      {\n      \"id\": 29,\n      \"describe\": \"别别别456\",\n      \"status\": 0\n      },\n      {\n      \"id\": 30,\n      \"describe\": \"呵呵帅那个帅789\",\n      \"status\": 0\n      }\n    ]\n    \"houseinfo\": [\n          {\n           \"id\": 26,\n           \"estate_owner\": null,\n           \"estate_owner_type\": null,\n           \"estate_name\": \"国际新城\",\n           \"estate_certtype\": 1,\n           \"estate_certnum\": 123456789,\n           \"house_type\": 1111,\n           \"estate_district\": \"440304\"\n          },\n         {\n          \"id\": 28,\n          \"estate_owner\": null,\n          \"estate_owner_type\": null,\n          \"estate_name\": \"万达广场\",\n          \"estate_certtype\": 1,\n          \"estate_certnum\": 123456789,\n          \"house_type\": 1111,\n          \"estate_district\": \"440304\"\n          }\n       ],\n      \"assetproof\": [\n          {\n          \"id\": 29,\n          \"estate_owner\": null,\n          \"estate_owner_type\": null,\n          \"estate_name\": \"万科\",\n          \"estate_certtype\": 1,\n          \"estate_certnum\": 123456789,\n          \"house_type\": 1111,\n          \"estate_district\": \"440304\"\n          },\n          {\n          \"id\": 30,\n          \"estate_owner\": null,\n          \"estate_owner_type\": null,\n          \"estate_name\": \"绿地\",\n          \"estate_certtype\": 1,\n          \"estate_certnum\": 123456789,\n          \"house_type\": 1111,\n          \"estate_district\": \"440304\"\n          }\n       ]\n    }\n  }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "balance_per",
            "description": "<p>负债成数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_normal",
            "description": "<p>是否正常单 0正常 1异常</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "review_rating",
            "description": "<p>审查评级</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "risk_rating",
            "description": "<p>风险评级</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_material",
            "description": "<p>是否缺资料通过 0未选中,不缺资料   1选中,缺资料</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_guarantee",
            "description": "<p>是否提供反担保 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_asset_prove",
            "description": "<p>是否提供资产证明 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_guarantee_estate",
            "description": "<p>是否房产反担保 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_guarantee_money",
            "description": "<p>是否保证金反担保 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_guarantee_other",
            "description": "<p>是否其它方式反担保 0未选中,否   1选中,是</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "guarantee_money",
            "description": "<p>反担保 （保证金）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "other_way",
            "description": "<p>其它方式</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "express_no",
            "description": "<p>订单号</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "problem",
            "description": "<p>问题汇总['id(int)'=&gt;'问题汇总id','describe（string）'=&gt;'问题描述','status{int}'=&gt;'问题状态 0未解决 1已解决']</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>缺少的资料['id(int)'=&gt;'缺少资料id','describe（string）'=&gt;'资料描述','status{int}'=&gt;'资料状态 0未收 1已收']</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "houseifo",
            "description": "<p>反担保房产信息['id(int)'=&gt;'房产id','estate_owner(string)'=&gt;'产权人姓名','estate_owner_type（int）'=&gt;'产权人类型  1个人 2企业','estate_name{string}'=&gt;'房产名称','estate_region（string）'=&gt;所属城区,'estate_certtype（int）'=&gt;'产证类型','estate_certnum{int}'=&gt;'产证编码','house_type（int）'=&gt;房屋类型 1分户 2分栋, 'house_id（int）'=&gt;房号id ,'estate_ecity（int）'=&gt;城市简称,'estate_district（int）'=&gt;城区简称,'estate_area'=&gt;房产面积,'building_name'=&gt;楼盘名称,'estate_alias'=&gt;楼盘别名,'estate_unit'=&gt;楼阁名称,'estate_unit_alias'=&gt;楼阁别名,'estate_floor'=&gt;楼层,'estate_floor_plusminus'=&gt;楼层类型,'estate_house'=&gt;房号]</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "assetproof",
            "description": "<p>资产证明房产信息['id(int)'=&gt;'房产id','estate_owner(string)'=&gt;'产权人姓名','estate_owner_type（int）'=&gt;'产权人类型  1个人 2企业','estate_name{string}'=&gt;'房产名称','estate_region（string）'=&gt;所属城区,'estate_certtype（int）'=&gt;'产证类型','estate_certnum{int}'=&gt;'产证编码','house_type（int）'=&gt;房屋类型 1分户 2分栋, 'house_id（int）'=&gt;房号id ,'estate_ecity（int）'=&gt;城市简称,'estate_district（int）'=&gt;城区简称,'estate_area'=&gt;房产面积,'building_name'=&gt;楼盘名称,'estate_alias'=&gt;楼盘别名,'estate_unit'=&gt;楼阁名称,'estate_unit_alias'=&gt;楼阁别名,'estate_floor'=&gt;楼层,'estate_floor_plusminus'=&gt;楼层类型,'estate_house'=&gt;房号]</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Approval/subApproval",
    "title": "提交审批[admin/Approval/subApproval]",
    "version": "1.0.0",
    "name": "subApproval",
    "group": "Approval",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/subApproval"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "proc_id",
            "description": "<p>处理明细表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_approval",
            "description": "<p>审批结果 1通过 2驳回</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>审批意见</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "next_user_id",
            "description": "<p>下一步审批人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "backtoback",
            "description": "<p>是否退回之后直接返回本节点 1 返回 不返回就不需要传值</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "back_proc_id",
            "description": "<p>退回节点id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "courierNumber",
            "description": "<p>运单号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "process_id",
            "description": "<p>流程步骤表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "process_name",
            "description": "<p>节点名称(当前步骤名称,审批节点)</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "attachment_id_str",
            "description": "<p>附件材料 [1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "item",
            "description": "<p>注意事项</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "next_process_name",
            "description": "<p>流向的审批节点名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_next_user",
            "description": "<p>是否需要选择审查人员 0不需要 1需要</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Approval.php",
    "groupTitle": "Approval"
  },
  {
    "type": "post",
    "url": "admin/Auth/add",
    "title": "新增权限组[admin/Auth/add]",
    "version": "1.0.0",
    "name": "add",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/add"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "groupid",
            "description": "<p>组id</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "rules",
            "description": "<p>组权限</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "description",
            "description": "<p>组描述</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "admin/Auth/delMember",
    "title": "指定组中添加指定用户[admin/Auth/addMember]",
    "version": "1.0.0",
    "name": "addMember",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/addMember"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "gid",
            "description": "<p>组id</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "uids",
            "description": "<p>用户id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "get",
    "url": "admin/Auth/changeStatus",
    "title": "权限组状态编辑[admin/Auth/changeStatus]",
    "version": "1.0.0",
    "name": "changeStatus",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/changeStatus"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>组id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>组状态</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "get",
    "url": "admin/Auth/del",
    "title": "删除组[admin/Auth/del]",
    "version": "1.0.0",
    "name": "del",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/del"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>组id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "get",
    "url": "admin/Auth/delMember",
    "title": "从指定组中删除指定用户[admin/Auth/delMember]",
    "version": "1.0.0",
    "name": "delMember",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/delMember"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "gid",
            "description": "<p>组id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "uid",
            "description": "<p>用户id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "admin/Auth/edit",
    "title": "编辑权限[admin/Auth/edit]",
    "version": "1.0.0",
    "name": "edit",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/edit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "groupid",
            "description": "<p>组id</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "rules",
            "description": "<p>组权限</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "description",
            "description": "<p>组描述</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "admin/Auth/editRule",
    "title": "编辑权限细节[admin/Auth/editRule]",
    "version": "1.0.0",
    "name": "editRule",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/editRule"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>组id</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "rules",
            "description": "<p>权限组</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "description",
            "description": "<p>组描述</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "get",
    "url": "admin/Auth/getGroups",
    "title": "获取全部已开放的可选组[admin/Auth/getGroups]",
    "version": "1.0.0",
    "name": "getGroups",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/getGroups"
      }
    ],
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "get",
    "url": "admin/Auth/getRuleList",
    "title": "获取组所在权限列表[admin/Auth/getRuleList]",
    "version": "1.0.0",
    "name": "getRuleList",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/getRuleList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "groupid",
            "description": "<p>组id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "get",
    "url": "admin/Auth/index",
    "title": "获取权限组列表[admin/Auth/index]",
    "version": "1.0.0",
    "name": "index",
    "group": "Auth",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Auth/index"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "get",
    "url": "admin/BankAccount/accountFlow",
    "title": "出账流水[admin/BankAccount/accountFlow]",
    "version": "1.0.0",
    "name": "accountFlow",
    "group": "BankAccount",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankAccount/accountFlow"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>派单id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "info",
            "description": "<p>统计信息</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "out_money",
            "description": "<p>已出账金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "use_money",
            "description": "<p>可用余额</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "totalarr",
            "description": "<p>列表信息</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>出账id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "item_text",
            "description": "<p>出账项目</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransom_bank",
            "description": "<p>赎楼银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "money",
            "description": "<p>出账金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransomer",
            "description": "<p>赎楼员</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "way_text",
            "description": "<p>出账方式</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>支票银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "cheque_num",
            "description": "<p>支票号码</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>申请时间</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BankAccount.php",
    "groupTitle": "BankAccount"
  },
  {
    "type": "get",
    "url": "admin/BankAccount/backAccount",
    "title": "退单[admin/BankAccount/backAccount]",
    "version": "1.0.0",
    "name": "backAccount",
    "group": "BankAccount",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankAccount/backAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>出账id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BankAccount.php",
    "groupTitle": "BankAccount"
  },
  {
    "type": "get",
    "url": "admin/BankAccount/cashList",
    "title": "现金出账列表[admin/BankAccount/cashList]",
    "version": "1.0.0",
    "name": "cashList",
    "group": "BankAccount",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankAccount/cashList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_status",
            "description": "<p>出账状态（1待财务出账 2待财务复核 3待银行扣款 4出账已退回 5财务已出账）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type_text",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "account_status_text",
            "description": "<p>出账状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "money",
            "description": "<p>出账金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "guarantee_fee",
            "description": "<p>担保金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "receipt_bank_account",
            "description": "<p>收款户名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>申请时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransomer",
            "description": "<p>赎楼员</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "financing_manager",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BankAccount.php",
    "groupTitle": "BankAccount"
  },
  {
    "type": "get",
    "url": "admin/BankAccount/checkDetail",
    "title": "出账详情页[admin/BankAccount/checkDetail]",
    "version": "1.0.0",
    "name": "checkDetail",
    "group": "BankAccount",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankAccount/checkDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>出账id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"orderinfo\": {\n      \"estate_name\": [\n      \"金怡华庭1栋30F\"\n      ],\n      \"estate_owner\": \"金荣富、金毅杰\",\n      \"type_text\": \"交易担保\",\n      \"type\": \"JYDB\",\n      \"finance_sn\": \"100000075\",\n      \"order_sn\": \"JYDB2018070023\",\n      \"account_status_text\": \"财务已出账\",\n      \"ransom_status\": 3,\n      \"money\": \"1250000.00\",\n      \"default_interest\": \"0.00\",\n      \"self_financing\": \"0.00\",\n      \"loan_money\": \"1250000.00\",\n      \"short_loan_interest\": \"0.00\",\n      \"can_money\": 1250000,\n      \"out_money\": 168922,\n      \"use_money\": 1081078\n      },\n      \"debitbank\": {\n      \"out_bank_card\": \"1111111111\",\n      \"out_bank\": \"民生银行\",\n      \"out_bank_account\": \"中诚金服\",\n      \"outok_time\": \"2018-07-25 08:35\"\n      },\n      \"debit\": {\n      \"ransomer\": \"杜小彦\",\n      \"money\": \"10000.00\",\n      \"way_text\": \"现金\",\n      \"ransom_bank\": \"工商银行-深圳市上步支行\",\n      \"item_text\": \"银行罚息\",\n      \"create_time\": \"2018-07-20 20:54\",\n      \"is_prestore_text\": \"是\",\n      \"account_type_text\": \"卖方账户\",\n      \"receipt_bank\": \"(中国农业银行)\",\n      \"prestore_day\": null,\n      \"receipt_bank_account\": \"聂梦\",\n      \"receipt_bank_card\": \"6666666666\"\n      },\n      \"id\": 17\n      }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/BankAccount.php",
    "groupTitle": "BankAccount"
  },
  {
    "type": "get",
    "url": "admin/BankAccount/checkList",
    "title": "支票出账列表[admin/BankAccount/checkList]",
    "version": "1.0.0",
    "name": "checkList",
    "group": "BankAccount",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankAccount/checkList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_status",
            "description": "<p>出账状态（1待财务出账 2待财务复核 3待银行扣款 4出账已退回 5财务已出账）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type_text",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "account_status_text",
            "description": "<p>出账状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "money",
            "description": "<p>出账金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "guarantee_fee",
            "description": "<p>担保金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "cheque_num",
            "description": "<p>支票号码</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>申请时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransomer",
            "description": "<p>赎楼员</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "financing_manager",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BankAccount.php",
    "groupTitle": "BankAccount"
  },
  {
    "type": "post",
    "url": "admin/BankAccount/determineAccount",
    "title": "确认出账[admin/BankAccount/determineAccount]",
    "version": "1.0.0",
    "name": "determineAccount",
    "group": "BankAccount",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankAccount/determineAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>出账id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "out_bank_card",
            "description": "<p>出账卡号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "out_bank",
            "description": "<p>出账银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "out_bank_account",
            "description": "<p>出账账户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "out_bank_card_name",
            "description": "<p>出账账户别名</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BankAccount.php",
    "groupTitle": "BankAccount"
  },
  {
    "type": "get",
    "url": "admin/BankAccount/reviewAccount",
    "title": "审核[admin/BankAccount/reviewAccount]",
    "version": "1.0.0",
    "name": "reviewAccount",
    "group": "BankAccount",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankAccount/reviewAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>出账id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BankAccount.php",
    "groupTitle": "BankAccount"
  },
  {
    "type": "get",
    "url": "admin/BankAccount/trackacountList",
    "title": "出账跟踪[admin/BankAccount/trackacountList]",
    "version": "1.0.0",
    "name": "trackacountList",
    "group": "BankAccount",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankAccount/trackacountList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_prestore",
            "description": "<p>是否预存</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "money",
            "description": "<p>出账金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "way_text",
            "description": "<p>出账方式</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "prestore_day",
            "description": "<p>预存天数</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "outok_time",
            "description": "<p>出账时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransomer",
            "description": "<p>赎楼员</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "financing_manager",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BankAccount.php",
    "groupTitle": "BankAccount"
  },
  {
    "type": "post",
    "url": "admin/BankAccount/turndownAccount",
    "title": "驳回[admin/BankAccount/turndownAccount]",
    "version": "1.0.0",
    "name": "turndownAccount",
    "group": "BankAccount",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankAccount/turndownAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>出账id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "operate_reason",
            "description": "<p>驳回理由</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BankAccount.php",
    "groupTitle": "BankAccount"
  },
  {
    "type": "get",
    "url": "admin/BankCard/getAllbank",
    "title": "获取所有公司银行[admin/BankCard/getAllbank]",
    "version": "1.0.0",
    "name": "getAllbank",
    "group": "BankCard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BankCard/getAllbank"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>数据id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "name",
            "description": "<p>银行别名（下拉时展示）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank_account",
            "description": "<p>银行账户</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank_card",
            "description": "<p>银行卡号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BankCard.php",
    "groupTitle": "BankCard"
  },
  {
    "type": "post",
    "url": "admin/Bank/getBank",
    "title": "获取银行[admin/Bank/getBank]",
    "version": "1.0.0",
    "name": "getBank",
    "group": "Bank",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Bank/getBank"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>银行名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Bank.php",
    "groupTitle": "Bank"
  },
  {
    "type": "post",
    "url": "admin/Bank/getBranch",
    "title": "获取支行[admin/Bank/getBranch]",
    "version": "1.0.0",
    "name": "getBranch",
    "group": "Bank",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Bank/getBranch"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>银行表id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>支行名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Bank.php",
    "groupTitle": "Bank"
  },
  {
    "type": "post",
    "url": "admin/BuildingInfo/addBuilding",
    "title": "添加楼盘[admin/BuildingInfo/addBuilding]",
    "version": "1.0.0",
    "name": "addBuilding",
    "group": "BuildingInfo",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BuildingInfo/addBuilding"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "districtId",
            "description": "<p>地区表id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cityId",
            "description": "<p>市id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "buildingName",
            "description": "<p>楼盘名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "buildingAlias",
            "description": "<p>楼盘别名</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BuildingInfo.php",
    "groupTitle": "BuildingInfo"
  },
  {
    "type": "post",
    "url": "admin/BuildingInfo/getBuilding",
    "title": "获取楼盘名称[admin/BuildingInfo/getBuilding]",
    "version": "1.0.0",
    "name": "getBuilding",
    "group": "BuildingInfo",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BuildingInfo/getBuilding"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "districtId",
            "description": "<p>地区表id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "buildingName",
            "description": "<p>楼盘名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BuildingInfo.php",
    "groupTitle": "BuildingInfo"
  },
  {
    "type": "post",
    "url": "admin/BuildingUnit/addFloor",
    "title": "添加楼层[admin/BuildingUnit/addFloor]",
    "version": "1.0.0",
    "name": "addFloor",
    "group": "BuildingUnit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BuildingUnit/addFloor"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "optional": false,
            "field": "unitId",
            "description": "<p>栋阁id</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "buildingId",
            "description": "<p>楼盘id</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "floornum",
            "description": "<p>楼层</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "floortype",
            "description": "<p>楼层类型(up:地上楼层；down：地下楼层)</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BuildingUnit.php",
    "groupTitle": "BuildingUnit"
  },
  {
    "type": "post",
    "url": "admin/BuildingUnit/addHouse",
    "title": "添加房号[admin/BuildingUnit/addHouse]",
    "version": "1.0.0",
    "name": "addHouse",
    "group": "BuildingUnit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BuildingUnit/addHouse"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "optional": false,
            "field": "floorId",
            "description": "<p>楼层id</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "unitId",
            "description": "<p>栋阁id</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "buildingId",
            "description": "<p>楼盘id</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "housename",
            "description": "<p>房号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BuildingUnit.php",
    "groupTitle": "BuildingUnit"
  },
  {
    "type": "post",
    "url": "admin/BuildingUnit/addUnit",
    "title": "添加栋阁信息[admin/BuildingUnit/addUnit]",
    "version": "1.0.0",
    "name": "addUnit",
    "group": "BuildingUnit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BuildingUnit/addUnit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "optional": false,
            "field": "buildingId",
            "description": "<p>楼盘id</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "unitalias",
            "description": "<p>栋阁别名</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "unitname",
            "description": "<p>栋阁名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BuildingUnit.php",
    "groupTitle": "BuildingUnit"
  },
  {
    "type": "post",
    "url": "admin/BuildingUnit/getFloor",
    "title": "查询楼层信息[admin/BuildingUnit/getFloor]",
    "version": "1.0.0",
    "name": "getFloor",
    "group": "BuildingUnit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BuildingUnit/getFloor"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "optional": false,
            "field": "unitId",
            "description": "<p>栋阁id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BuildingUnit.php",
    "groupTitle": "BuildingUnit"
  },
  {
    "type": "post",
    "url": "admin/BuildingUnit/getHouse",
    "title": "查询房号信息[admin/BuildingUnit/getHouse]",
    "version": "1.0.0",
    "name": "getHouse",
    "group": "BuildingUnit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BuildingUnit/getHouse"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "optional": false,
            "field": "floorId",
            "description": "<p>楼层id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BuildingUnit.php",
    "groupTitle": "BuildingUnit"
  },
  {
    "type": "post",
    "url": "admin/BuildingUnit/getUnit",
    "title": "获取栋阁信息[admin/BuildingUnit/getUnit]",
    "version": "1.0.0",
    "name": "getUnit",
    "group": "BuildingUnit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/BuildingUnit/getUnit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "optional": false,
            "field": "buildingId",
            "description": "<p>楼盘id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/BuildingUnit.php",
    "groupTitle": "BuildingUnit"
  },
  {
    "type": "post",
    "url": "admin/CancellationsApply/addCancellRefund",
    "title": "撤单确定退费[admin/CancellationsApply/addCancellRefund]",
    "version": "1.0.0",
    "name": "addCancellRefund",
    "group": "CancellationsApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CancellationsApply/addCancellRefund"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "accountinfo",
            "description": "<p>账户信息(具体参数在下面)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>账户信息id</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "actual_payment",
            "description": "<p>实付金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "expense_taxation",
            "description": "<p>手续费</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CancellationsApply.php",
    "groupTitle": "CancellationsApply"
  },
  {
    "type": "post",
    "url": "admin/CancellationsApply/addCancellatApply",
    "title": "添加撤单申请[admin/CancellationsApply/addCancellatApply]",
    "version": "1.0.0",
    "name": "addCancellatApply",
    "group": "CancellationsApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CancellationsApply/addCancellatApply"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "process_type",
            "description": "<p>撤单类型 10(退担保费) 11(不退担保费) 12(保费调整)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>撤单原因(申请原因)</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件材料[1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "accountinfo",
            "description": "<p>支付账户信息(具体参数在下面)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_account",
            "description": "<p>银行户名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_card",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>开户银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>开户支行</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>退款金额</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CancellationsApply.php",
    "groupTitle": "CancellationsApply"
  },
  {
    "type": "post",
    "url": "admin/CancellationsApply/cancellApplyDetail",
    "title": "撤单审批(退费)详情[admin/CancellationsApply/cancellApplyDetail]",
    "version": "1.0.0",
    "name": "cancellApplyDetail",
    "group": "CancellationsApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CancellationsApply/cancellApplyDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"orderinfo\": {                        订单基本信息\n    \"order_sn\": \"JYDB2018070002\",       业务单号\n    \"finance_sn\": \"100000001\",          财务编号\n    \"money\": \"7800000.00\",              担保金额\n    \"financing_dept_id\": 24,\n    \"stage\": \"1013\",\n    \"name\": \"徐霞\",                     理财经理\n    \"sname\": \"担保业务01部\",            所属部门\n    \"financing_mobile\": \"13554767498\",       理财经理联系电话\n    \"stage_str\": \"待派单\",                   业务订单状态\n    \"dept_manager_name\": \"刘传英\",            部门经理\n    \"dept_mobile\": \"13570871906\",             部门经理联系电话\n    \"estateinfo\": \"竹盛花园（三期）A座12\"     房产名称\n    \"refund_type\": \"退担保费\"                 退费类型\n    }\n    \"costInfo\": {                         费用信息\n    \"guarantee_fee\": \"3000.00\",       担保费(保费调整)\n    \"ac_guarantee_fee\": \"3000.00\",    实收担保费(退担保费)\n    \"ac_fee\": \"100.00\",               手续费\n    \"ac_self_financing\": \"0.00\",      自筹金额\n    \"ac_short_loan_interest\": \"0.00\", 短贷利息\n    \"ac_default_interest\": \"0.00\",    罚息\n    \"ac_transfer_fee\": \"0.00\",        过账手续费\n    \"ac_deposit\": \"0.00\",             保证金\n    \"ac_other_money\": \"0.00\"          其他\n    \"sum_money\": 6000                 费用总计\n    }\n     \"applyforinfo\": {                     申请信息\n    \"id\": 10,                               其他业务表主键id\n    \"process_sn\": \"201809060023\",           流程编号\n    \"reason\": \"测试元原因\",                 撤单原因\n    \"attachment\": [                         附件材料\n    {\n    \"id\": 5,                        附件id\n    \"url\": \"/uploads/20180717/7a07d619c7f9ffb82527db5d386513e5.png\",   附件地址\n    \"name\": \"毕圆明.png\",                 附件名称\n    \"thum1\": \"uploads/thum/20180717/7a07d619c7f9ffb82527db5d386513e5.png\",  附件缩略图地址\n    \"ext\": \"png\"                          附件后缀\n    },\n    {\n    \"id\": 6,\n    \"url\": \"/uploads/20180717/36a1b7c84079d280c9f6058c98bf1659.jpg\",\n    \"name\": \"身份证复印件.jpg\",\n    \"thum1\": \"uploads/thum/20180717/36a1b7c84079d280c9f6058c98bf1659.jpg\",\n    \"ext\": \"jpg\"\n    }\n    ]\n    }\n    \"accountinfo\": [                支付账户(退费账户)(放款账户)信息\n    {\n    \"id\": 11,                     账户信息id\n    \"bank_account\": \"张三\",       银行户名\n    \"bank_card\": \"4521368\",       银行卡号\n    \"bank\": \"中国银行\",            开户银行\n    \"bank_branch\": \"车公庙支行\",   开户支行\n    \"money\": \"12458.00\",            退款金额\n    \"actual_payment\": null,      实退金额\n    \"expense_taxation\": null     手续费\n    }\n    ]\n    \"approval_info\": [                          审批记录\n    {\n    \"order_sn\": \"JYDB2018070002\",\n    \"create_time\": 1531800077,            审批记录的时间\n    \"process_name\": \"待业务报单\",          审批节点\n    \"auditor_name\": \"杨振亚1\",            操作人员名称\n    \"auditor_dept\": \"权证部\",            操作人员部门\n    \"status\": \"通过\",                   操作\n    \"content\": null                     审批意见\n    },\n    {\n    \"order_sn\": \"JYDB2018070002\",\n    \"create_time\": 1531800077,\n    \"process_name\": \"待部门经理审批\",\n    \"auditor_name\": \"甘雯\",\n    \"auditor_dept\": \"担保业务02部\",\n    \"status\": \"通过\",\n    \"content\": \"\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CancellationsApply.php",
    "groupTitle": "CancellationsApply"
  },
  {
    "type": "post",
    "url": "admin/CancellationsApply/cancellApprovalList",
    "title": "撤单申请审核列表[admin/CancellationsApply/cancellApprovalList]",
    "version": "1.0.0",
    "name": "cancellApprovalList",
    "group": "CancellationsApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CancellationsApply/cancellApprovalList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>业务类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"proc_id\": 5960,                  处理明细表主键id\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程编号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"type\": \"DQJK\",\n    \"estate_name\": null,           房产名称\n    \"create_time\": \"2018-08-15\",   申请时间\n    \"name\": \"管理员\"                  理财经理\n    \"process_type_text\": \"退担保费\",   退费类型\n    \"stage_text\": \"待核算专员审批\"        审批状态\n    \"type_text\": \"交易担保\"             业务类型\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CancellationsApply.php",
    "groupTitle": "CancellationsApply"
  },
  {
    "type": "post",
    "url": "admin/CancellationsApply/cancellManagementList",
    "title": "撤单申请退费管理列表[admin/CancellationsApply/cancellManagementList]",
    "version": "1.0.0",
    "name": "cancellManagementList",
    "group": "CancellationsApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CancellationsApply/cancellManagementList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>业务类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"proc_id\": 5960,                  处理明细表主键id\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程编号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"money\": \"2000\",                退款金额\n    \"estate_name\": null,           房产名称\n    \"create_time\": \"2018-08-15\",   申请时间\n    \"name\": \"管理员\"                  理财经理\n    \"process_type_text\": \"退担保费\",   退费类型\n    \"stage_text\": \"待核算专员审批\"        审批状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CancellationsApply.php",
    "groupTitle": "CancellationsApply"
  },
  {
    "type": "post",
    "url": "admin/CancellationsApply/cancellationsList",
    "title": "撤单申请列表[admin/CancellationsApply/cancellationsList]",
    "version": "1.0.0",
    "name": "cancellationsList",
    "group": "CancellationsApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CancellationsApply/cancellationsList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "refund_type",
            "description": "<p>退费类型 10(退担保费) 11(不退担保费) 12(保费调整)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程单号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"type\": \"DQJK\",                 业务类型\n    \"estate_name\": null,           房产名称\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:26:11\",   申请时间\n    \"name\": \"管理员\"                        理财经理\n    \"type_text\": \"短期借款\",                业务类型\n    \"stage_text\": \"待核算专员审批\"          审批状态\n     \"refund_type\": \"保费调整\"       退费类型\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CancellationsApply.php",
    "groupTitle": "CancellationsApply"
  },
  {
    "type": "post",
    "url": "admin/CancellationsApply/editCancell",
    "title": "编辑撤单申请[admin/CancellationsApply/editCancell]",
    "version": "1.0.0",
    "name": "editCancell",
    "group": "CancellationsApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CancellationsApply/editCancell"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>撤单原因(申请原因)</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件材料[1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "accountinfo",
            "description": "<p>支付账户信息(具体参数在下面)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_account",
            "description": "<p>银行户名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_card",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>开户银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>开户支行</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>退款金额</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CancellationsApply.php",
    "groupTitle": "CancellationsApply"
  },
  {
    "type": "post",
    "url": "admin/CancellationsApply/getCancellOrderInfo",
    "title": "撤单申请页面订单基本信息[admin/CancellationsApply/getCancellOrderInfo]",
    "version": "1.0.0",
    "name": "getCancellOrderInfo",
    "group": "CancellationsApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CancellationsApply/getCancellOrderInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "process_type",
            "description": "<p>撤单类型 10(退担保费) 11(不退担保费) 12(保费调整)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n            \"orderinfo\": {                        订单基本信息\n            \"order_sn\": \"JYDB2018070002\",       业务单号\n            \"finance_sn\": \"100000001\",          财务编号\n            \"money\": \"7800000.00\",              担保金额\n            \"financing_dept_id\": 24,\n            \"stage\": \"1013\",\n            \"name\": \"徐霞\",                     理财经理\n            \"sname\": \"担保业务01部\",            所属部门\n            \"financing_mobile\": \"13554767498\",       理财经理联系电话\n            \"stage_str\": \"待派单\",                   业务订单状态\n            \"dept_manager_name\": \"刘传英\",            部门经理\n            \"dept_mobile\": \"13570871906\",             部门经理联系电话\n            \"estateinfo\": \"竹盛花园（三期）A座12\"     房产名称\n            \"refund_type\": \"退担保费\"                 退费类型\n        }\n    \"costInfo\": {                         费用信息\n            \"guarantee_fee\": \"3000.00\",       担保费(保费调整)\n            \"ac_guarantee_fee\": \"3000.00\",    实收担保费(退担保费)\n            \"ac_fee\": \"100.00\",               手续费\n            \"ac_self_financing\": \"0.00\",      自筹金额\n            \"ac_short_loan_interest\": \"0.00\", 短贷利息\n            \"ac_default_interest\": \"0.00\",    罚息\n            \"ac_transfer_fee\": \"0.00\",        过账手续费\n            \"ac_deposit\": \"0.00\",             保证金\n            \"ac_other_money\": \"0.00\"          其他\n            \"sum_money\": 6000                 费用总计\n        }\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CancellationsApply.php",
    "groupTitle": "CancellationsApply"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/addChannelWater",
    "title": "添加加渠道放款入账[admin/CashBusiness/addChannelWater]",
    "version": "1.0.0",
    "name": "addChannelWater",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/addChannelWater"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>订单资金渠道表id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "loan_money",
            "description": "<p>渠道放款金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "lender_object",
            "description": "<p>资金渠道</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "bank_card_id",
            "description": "<p>收款账户id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "receivable_account",
            "description": "<p>收款账户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "into_money_time",
            "description": "<p>到账时间(2018-01-09)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/channelHasList",
    "title": "渠道放款已入账列表[admin/CashBusiness/channelHasList]",
    "version": "1.0.0",
    "name": "channelHasList",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/channelHasList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "loan_money_status",
            "description": "<p>银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/channelLendList",
    "title": "渠道放款待入账列表[admin/CashBusiness/channelLendList]",
    "version": "1.0.0",
    "name": "channelLendList",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/channelLendList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "loan_money_status",
            "description": "<p>银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"total\": 2,                            总条数\n      \"per_page\": 20,                        每页显示的条数\n      \"current_page\": 1,                     当前页\n      \"last_page\": 1,                        总页数\n      \"data\": [\n          {\n                \"order_sn\": \"GMDZ2018060007\",        业务单号\n                \"finance_sn\": \"100000333\",           财务序号\n                \"type\": \"更名赎楼垫资\",              订单类型\n                \"name\": \"刘颖6\",                     理财经理\n                \"estate_name\": \"名称1阁栋名称1010\",    房产名称\n                \"estate_owner\": \"张三\",                 业主姓名\n                \"id\",                                   订单资金渠道表id\n                \"fund_channel_name\": \"365\",             放款渠道\n                \"loan_money_time\": \"2018-02-01\",        入账时间\n                \"money\": \"1000.00\",                     申请金额\n                \"actual_account_money\": null,           入账金额\n                \"loan_money_status\": 2,                 入账状态 1待入账 2待复核 3已复核 4驳回待处理\n                \"trust_contract_num\": \"25421155\",       信托合同号(推单号)\n                \"type_text\": \"GMDZ\"                     订单类型(简称)\n                },\n                {\n                \"order_sn\": \"GMDZ2018060005\",\n                \"finance_sn\": \"100000321\",\n                \"type\": \"更名赎楼垫资\",\n                \"name\": \"刘颖6\",\n                \"estate_name\": \"名称1阁栋名称1010\",\n                \"estate_owner\": \"张三\",\n                \"fund_channel_name\": \"永安\",\n                \"loan_money_time\": null,\n                \"money\": \"1000.00\",\n                \"actual_account_money\": null,\n                \"loan_money_status\": 1,\n                \"type_text\": \"GMDZ\"\n                }\n      ]\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/channelReview",
    "title": "渠道放款入账复核[admin/CashBusiness/channelReview]",
    "version": "1.0.0",
    "name": "channelReview",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/channelReview"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>订单资金渠道表id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>按钮区分  1 确认复核 2驳回</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/channelsAuditList",
    "title": "渠道放款审核列表[admin/CashBusiness/channelsAuditList]",
    "version": "1.0.0",
    "name": "channelsAuditList",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/channelsAuditList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fund_channel_id",
            "description": "<p>资金渠道id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"total\": 2,\n      \"per_page\": 20,\n      \"current_page\": 1,\n      \"last_page\": 1,\n      \"data\": [\n          {\n          \"order_sn\": \"JYXJ2018060063\",       订单编号\n          \"finance_sn\": \"100000337\",          财务序号\n          \"type\": \"交易现金\",                 订单类型\n          \"name\": \"刘颖6\",                    理财经理\n          \"estate_name\": \"名称1阁栋名称1010\",        房产名称\n          \"estate_owner\": \"张三\",                    业主姓名\n          \"instruct_status\": 2,                      审核状态(固定为待财务审核)\n          \"id\":123,                           订单资金渠道表id\n          \"fund_channel_name\": \"永安\",               资金渠道\n          \"money\": \"1000.00\",                        垫资金额\n          \"type_text\": \"JYXJ\"                        订单类型(简写)\n          },\n          {\n          \"order_sn\": \"SQDZ2018060012\",\n          \"finance_sn\": \"100000328\",\n          \"type\": \"首期款垫资\",\n          \"name\": \"刘颖6\",\n          \"estate_name\": \"名称1阁栋名称1010\",\n          \"estate_owner\": \"张三\",\n          \"instruct_status\": 2,\n          \"fund_channel_name\": \"永安\",\n          \"money\": \"1000.00\",\n          \"type_text\": \"SQDZ\"\n          }\n      ]\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/channelsHasList",
    "title": "发送指令(渠道)已发送[admin/CashBusiness/channelsHasList]",
    "version": "1.0.0",
    "name": "channelsHasList",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/channelsHasList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fund_channel_id",
            "description": "<p>资金渠道id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "instruct_status",
            "description": "<p>指令状态（1待申请 2待财务审核 3待发送 4已经发送）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_lend",
            "description": "<p>是否放款（1是 2否）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间(2018-08-10)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间(2018-09-10)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/channelsInfo",
    "title": "垫资出账表[admin/CashBusiness/channelsInfo]",
    "version": "1.0.0",
    "name": "channelsInfo",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/channelsInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>资金渠道表id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n    \"basic_information\": {                      基本信息\n    \"order_sn\": \"JYDB2018050137123456\",    业务单号\n    \"type\": \"JYDB\",        业务类型\n    \"finance_sn\": \"100000048\",      财务序号\n    \"order_source\": 1,\n    \"source_info\": \"万科地产\",          订单来源\n    \"order_source_str\": \"合作中介\",     来源机构\n    \"financing_manager_name\": \"夏丽平\",    理财经理\n    \"dept_manager_name\": \"杜欣\",           部门经理\n    \"deptname\": \"总经办\"                   所属部门\n    },\n    \"estate_info\": [   房产信息\n    {\n    \"estate_name\": \"国际新城一栋\",                  房产名称\n    \"estate_region\": \"深圳市|罗湖区|桂园街道\",      所属城区\n    \"estate_area\": 70,                             房产面积\n    \"estate_certtype\": 1,                          产证类型\n    \"estate_certnum\": 11111,                       产证编码\n    \"house_type\": 1                                房产类型 1分户 2分栋\n    },\n    {\n    \"estate_name\": \"国际新城一栋\",\n    \"estate_district\": \"440303\",\n    \"estate_area\": 70,\n    \"estate_certtype\": 1,\n    \"estate_certnum\": 11111,\n    \"house_type\": 1\n    }\n    ],\n    \"seller_info\": [    买方信息(is_seller = 1 && is_comborrower = 0) 买方共同借款人(is_seller = 1 && is_comborrower = 1)\n    {               卖方信息(is_seller = 2 && is_comborrower = 0) 卖方共同借款人(is_seller = 2 && is_comborrower = 1)\n    \"is_seller\": 2,\n    \"is_comborrower\": 0,           共同借款人属性 0借款人 1共同借款人\n    \"cname\": \"张三\",                 卖方姓名\n    \"ctype\": 1,                      卖方类型 1个人 2企业\n    \"certtype\": 1,                   证件类型\n    \"certcode\": \"11111122322\",       证件号码\n    \"mobile\": \"18825454079\",         电话号码\n    \"is_guarantee\": 0                 担保申请人 1是 0否\n    \"is_seller_str\": \"买方\",\n    \"is_comborrower_str\": \"买方共同借款人\",     所属角色\n    \"ctype_str\": \"个人\",                      卖方类型\n    \"certtype_str\": \"身份证\"                    证件类型\n    },\n    {\n    \"cname\": \"张三\",\n    \"ctype\": 1,\n    \"certtype\": 1,\n    \"certcode\": \"11111122322\",\n    \"mobile\": \"18825454079\",\n    \"is_guarantee\": 0\n    }\n    ],\n    \"sqk_info\": {                               首期款信息\n        \"dp_strike_price\": \"5900000.00\",             成交价格\n        \"dp_earnest_money\": \"80000.00\",             定金金额\n        \"dp_supervise_guarantee\": null,            担保公司监管\n        \"dp_supervise_buyer\": null,                 买方本人监管\n        \"dp_supervise_bank\": \"建设银行\",           监管银行\n        \"dp_supervise_bank_branch\": null,\n        \"dp_supervise_date\": \"2018-04-24\",         监管日期\n        \"dp_buy_way\": \"按揭购房\",                  购房方式\n        \"dp_now_mortgage\": \"5.00\"                  现按揭成数\n        },\n    \"mortgage_info\": [                               现按揭信息\n            {\n            \"type\": \"NOW\",\n            \"mortgage_type\": 1,\n            \"money\": \"900000.00\",                     现按揭金额\n            \"organization_type\": \"1\",\n            \"organization\": \"建设银行-深圳振兴支行\",     现按揭机构\n            \"mortgage_type_str\": \"公积金贷款\",           现按揭类型\n            \"organization_type_str\": \"银行\"         现按揭机构类型\n            },\n            {\n            \"type\": \"NOW\",\n            \"mortgage_type\": 2,\n            \"money\": \"2050000.00\",\n            \"organization_type\": \"1\",\n            \"organization\": \"建设银行-深圳振兴支行\",\n            \"mortgage_type_str\": \"商业贷款\",\n            \"organization_type_str\": \"银行\"\n            }\n        ],\n    \"preliminary_question\": [    风控初审问题汇总\n    {\n    \"describe\": \"呵呵456\",     问题描述\n    \"status\": 0               是否解决  0未解决 1已经解决\n    },\n    {\n    \"describe\": \"呵呵帅那个帅789\",\n    \"status\": 0\n    }\n    ],\n    \"needing_attention\": [   风控提醒注意事项\n    {\n    \"process_name\": \"收到公司的\",    来源\n    \"item\": \"啥打法是否\"             注意事项\n    },\n    {\n    \"process_name\": \"测试\",\n    \"item\": \"测试注意事项\"\n    }\n    ],\n    \"reimbursement_info\": [   银行账户信息\n    {\n    \"bankaccount\": \"张三\",   银行户名\n    \"accounttype\": 1,        账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它 6公司个人账户\n    \"bankcard\": \"111111\",    银行卡号\n    \"openbank\": \"中国银行\"    开户银行\n    \"type\": 2,\n    \"verify_card_status\": 0,\n    \"type_text\": \"尾款卡\",                  账号用途\n    \"verify_card_status_text\": \"已完成\",    核卡状态\n    \"accounttype_str\": \"卖方\"               账号归属\n    },\n    {\n    \"bankaccount\": \"李四\",\n    \"accounttype\": 5,\n    \"bankcard\": \"111\",\n    \"openbank\": \"工商银行\"\n    }\n    ],\n\n    \"advancemoney_info\": [    垫资费计算\n    {\n    \"advance_money\": \"650000.00\",     垫资金额\n    \"advance_day\": 30,               垫资天数\n    \"advance_rate\": 0.5,             垫资费率\n    \"advance_fee\": \"97500.0\",        垫资费\n    \"remark\": null,                  备注\n    \"id\": 115\n    }\n    ],\n\n    \"cost_account\":{     预收费用信息 与 实际费用入账\n    \"ac_guarantee_fee\": \"1000.00\",   实收担保费\n    \"ac_fee\": \"-15.00\",              手续费\n    \"ac_self_financing\": \"30.00\",    自筹金额(实际费用入账)\n    \"short_loan_interest\": \"-12.30\",   短贷利息\n    \"return_money\": \"12.50\",           赎楼返还款\n    \"default_interest\": \"0.00\",        罚息\n    \"overdue_money\": \"0.00\",           逾期费\n    \"exhibition_fee\": \"1000.00\",      展期费\n    \"transfer_fee\": \"10000.00\",       过账手续费\n    \"other_money\": \"0.00\"             其他\n    \"notarization\": \"2018-07-26\",     公正日期\n    \"money\": \"46465.00\",              担保金额(额度类)  垫资金额(现金类)\n    \"project_money_date\": null,       预计用款日\n    \"guarantee_per\": 0.35,            担保成数(垫资成数)\n    \"guarantee_rate\": 4,              担保费率\n    \"guarantee_fee\": \"225826007.64\",  预收担保费\n    \"account_per\": 41986.52,          出账成数\n    \"fee\": \"456456.00\",               预收手续费\n    \"self_financing\": \"30.00\",        自筹金额(预收费用信息)\n    \"info_fee\": \"0.00\",               预计信息费\n    \"total_fee\": \"226282463.64\",      预收费用合计\n    \"return_money_mode\": null,\n    \"return_money_amount\": 1425,      回款金额\n    \"turn_into_date\": null,           存入日期\n    \"turn_back_date\": null,           转回日期\n    \"chuzhangsummoney\": 5645650191    预计出账总额\n    \"return_money_mode_str\": \"直接回款\"   回款方式\n    },\n    \"lend_books\": [    银行放款入账\n    {\n    \" loan_money\": \"56786.00\",             放款金额\n    \"lender_object\": \"中国银行\",           放款银行\n    \"receivable_account\": \"中国银行账户\",    收款账户\n    \"into_money_time\": \"2019-11-03\",        到账时间\n    \"remark\": \"法国红酒狂欢节\",             备注说明\n    \"operation_name\": \"杜欣\"                入账人员\n    },\n    {\n    \" loan_money\": \"123456.00\",\n    \"lender_object\": \"中国银行\",\n    \"receivable_account\": \"中国银行账户\",\n    \"into_money_time\": \"2019-11-02\",\n    \"remark\": \"啊是的范德萨\",\n    \"operation_name\": \"杜欣\"\n    }\n    ],\n    \"arrears_info\": [    欠款及预计出账金额\n    {\n    \"ransom_status_text\": \"已完成\",       赎楼状态\n    \"ransomer\": \"朱碧莲\",         赎楼员\n    \"organization\": \"银行\",      欠款机构名称\n    \"interest_balance\": \"111111.11\",    欠款金额\n    \"mortgage_type_name\": \"商业贷款\",   欠款类型\n    \"accumulation_fund\": \"2.00\"         预计出账金额\n    },\n    {\n    \"organization\": \"银行\",\n    \"interest_balance\": \"111111.11\",\n    \"mortgage_type_name\": \"公积金贷款\",\n    \"accumulation_fund\": \"2.00\"\n    }\n    ],\n    \"fund_channel\": [                 资金渠道信息\n    {\n    \"fund_channel_name\": \"华安\",      资金渠道\n    \"money\": \"2000000.00\",             申请金额\n    \"actual_account_money\": \"2000000.00\",    实际入账金额\n    \"is_loan_finish\": 1,\n    \"trust_contract_num\": \"25421155\",         信托合同号\n    \"loan_day\": 5                             借款天数\n    }\n    \"status_info\": {        各种需要用到的其他字段\n    \"guarantee_fee_status\": 2,     （担保费）收费状态 1未收齐 2已收齐\n    \"loan_money_status\": 1,         银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理\n    \"instruct_status\": 3,           主表指令状态（1待申请 2待发送 3已发送）\n    \"is_loan_finish\": 1,             银行放款是否完成 0未完成 1已完成\n    \"loan_money\": \"4200000.00\",      银行放款入账(实收金额总计)  资金渠道信息(渠道实际入账总计)\n    \"com_loan_money\": null,\n    \"guarantee_fee\": 14526          垫资费计算(垫资费总计)\n    \"is_comborrower_sell\": 1       是否卖方有共同借款人 0否 1是\n    \"chile_instruct_status\"  3     子单指令状态（资金渠道表指令状态）\n    \"is_dispatch\": 1,               是否派单 0未派单 1已派单 2退回\n    \"endowmentsum\": 120000          资金渠道信息(垫资总计)\n    }\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/channelsInstructionList",
    "title": "发送指令(渠道)待发送[admin/CashBusiness/channelsInstructionList]",
    "version": "1.0.0",
    "name": "channelsInstructionList",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/channelsInstructionList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fund_channel_id",
            "description": "<p>资金渠道id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "instruct_status",
            "description": "<p>指令状态（1待申请 2待财务审核 3待发送 4已经发送）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_lend",
            "description": "<p>是否放款（1是 2否）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"total\": 2,\n      \"per_page\": 20,\n      \"current_page\": 1,\n      \"last_page\": 1,\n      \"data\": [\n          {\n          \"order_sn\": \"JYXJ2018060063\",       订单编号\n          \"finance_sn\": \"100000337\",          财务序号\n          \"type\": \"交易现金\",                 订单类型\n          \"name\": \"刘颖6\",                    理财经理\n          \"estate_name\": \"名称1阁栋名称1010\",        房产名称\n          \"estate_owner\": \"张三\",                    业主姓名\n          \"instruct_status\": 1,                      指令状态（1待申请 2待财务审核 3待发送 4已经发送）\n          \"id\":123,                           订单资金渠道表id\n          \"is_loan_finish\": 0,                       是否放款  0否  1是\n          \"fund_channel_name\": \"永安\",               资金渠道\n          \"money\": \"1000.00\",                        垫资金额\n          \"instruct_sendtime\" : \"2018-10-10\"         指令发送时间\n          \"type_text\": \"JYXJ\"                        订单类型(简写)\n          },\n          {\n          \"order_sn\": \"SQDZ2018060012\",\n          \"finance_sn\": \"100000328\",\n          \"type\": \"首期款垫资\",\n          \"name\": \"刘颖6\",\n          \"estate_name\": \"名称1阁栋名称1010\",\n          \"estate_owner\": \"张三\",\n          \"instruct_status\": 0,\n          \"is_loan_finish\": 0,\n          \"fund_channel_name\": \"永安\",\n          \"money\": \"1000.00\",\n          \"type_text\": \"SQDZ\"\n          }\n      ]\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/channelsSend",
    "title": "指令发送(渠道)[admin/CashBusiness/channelsSend]",
    "version": "1.0.0",
    "name": "channelsSend",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/channelsSend"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>订单资金渠道表id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1申请发送 2确认发送 3撤回发送</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/channelsSubAudit",
    "title": "渠道放款审核提交审核[admin/CashBusiness/channelsSubAudit]",
    "version": "1.0.0",
    "name": "channelsSubAudit",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/channelsSubAudit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>订单资金渠道表id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1审核通过 2驳回</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "item",
            "description": "<p>驳回原因</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "get",
    "url": "admin/CashBusiness/exportHasList",
    "title": "渠道放款已入账导出[admin/CashBusiness/exportHasList]",
    "version": "1.0.0",
    "name": "exportHasList",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/exportHasList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/finanStateList",
    "title": "财务结单列表[admin/CashBusiness/finanStateList]",
    "version": "1.0.0",
    "name": "finanStateList",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/finanStateList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>结单状态 1026 待结单  1021 已结单</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"total\": 2,                            总条数\n      \"per_page\": 20,                        每页显示的条数\n      \"current_page\": 1,                     当前页\n      \"last_page\": 1,                        总页数\n      \"data\": [\n          {\n                \"order_sn\": \"GMDZ2018060007\",        业务单号\n                \"finance_sn\": \"100000333\",           财务序号\n                \"stage\": \"1021\",                     订单状态\n                \"statement_state\": \"已结单\"           结单状态\n                \"type\": \"GMDZ\",              订单类型(简称)\n                \"order_finish_achievement\": 8600,   结单业绩\n                \"hang_achievement\": 8600            挂账业绩\n                \"order_finish_date\": null,          结单日期\n                \"info_fee_date\": null,              信息费支付日期\n                \"return_money_finish_date\": null,   回款完成日期\n                \"remortgage_date\": null,            重新抵押日期\n                \"name\": \"刘颖6\",                     理财经理\n                \"deptname\": \"中诚金服\",              所属部门\n                \"estate_name\": \"名称1阁栋名称1010\",    房产名称\n                \"cname\": \"张三\",                       担保申请人\n                \"id\",                                   订单表id\n                \"money\": \"1.00\",                        担保金额\n                \"guarantee_fee\": \"20000000.02\",          预收担保费\n                \"info_fee\": \"1.00\",                      预收信息费\n                \"ac_guarantee_fee\": null,                实收担保费\n                \"ac_guarantee_fee_time\": null,           收费日期\n                \"type_text\": \"更名赎楼垫资\"                     订单类型\n                \"customer_sum\": [                        所有的担保申请人\n                        \"刘恺威\",\n                        \"杨幂\"\n                    ]\n                },\n                {\n                \"id\": 1,\n                \"order_sn\": \"JYDB2018050371\",\n                \"finance_sn\": \"100000147\",\n                \"stage\": \"1021\",\n                \"type\": \"交易担保\",\n                \"order_finish_achievement\": null,\n                \"order_finish_date\": null,\n                \"info_fee_date\": null,\n                \"return_money_finish_date\": null,\n                \"remortgage_date\": null,\n                \"name\": \"管理员\",\n                \"deptname\": \"中诚金服\",\n                \"estate_name\": \"名称1阁栋名称1010\",\n                \"cname\": \"张三\",\n                \"money\": \"1.00\",\n                \"guarantee_fee\": \"20000000.02\",\n                \"info_fee\": \"1.00\",\n                \"ac_guarantee_fee\": null,\n                \"ac_guarantee_fee_time\": null,\n                \"type_text\": \"JYDB\",\n                \"hang_achievement\": -1\n                }\n      ]\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/getFundChannel",
    "title": "所有的资金渠道信息[admin/CashBusiness/getFundChannel]",
    "version": "1.0.0",
    "name": "getFundChannel",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/getFundChannel"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n[\n            {\n            \"id\": 2,             渠道id\n            \"name\": \"华安\"       渠道名称\n            },\n            {\n            \"id\": 3,\n            \"name\": \"365\"\n            }\n        ]",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/getGuarantee",
    "title": "获取所有担保申请人[admin/CashBusiness/getGuarantee]",
    "version": "1.0.0",
    "name": "getGuarantee",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/getGuarantee"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n  {\n            \"code\": 1,\n            \"msg\": \"操作成功\",\n            \"data\": [\n                {\n                \"cname\": \"测试第二次\"     担保申请人名称\n                },\n                {\n                \"cname\": \"测试第一次\"\n                }\n            ]\n       }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/isQdFinish",
    "title": "渠道放款入账是否已收齐[admin/CashBusiness/isQdFinish]",
    "version": "1.0.0",
    "name": "isQdFinish",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/isQdFinish"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>订单资金渠道表id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>是否收齐 1已收齐 2未收齐</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/showChannelLendDetail",
    "title": "渠道放款入账详情[admin/CashBusiness/showChannelLendDetail]",
    "version": "1.0.0",
    "name": "showChannelLendDetail",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/showChannelLendDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>订单资金渠道表id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "fund_channel_name",
            "description": "<p>放款渠道名称(365,永安)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n      \"code\": 1,\n      \"msg\": \"操作成功\",\n       data\": {\n          \"orderinfo\": {\n                 \"order_sn\": \"GMDZ2018060007\",        订单编号\n                        \"type\": \"更名赎楼垫资\",               订单类型\n                        \"name\": \"刘颖6\",                       理财经理\n                        \"deptname\": \"运营支持部\",               所在部门\n                        \"finance_sn\": \"100000333\",              财务序号\n                        \"money\": \"1000.00\",                     垫资金额\n                        \"actual_account_money\": \"339.00\",       实收金额总计\n                        \"is_loan_finish\": 1,                    渠道放款是否完成 0未完成 1已完成\n                        \"loan_money_status\": 2,               入账状态 1待入账 2待复核 3已复核 4驳回待处理\n                        \"fund_channel_name\": \"365\",           资金渠道\n                        \"delivery_status\": 2,                 送审状态（-1不需要送审 0待送审 1待渠道审核 2审核通过）\n                        \"trust_contract_num\": null,           信托合同号\n                        \"push_order_money\": null,             推单金额\n                        \"loan_day\": null,                     借款天数\n                        \"type_text\": \"GMDZ\"                   订单类型(简称)\n            },\n        \"BankLendInfo\": [\n              {\n                        \"loan_money\": \"113.00\",              放款金额\n                        \"lender_object\": \"365\",              放款渠道\n                        \"receivable_account\": \"中国银行\",    收款账户\n                        \"into_money_time\": \"2018-10-11\",     到账时间\n                        \"remark\": \"测试\",                    备注说明\n                        \"operation_name\": \"杜欣\"             入账人员\n                        },\n                        {\n                        \"loan_money\": \"113.00\",\n                        \"lender_object\": \"365\",\n                        \"receivable_account\": \"中国银行\",\n                        \"into_money_time\": \"2018-10-11\",\n                        \"remark\": \"测试\",\n                        \"operation_name\": \"杜欣\"\n                        },\n           ]\n              \"payment\": [   收款账户\n                        {\n                        \"bank_card_id\": 12,\n                        \"bank\": \"广发银行\"\n                        },\n                        {\n                        \"bank_card_id\": 13,\n                        \"bank\": \"上海银行\"\n                        }\n                    ]\n              \"postinginfo\": [     过账账户信息\n                    {\n                    \"bankaccount\": \"张飞\",       银行户名\n                    \"accounttype\": 1,\n                    \"bankcard\": \"52265615465\",      银行卡号\n                    \"openbank\": \"农业银行\",         开户银行\n                    \"accounttype_str\": \"卖方\"      账户类型\n                    }\n                ]\n       }\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/CashBusiness/submitOrder",
    "title": "提交结单[admin/CashBusiness/submitOrder]",
    "version": "1.0.0",
    "name": "submitOrder",
    "group": "CashBusiness",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CashBusiness/submitOrder"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "button_type",
            "description": "<p>1确认结单  2撤回结单</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CashBusiness.php",
    "groupTitle": "CashBusiness"
  },
  {
    "type": "post",
    "url": "admin/Check/CheckDelete",
    "title": "支票删除 [admin/Check/CheckDelete]",
    "version": "1.0.0",
    "name": "CheckDelete",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/CheckDelete"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "checkid",
            "description": "<p>支票id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/CheckTransfer",
    "title": "支票转让[admin/Check/CheckTransfer]",
    "version": "1.0.0",
    "name": "CheckTransfer",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/CheckTransfer"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "checkid",
            "description": "<p>支票id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "personid",
            "description": "<p>转让人id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "get",
    "url": "admin/CheckFile/checkAgain",
    "title": "再次查档[admin/CheckFile/checkAgain]",
    "version": "1.0.0",
    "name": "checkAgain",
    "group": "CheckFile",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CheckFile/checkAgain"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>房产id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data",
            "description": "<p>查询状态（红本在收，抵押等）</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CheckFile.php",
    "groupTitle": "CheckFile"
  },
  {
    "type": "get",
    "url": "admin/CheckFile/checkRecords",
    "title": "房产查询操作记录[admin/CheckFile/checkRecords]",
    "version": "1.0.0",
    "name": "checkRecords",
    "group": "CheckFile",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CheckFile/checkRecords"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>房产id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>操作记录信息</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CheckFile.php",
    "groupTitle": "CheckFile"
  },
  {
    "type": "get",
    "url": "admin/CheckFile/getEstateinfo",
    "title": "获取订单房产信息[admin/CheckFile/getEstateinfo]",
    "version": "1.0.0",
    "name": "getEstateinfo",
    "group": "CheckFile",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CheckFile/getEstateinfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "base_data",
            "description": "<p>担保信息</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "counter_data",
            "description": "<p>反担保（为空时说明该订单不存在反担保房产）</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "review_data",
            "description": "<p>资产证明（为空时说明该订单不存在资产证明房产）</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CheckFile.php",
    "groupTitle": "CheckFile"
  },
  {
    "type": "post",
    "url": "admin/Check/addCheckStorage",
    "title": "支票入库[admin/Check/addCheckStorage]",
    "version": "1.0.0",
    "name": "addCheckStorage",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/addCheckStorage"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "starebanks",
            "description": "<p>起始票号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endbanks",
            "description": "<p>结束票号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bankname",
            "description": "<p>银行名称 中国银行，农业银行，工商银行，建设银行</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/checkCancel",
    "title": "支票核销 [admin/Check/checkCancel]",
    "version": "1.0.0",
    "name": "checkCancel",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/checkCancel"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "checkid",
            "description": "<p>支票id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/checkInvalid",
    "title": "支票作废[admin/Check/checkInvalid]",
    "version": "1.0.0",
    "name": "checkInvalid",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/checkInvalid"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "checkid",
            "description": "<p>支票id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/checkWithdraw",
    "title": "支票撤回[admin/Check/checkWithdraw]",
    "version": "1.0.0",
    "name": "checkWithdraw",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/checkWithdraw"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "checkid",
            "description": "<p>支票id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "withdraw_status",
            "description": "<p>撤回状态 1领取人员 2库存中 只有超管对作废待核销的支票撤回才传该字段</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "user_id",
            "description": "<p>当超管选择撤回状态为领取人员时才传该字段</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/getCheck",
    "title": "领取支票[admin/Check/getCheck]",
    "version": "1.0.0",
    "name": "getCheck",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/getCheck"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "checkid",
            "description": "<p>支票id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "personid",
            "description": "<p>领取人id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/modifyCheck",
    "title": "修改支票信息[admin/Check/modifyCheck]",
    "version": "1.0.0",
    "name": "modifyCheck",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/modifyCheck"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>支票id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cheque_num",
            "description": "<p>支票号码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bankname",
            "description": "<p>银行名称 中国银行，农业银行，工商银行，建设银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注说明</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/operationList",
    "title": "操作记录列表 [admin/Check/operationList]",
    "version": "1.0.0",
    "name": "operationList",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/operationList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "checkid",
            "description": "<p>支票id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n  \"total\": 5,\n  \"per_page\": 20,\n  \"current_page\": 1,\n  \"last_page\": 1,\n  \"data\": [\n      {\n      \"create_time\": \"2018-04-28 11:55:31\",\n      \"operate_name\": \"1胡歌\",\n      \"operate_deptname\": \"业务部A\",\n      \"remark\": \"使用核销\",\n      \"operate_det\": \"使用待核销成功，核销人:1胡歌\",\n      \"note\": \"\"\n      },\n      {\n      \"create_time\": \"2018-04-28 09:37:15\",\n      \"operate_name\": \"1胡歌\",\n      \"operate_deptname\": \"业务部A\",\n      \"remark\": \"作废\",\n      \"operate_det\": \"作废成功，作废操作人:张四\",\n      \"note\": null\n      },\n   ]\n }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "total",
            "description": "<p>总条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示的条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "current_page",
            "description": "<p>当前页</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "last_page",
            "description": "<p>总页数</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "operate_name",
            "description": "<p>操作人员</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "operate_deptname",
            "description": "<p>操作人员所在的部门</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>操作</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "operate_det",
            "description": "<p>操作详情</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "note",
            "description": "<p>备注信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/showCheckDetail",
    "title": "支票详情[admin/Check/showCheckDetail]",
    "version": "1.0.0",
    "name": "showCheckDetail",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/showCheckDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>支票id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "show_type",
            "description": "<p>点击编辑支票查询支票信息时传该参数，且值为1</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"id\": 1,\n      \"cheque_num\": \"qw123456\",\n      \"bankname\": \"中国银行\",\n      \"status\": \"库存中\",\n      \"money\": 0,\n      \"create_uid\": null,\n      \"create_time\": \"1970-01-01 17:30:54\",\n      \"owner\": null,\n      \"order_sn\": \"cvnvbmn68765\",\n      \"estate_json\": \"{\\\"1\\\": {\\\"id\\\": \\\"20\\\", \\\"name\\\": \\\"张三\\\"}, \\\"2\\\": {\\\"id\\\": \\\"25\\\", \\\"name\\\": \\\"李四\\\"}, \\\"3\\\": {\\\"id\\\": \\\"19\\\", \\\"name\\\": \\\"王五\\\"}}\",\n      \"descr\": [\n          {\n          \"note\": \"是的法规\"\n          },\n          {\n          \"note\": \"法规和豆腐干\"\n          },\n          {\n          \"note\": \"我是备注信息\"\n          },\n          {\n          \"note\": \"华盛顿和风格还是\"\n          }\n      ]\n  }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>支票id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "cheque_num",
            "description": "<p>支票号码</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankname",
            "description": "<p>银行名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>支票状态</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "money",
            "description": "<p>支票金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_name",
            "description": "<p>入库人员名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "owner",
            "description": "<p>领用人名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>入库时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_json",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "descr",
            "description": "<p>备注说明</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_administrator",
            "description": "<p>是否是管理员 0不是管理员 1是管理员</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/showCheckList",
    "title": "支票列表[admin/Check/showCheckList]",
    "version": "1.0.0",
    "name": "showCheckList",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/showCheckList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_people",
            "description": "<p>1 领取人 2 使用人</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "owner",
            "description": "<p>支票所有人id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subor",
            "description": "<p>1 含下属 0 不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "time_type",
            "description": "<p>1入库时间 2领取时间 3使用时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>支票状态-1删除 1库存中 2领取待使用 3转让待确认 4使用待核销 5作废待核销 6使用已核销 7作废已核销</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bankname",
            "description": "<p>中国银行 中国农业银行 中国工商银行 中国建设银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search_text",
            "description": "<p>支票号 订单号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n  \"total\": 8,\n  \"per_page\": 20,\n  \"current_page\": 1,\n  \"last_page\": 1,\n  \"data\": [\n      {\n      \"id\": 3,\n      \"order_sn\": null,\n      \"create_time\": \"1971-02-06 07:47:23\",\n      \"cheque_num\": \"zgs12345\",\n      \"bankname\": \"中国农业银行\",\n      \"status\": \"使用已核销\",\n      \"use_time\": null,\n      \"user\": null,\n      \"owner\": null,\n      \"owner_time\": 45454,\n      \"estate_json\": \"{\\\"1\\\": {\\\"id\\\": \\\"20\\\", \\\"name\\\": \\\"张三\\\"}, \\\"2\\\": {\\\"id\\\": \\\"25\\\", \\\"name\\\": \\\"李四\\\"}, \\\"3\\\": {\\\"id\\\": \\\"19\\\", \\\"name\\\": \\\"王五\\\"}}\"\n      },\n      {\n      \"id\": 5,\n      \"order_sn\": null,\n      \"create_time\": \"1970-01-01 08:37:02\",\n      \"cheque_num\": \"12345678\",\n      \"bankname\": \"中国银行\",\n      \"status\": \"作废待核销\",\n      \"use_time\": null,\n      \"user\": null,\n      \"owner\": null,\n      \"owner_time\": 4545,\n      \"estate_json\": \"{\\\"1\\\": {\\\"id\\\": \\\"20\\\", \\\"name\\\": \\\"张三\\\"}, \\\"2\\\": {\\\"id\\\": \\\"25\\\", \\\"name\\\": \\\"李四\\\"}, \\\"3\\\": {\\\"id\\\": \\\"19\\\", \\\"name\\\": \\\"王五\\\"}}\"\n      }\n  ]\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "total",
            "description": "<p>总条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示的条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "current_page",
            "description": "<p>当前页</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "last_page",
            "description": "<p>总页数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>支票表主键id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>入库时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "cheque_num",
            "description": "<p>支票号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankname",
            "description": "<p>银行名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>支票状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "use_time",
            "description": "<p>使用时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "user",
            "description": "<p>使用人</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "owner",
            "description": "<p>领取人</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "owner_time",
            "description": "<p>领取时间</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_administrator",
            "description": "<p>是否是管理员 0不是管理员 1是管理员</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/showUser",
    "title": "人员部门模糊搜索[admin/Check/showUser]",
    "version": "1.0.0",
    "name": "showUser",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/showUser"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "people",
            "description": "<p>0未选择 1领取人 2使用人</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search_text",
            "description": "<p>人员名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>用户id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>用户部门</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/transferDetermine",
    "title": "转让确定[admin/Check/transferDetermine]",
    "version": "1.0.0",
    "name": "transferDetermine",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/transferDetermine"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "checkid",
            "description": "<p>支票id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isagreed",
            "description": "<p>是否同意 1同意 2不同意</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "information",
            "description": "<p>备注信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/Check/verifyOperation",
    "title": "支票批量操作的验证[admin/Check/verifyOperation]",
    "version": "1.0.0",
    "name": "verifyOperation",
    "group": "Check",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Check/verifyOperation"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "status_arr",
            "description": "<p>支票的状态[1,2,3]</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Check.php",
    "groupTitle": "Check"
  },
  {
    "type": "post",
    "url": "admin/CostApply/addCostApply",
    "title": "添加费用申请[admin/CostApply/addCostApply]",
    "version": "1.0.0",
    "name": "addCostApply",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/addCostApply"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "process_type",
            "description": "<p>添加类型 1信息费支付(INFO_FEE) 2首期转账(SQ_TRANSFER) 3退保证金(DEPOSIT) 4现金按天退担保费(XJ_GUARANTEE_FEE) 5额度退担保费(ED_GUARANTEE_FEE) 6额度类订单放尾款申请  7现金类订单放尾款申请</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "loan_way",
            "description": "<p>放款方式 1转账</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "transfer_type",
            "description": "<p>到账类型 1实时 2普通</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "info_fee_rate",
            "description": "<p>信息费费率</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "info_fee",
            "description": "<p>信息费金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "collector",
            "description": "<p>信息费收取人</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "mobile",
            "description": "<p>联系电话</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>支付原因(申请原因)</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件材料[1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_type",
            "description": "<p>订单类型 1内单 2外单</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "return_money",
            "description": "<p>额度放尾款（退赎楼金额） 现金放尾款（退回款金额）</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "default_interest",
            "description": "<p>退罚息金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "short_loan",
            "description": "<p>退短贷金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "used_interest",
            "description": "<p>已用罚息金额</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "accountinfo",
            "description": "<p>支付账户信息(具体参数在下面)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_account",
            "description": "<p>银行户名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_card",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>开户银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>开户支行</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>信息费(支付金额) 首期款(转账金额) 保证金(应退金额) 按天退担保费(应退担保金额) 额度退担保费,额度类订单放尾款,现金类订单放尾款(退款金额)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_type",
            "description": "<p>账户类型 1业主 2客户 3收款确定书</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_source",
            "description": "<p>账户来源 1合同 2财务确认书 3其他</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_fee",
            "description": "<p>应退展期费(按天退担保费申请专有)</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/addImportantMatter",
    "title": "添加要事审批申请[admin/CostApply/addImportantMatter]",
    "version": "1.0.0",
    "name": "addImportantMatter",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/addImportantMatter"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>重要说明</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件材料[1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "next_user_id",
            "description": "<p>下一个审批人</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/addRenewal",
    "title": "添加展期申请[admin/CostApply/addRenewal]",
    "version": "1.0.0",
    "name": "addRenewal",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/addRenewal"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "return_money",
            "description": "<p>待回款金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_rate",
            "description": "<p>展期费率</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "exhibition_starttime",
            "description": "<p>展期开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "exhibition_endtime",
            "description": "<p>展期结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "exhibition_day",
            "description": "<p>展期天数</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_fee",
            "description": "<p>展期费用</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_guarantee_fee",
            "description": "<p>担保费抵扣金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_info_fee",
            "description": "<p>信息费抵扣金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "total_money",
            "description": "<p>应交金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>实交金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>申请原因</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件材料,附件id组成的数组[1,2,3]</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/addSubmission",
    "title": "提交信息费支付(确定退费)[admin/CostApply/addSubmission]",
    "version": "1.0.0",
    "name": "addSubmission",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/addSubmission"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "accountinfo",
            "description": "<p>账户信息(具体参数在下面)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>账户信息id</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "actual_payment",
            "description": "<p>实付金额(其他退费，撤单)  实退金额(放尾款申请)</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "expense_taxation",
            "description": "<p>扣税费用(其他退费)  过账手续费(放尾款申请) 手续费(撤单)</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/appFlowInfo",
    "title": "处理审批相关信息[admin/CostApply/appFlowInfo]",
    "version": "1.0.0",
    "name": "appFlowInfo",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/appFlowInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "proc_id",
            "description": "<p>处理明细表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n            \"proc_id\": \"5970\",                              处理明细表主键id\n            \"process_id\": 163,                              流程步骤表主键id\n            \"process_name\": \"待核算专员审批\",               节点名称(当前步骤名称,审批节点)\n            \"next_process_name\": \"待资金专员放款\",          下一个审批节点名称\n            \"preprocess\": [    退回节点下拉信息['id(int)'=>'退回节点id','entry_id（int）'=>'流程实例id','flow_id（int）'=>'工作流定义表id','process_id（int）'=>'流程步骤id','process_name（string）'=>'返回节点名称']\n                    {\n                    \"id\": 5965,\n                    \"entry_id\": 467,\n                    \"flow_id\": 17,\n                    \"process_id\": 162,\n                    \"process_name\": \"带业务报单\",\n                    \"create_time\": \"2018-08-17 10:21:41\"\n                    }\n                ]\n            }\n }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/balanceApprovalList",
    "title": "放尾款审批列表[admin/CostApply/balanceApprovalList]",
    "version": "1.0.0",
    "name": "balanceApprovalList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/balanceApprovalList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程单号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"type\": \"DQJK\",\n    \"estate_name\": null,           房产名称\n    \"estate_owner\": null,          业主姓名\n    \"money\": \"0.00\",               退款金额\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15\",   申请时间\n    \"name\": \"管理员\"                        理财经理\n    \"stage_text\": \"待部门经理审批\"           审批状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/balancemanagementList",
    "title": "放尾款管理列表[admin/CostApply/balancemanagementList]",
    "version": "1.0.0",
    "name": "balancemanagementList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/balancemanagementList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程单号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"type\": \"DQJK\",\n    \"estate_name\": null,           房产名称\n    \"estate_owner\": null,          业主姓名\n    \"money\": \"0.00\",               退款金额\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15\",   申请时间\n    \"name\": \"管理员\"                        理财经理\n    \"stage_text\": \"待部门经理审批\"           审批状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/communicaReply",
    "title": "沟通回复[admin/CostApply/communicaReply]",
    "version": "1.0.0",
    "name": "communicaReply",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/communicaReply"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "process_sn",
            "description": "<p>流程编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>回复内容</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/communicationReplyList",
    "title": "沟通回复列表[admin/CostApply/communicationReplyList]",
    "version": "1.0.0",
    "name": "communicationReplyList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/communicationReplyList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程编号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"estate_name\": null,           房产名称\n    \"stage\": \"302\",\n    \"create_time\": \"2018-08-24\",      申请时间\n    \"name\": \"刘志刚\",                理财经理\n    \"reply_state\": \"待回复\",         回复状态\n    \"application_type\": \"展期申请\",  订单类型\n    \"stage_text\": \"待部门经理审批\"   审批状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/costApplyDetail",
    "title": "信息费支付(首期款转账,退保证金,按天,额度退担保)详情[admin/CostApply/costApplyDetail]",
    "version": "1.0.0",
    "name": "costApplyDetail",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/costApplyDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n            \"orderinfo\": {                        订单基本信息\n                \"order_sn\": \"JYDB2018070002\",       业务单号\n                \"finance_sn\": \"100000001\",          财务编号\n                \"money\": \"7800000.00\",              担保金额\n                \"order_source\": 2,\n                \"source_info\": \"中原地产\",           来源机构\n                \"name\": \"梁小健\",                    理财经理\n                \"sname\": \"担保业务02部\",             所属部门\n                \"estateinfo\": \"绿海湾花园A座1单元1601,绿海湾花园A座1单元1601a\",    房产名称\n                \"order_source_str\": \"银行介绍\",                 业务来源\n                \"costomerinfo\": \"杨丽娟,梁玉生,孙士钧,刘佩铃\"       担保申请人\n                 \"paymatters\": \"首期款转账\",                        付款事项\n                 \"estateOwner\": \"杨丽娟,杨丽娟\",                    业主姓名\n                 \"associated\": \"TMXJ2018070003,JYXJ2018070005,JYXJ2018070006\"  关联订单\n                 \"ac_deposit\": \"1.00\",                      实收保证金\n                 \"ac_guarantee_fee\": \"62400.00\",            担保费(实收担保费)\n                \"ac_fee\": \"0.00\",                           手续费\n                \"ac_exhibition_fee\": \"0.00\",                展期费\n                \"ac_overdue_money\": \"0.00\",                 逾期金额\n                 \"guarantee_rate\": 0.8,                     担保费率\n                 \"is_show_communication\"             是否展示沟通的按钮  1需要显示  2不需要显示\n                 \"is_show_approval\"                   是否显示处理审批  1需要显示  2不需要要显示\n            }\n             \"applyforinfo\": {                     申请信息\n                \"id\": 10,                               其他业务表主键id\n                \"process_sn\": \"201808150008\",           流程编码\n                \"order_type\": null,                     订单类型 1内单 2外单\n                \"loan_way\": 1,                          放款方式 1转账\n                \"transfer_type\": 1,                     到账类型 1实时 2普通\n                \"info_fee_rate\": 0.55,                  信息费费率\n                \"info_fee\": \"10523.00\",                 信息费金额\n                \"collector\": \"张三\",                    信息费收取人\n                \"mobile\": \"18529113254\",                联系电话\n                \"reason\": \"测试元原因\",                 支付原因(申请原因)\n                \"return_money\": \"1125.00\",              待回款金额(展期申请)  退赎楼金额(额度放尾款)  退回款金额(现金放尾款)\n                \"total_money\": \"5000.00\",               应交金额\n                \"money\": \"4000.00\",                     现金按天退担保费、额度退担保费、额度放尾款（退款总计）、现金放尾款（退款总计）、展期实交金额\n                \"exhibition_rate\": 0.14,                展期费率/日\n                \"exhibition_starttime\": \"2018-08-08\",   展期开始时间\n                \"exhibition_endtime\": \"2018-08-09\",     展期结束时间\n                \"exhibition_day\": 2,                    展期天数\n                \"exhibition_fee\": \"20000.00\",           展期费用\n                \"exhibition_guarantee_fee\": \"2000.00\",  担保费抵扣金额\n                \"exhibition_info_fee\": \"3000.00\",       信息费抵扣金额\n                \"default_interest\": \"200.00\",           退罚息金额\n                \"short_loan\": null,                     退短贷金额\n                \"attachment\": [                         附件材料\n                        {\n                        \"id\": 5,                        附件id\n                        \"url\": \"/uploads/20180717/7a07d619c7f9ffb82527db5d386513e5.png\",   附件地址\n                        \"name\": \"毕圆明.png\",                 附件名称\n                        \"thum1\": \"uploads/thum/20180717/7a07d619c7f9ffb82527db5d386513e5.png\",  附件缩略图地址\n                        \"ext\": \"png\"                          附件后缀\n                        },\n                        {\n                        \"id\": 6,\n                        \"url\": \"/uploads/20180717/36a1b7c84079d280c9f6058c98bf1659.jpg\",\n                        \"name\": \"身份证复印件.jpg\",\n                        \"thum1\": \"uploads/thum/20180717/36a1b7c84079d280c9f6058c98bf1659.jpg\",\n                        \"ext\": \"jpg\"\n                        }\n                    ]\n            }\n             \"accountinfo\": [                支付账户(退费账户)(放款账户)信息\n                {\n                \"id\": 11,                     账户信息id\n                \"bank_account\": \"张三\",       银行户名\n                \"account_type\": 1,            账户类型 1业主 2客户 3收款确定书\n                \"account_source\": 1,          账户来源 1合同 2财务确认书 3其他\n                \"bank_card\": \"4521368\",       银行卡号\n                \"bank\": \"中国银行\",            开户银行\n                \"bank_branch\": \"车公庙支行\",   开户支行\n                \"money\": \"12458.00\",           支付金额(信息费) 转账金额(首期款转账) 应退金额(退保证金) 应退担保金额(现金按天退) 退款金额(额度退担保费)\n                \"exhibition_fee\": \"12345.00\",  应退展期费(现金按天退专有)\n                \"actual_payment\": null,      实付金额(信息费) 实转金额(首期款转账) 实退金额(退保证金，现金按天退，额度退担保费)\n                \"expense_taxation\": null     扣税费用(信息费)  手续费(首期款转账,退保证金，现金按天退，额度退担保费)\n                },\n                {\n                \"bank_account\": \"李四\",\n                \"account_type\": 2,\n                \"account_source\": 3,\n                \"bank_card\": \"123445\",\n                \"bank\": \"中国农业银行\",\n                \"bank_branch\": \"车公庙支行\",\n                \"money\": \"1456.00\",\n                \"exhibition_fee\": \"23456.00\",\n                \"actual_payment\": null,\n                \"expense_taxation\": null\n                }\n            ]\n         \"dpInfo\": {                               首期款信息\n            \"dp_strike_price\": \"5900000.00\",             成交价格\n            \"dp_earnest_money\": \"80000.00\",             定金金额\n            \"dp_money\": null,                          首期款金额\n            \"dp_supervise_bank\": \"建设银行\",           资金监管银行\n            \"dp_supervise_bank_branch\": null,          资金监管支行\n            \"dp_supervise_date\": \"2018-04-24\",         监管日期\n            \"dp_buy_way\": \"按揭购房\",                  购房方式  1全款购房2按揭购房\n            \"dp_now_mortgage\": \"5.00\"                  现按揭成数\n            }\n         \"mortgage_info\": [                               现按揭信息\n                {\n                \"type\": \"NOW\",\n                \"mortgage_type\": 1,\n                \"money\": \"900000.00\",                     现按揭金额\n                \"organization_type\": \"1\",\n                \"organization\": \"建设银行-深圳振兴支行\",     现按揭机构\n                \"mortgage_type_str\": \"公积金贷款\",           现按揭类型\n                \"organization_type_str\": \"银行\"         现按揭机构类型\n                },\n                {\n                \"type\": \"NOW\",\n                \"mortgage_type\": 2,\n                \"money\": \"2050000.00\",\n                \"organization_type\": \"1\",\n                \"organization\": \"建设银行-深圳振兴支行\",\n                \"mortgage_type_str\": \"商业贷款\",\n                \"organization_type_str\": \"银行\"\n                }\n            ]\n         \"estate_info\": [   房产信息\n                {\n                \"estate_name\": \"国际新城一栋\",                  房产名称\n                \"estate_region\": \"深圳市|罗湖区|桂园街道\",      所属城区\n                \"estate_area\": 70,                             房产面积\n                \"estate_owner\": \"杨丽娟\",                       产权人\n                \"estate_certtype\": 1,\n                \"estate_certtype_str:\"房产证\"                  产证类型\n                \"estate_certnum\": 11111,                       产证编码\n                \"house_type\": 1                                房产类型 1分户 2分栋\n                },\n                {\n                \"estate_name\": \"国际新城一栋\",\n                \"estate_district\": \"440303\",\n                \"estate_area\": 70,\n                \"estate_certtype\": 1,\n                \"estate_certnum\": 11111,\n                \"house_type\": 1\n                }\n            ],\n         \"advance_info\": [              担保费信息\n                {\n                \"advance_money\": \"12000000.00\",  垫资金额\n                \"advance_day\": 5,                垫资天数\n                \"advance_rate\": 0.06,            垫资费率\n                \"advance_fee\": \"36000.0\",        垫资费\n                \"remark\": \"\",                    备注说明\n                \"id\": 3\n                }\n            ]\n         \"approval_info\": [                          审批记录\n                {\n                \"order_sn\": \"JYDB2018070002\",\n                \"create_time\": 1531800077,            审批记录的时间\n                \"process_name\": \"待业务报单\",          审批节点\n                \"auditor_name\": \"杨振亚1\",            操作人员名称\n                \"auditor_dept\": \"权证部\",            操作人员部门\n                \"status\": \"通过\",                   操作\n                \"content\": null                     审批意见\n                },\n                {\n                \"order_sn\": \"JYDB2018070002\",\n                \"create_time\": 1531800077,\n                \"process_name\": \"待部门经理审批\",\n                \"auditor_name\": \"甘雯\",\n                \"auditor_dept\": \"担保业务02部\",\n                \"status\": \"通过\",\n                \"content\": \"\"\n                }\n            ]\n        \"outbackinfo\": {                       出账回款信息\n            \"out_account_total\": \"1500000.00\",    垫资金额\n            \"account_com_total\": 1500000,         垫资出账金额\n            \"account_cus_total\": 600000,\n            \"out_time\": \"2018-08-23\",           垫资出账时间\n            \"expectday\": \"2018-08-28\",          合同回款日\n            \"readyin_money\": 0,                 已回款金额\n            \"unreadyin_money\": 1500000,         待回款金额\n            \"return_time\": null                 最近回款时间\n            }\n        \"cost_info\": {                         费用信息\n            \"sumexhibition_day\": \"17\",           已展期总天数\n            \"sumexhibition_fee\": \"1010.00\",      已收展期费\n            \"sumperiods\": 2,                     已展期期数\n            \"guarantee_fee\": \"3000.00\",          预收担保费\n            \"info_fee\": \"0.00\",                  预计信息费\n            \"ac_guarantee_fee\": \"4534.00\",      实收担保费\n            \"ac_overdue_money\": \"453.00\",        实收逾期费\n            \"exhibition_rate\": 0.12,              展期费率(展期申请信息)\n            \"yuqimoney\": \"213.00\",              应收逾期费\n            \"yuqiday\": 2                         逾期天数\n            }\n    \"cate_info\": [                           沟通记录\n        {\n        \"id\": 3,\n        \"initiate_time\": \"2018-08-25 15:45\",      时间\n        \"node\": \"待部门经理审批\",                 沟通发起节点\n        \"initiator\": \"刘传英\",                   操作人员\n        \"content\": \"测试测试测试\",               内容\n        \"communicationtype\": \"沟通：张三,李四\"    沟通类型\n        },\n        {\n        \"initiate_time\": \"2018-08-25 15:45\",     时间\n        \"node\": \"沟通回复\",                      沟通发起节点\n        \"communicationtype\": \"回复:刘传英\",       操作人员\n        \"content\": [                             内容\n            {\n            \"user_name\": \"张三\",\n            \"content\": \"从随时随地\",\n            \"reply_time\": \"2018-08-25 15:33\"\n            },\n            {\n            \"user_name\": \"李四\",\n            \"content\": \"水电费水电费是\",\n            \"reply_time\": \"2018-08-25 15:33\"\n            }\n        ],\n        \"initiator\": \"张三,李四\"            操作人员\n        },\n    ]\n    \"linesInfo\": {               额度(现金)赎楼信息\n    \"create_time\": 1534935737,       银行放款时间(额度放尾款)  回款时间(现金放尾款)\n    \"loan_money\": \"2836000.00\",      银行放款金额(额度放尾款)  实际回款金额(现金放尾款)\n    \"foreclosure\": 0,                赎楼金额\n    \"ac_return_money\": \"0.00\",       赎楼返还款\n    \"sum_return_money\": null,        已退赎楼金额\n    \"back_floor\": 2836000            可退赎楼金额\n    },\n    \"canBackInfo\": {           罚息信息\n    \"ac_default_interest\": \"0.00\",    实收罚息金额\n    \"used_interest\": 0,                   已用罚息金额\n    \"sum_default_interest\": null,     已退罚息金额\n    \"can_back_money\": 0               可退罚息金额\n    },\n    \"shortLoan\": {            短贷利息信息\n    \"ac_short_loan_interest\": \"0.00\",      实收短贷利息\n    \"used_short_loan\" :   \"0\"             已用短贷利息\n    \"sum_short_loan\": null,                已退短贷利息金额\n    \"can_back_short_loan\": 0               可退短贷利息金额\n    }\n        }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/costApprovalDetail",
    "title": "费用申请审批详情[admin/CostApply/costApprovalDetail]",
    "version": "1.0.0",
    "name": "costApprovalDetail",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/costApprovalDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"orderinfo\": {                    订单基本信息\n    \"order_sn\": \"JYDB2018070002\",       业务单号\n    \"finance_sn\": \"100000001\",          财务编号\n    \"money\": \"7800000.00\",              担保金额\n    \"order_source\": 2,\n    \"source_info\": \"中原地产\",           来源机构\n    \"name\": \"梁小健\",                    理财经理\n    \"sname\": \"担保业务02部\",             所属部门\n    \"estateinfo\": \"绿海湾花园A座1单元1601,绿海湾花园A座1单元1601a\",    房产名称\n    \"order_source_str\": \"银行介绍\",                 业务来源\n    \"costomerinfo\": \"杨丽娟,梁玉生,孙士钧,刘佩铃\"       担保申请人\n    \"paymatters\": \"首期款转账\",                        付款事项\n    \"estateOwner\": \"杨丽娟,杨丽娟\",                    业主姓名\n    \"associated\": \"TMXJ2018070003,JYXJ2018070005,JYXJ2018070006\"  关联订单\n    \"ac_deposit\": \"1.00\",                      实收保证金\n    \"ac_guarantee_fee\": \"62400.00\",            担保费(实收担保费)\n    \"ac_fee\": \"0.00\",                           手续费\n    \"ac_exhibition_fee\": \"0.00\",                展期费\n    \"ac_overdue_money\": \"0.00\",                 逾期金额\n    \"guarantee_rate\": 0.8,                     担保费率\n    }\n    \"applyforinfo\": {                   申请信息\n    \"id\": 10,                               其他业务表主键id\n    \"process_sn\": \"201808150008\",           流程编码\n    \"order_type\": null,                     订单类型 1内单 2外单\n    \"loan_way\": 1,                          放款方式 1转账\n    \"transfer_type\": 1,                     到账类型 1实时 2普通\n    \"info_fee_rate\": 0.55,                  信息费费率\n    \"info_fee\": \"10523.00\",                 信息费金额\n    \"collector\": \"张三\",                    信息费收取人\n    \"mobile\": \"18529113254\",                联系电话\n    \"reason\": \"测试元原因\",                 支付原因(申请原因)\n    \"attachment\": [                         附件材料\n    {\n    \"id\": 5,                        附件id\n    \"url\": \"/uploads/20180717/7a07d619c7f9ffb82527db5d386513e5.png\",   附件地址\n    \"name\": \"毕圆明.png\",                 附件名称\n    \"thum1\": \"uploads/thum/20180717/7a07d619c7f9ffb82527db5d386513e5.png\",  附件缩略图地址\n    \"ext\": \"png\"                          附件后缀\n    },\n    {\n    \"id\": 6,\n    \"url\": \"/uploads/20180717/36a1b7c84079d280c9f6058c98bf1659.jpg\",\n    \"name\": \"身份证复印件.jpg\",\n    \"thum1\": \"uploads/thum/20180717/36a1b7c84079d280c9f6058c98bf1659.jpg\",\n    \"ext\": \"jpg\"\n    }\n    ]\n    }\n    \"accountinfo\": [               支付账户(退费账户)信息\n    {\n    \"id\": 11,                     账户信息id\n    \"bank_account\": \"张三\",       银行户名\n    \"account_type\": 1,            账户类型 1业主 2客户 3收款确定书\n    \"account_source\": 1,          账户来源 1合同 2财务确认书 3其他\n    \"bank_card\": \"4521368\",       银行卡号\n    \"bank\": \"中国银行\",            开户银行\n    \"bank_branch\": \"车公庙支行\",   开户支行\n    \"money\": \"12458.00\",           支付金额(信息费) 转账金额(首期款转账) 应退金额(退保证金) 应退担保金额(现金按天退) 退款金额(额度退担保费)\n    \"exhibition_fee\": \"12345.00\",  应退展期费(现金按天退专有)\n    \"actual_payment\": null,      实付金额(信息费) 实转金额(首期款转账) 实退金额(退保证金，现金按天退，额度退担保费)\n    \"expense_taxation\": null     扣税费用(信息费)  手续费(首期款转账,退保证金，现金按天退，额度退担保费)\n    },\n    {\n    \"bank_account\": \"李四\",\n    \"account_type\": 2,\n    \"account_source\": 3,\n    \"bank_card\": \"123445\",\n    \"bank\": \"中国农业银行\",\n    \"bank_branch\": \"车公庙支行\",\n    \"money\": \"1456.00\",\n    \"exhibition_fee\": \"23456.00\",\n    \"actual_payment\": null,\n    \"expense_taxation\": null\n    }\n    ]\n\n    \"approval_info\": [   审批记录\n            {\n            \"order_sn\": \"JYDB2018070002\",\n            \"create_time\": 1531800077,            审批记录的时间\n            \"process_name\": \"待业务报单\",          审批节点\n            \"auditor_name\": \"杨振亚1\",            操作人员名称\n            \"auditor_dept\": \"权证部\",            操作人员部门\n            \"status\": \"通过\",                   操作\n            \"content\": null                     审批意见\n            },\n            {\n            \"order_sn\": \"JYDB2018070002\",\n            \"create_time\": 1531800077,\n            \"process_name\": \"待部门经理审批\",\n            \"auditor_name\": \"甘雯\",\n            \"auditor_dept\": \"担保业务02部\",\n            \"status\": \"通过\",\n            \"content\": \"\"\n            }\n        ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/costAuditList",
    "title": "信息费审核列表[admin/CostApply/costAuditList]",
    "version": "1.0.0",
    "name": "costAuditList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/costAuditList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"proc_id\": 5960,                  处理明细表主键id\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程单号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"type\": \"DQJK\",                 业务类型\n    \"estate_name\": null,           房产名称\n    \"estate_owner\": null,          业主姓名\n    \"money\": \"0.00\",               支付金额\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15\",   申请时间\n    \"name\": \"管理员\"                        申请人\n    \"type_text\": \"短期借款\",                业务类型\n    \"stage_text\": \"待核算专员审批\"          审批状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/editCostApply",
    "title": "编辑费用申请[admin/CostApply/editCostApply]",
    "version": "1.0.0",
    "name": "editCostApply",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/editCostApply"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "loan_way",
            "description": "<p>放款方式 1转账</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "transfer_type",
            "description": "<p>到账类型 1实时 2普通</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "info_fee_rate",
            "description": "<p>信息费费率</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "info_fee",
            "description": "<p>信息费金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "collector",
            "description": "<p>信息费收取人</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "mobile",
            "description": "<p>联系电话</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>支付原因(申请原因)</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件材料[1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_type",
            "description": "<p>订单类型 1内单 2外单</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "accountinfo",
            "description": "<p>支付账户信息(具体参数在下面)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_account",
            "description": "<p>银行户名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_card",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>开户银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>开户支行</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>信息费(支付金额) 首期款(转账金额) 保证金(应退金额) 按天退担保费(应退担保金额) 额度退担保费(退款金额)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_type",
            "description": "<p>账户类型 1业主 2客户 3收款确定书</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_source",
            "description": "<p>账户来源 1合同 2财务确认书 3其他</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_fee",
            "description": "<p>应退展期费(按天退担保费申请专有)</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/editImportantMatter",
    "title": "要事审批编辑页面[admin/CostApply/editImportantMatter]",
    "version": "1.0.0",
    "name": "editImportantMatter",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/editImportantMatter"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "process_sn",
            "description": "<p>流程编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>联系电话</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>所属部门</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>重要说明</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件材料</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/editRenewal",
    "title": "编辑展期申请[admin/CostApply/editRenewal]",
    "version": "1.0.0",
    "name": "editRenewal",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/editRenewal"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "return_money",
            "description": "<p>待回款金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_rate",
            "description": "<p>展期费率</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "exhibition_starttime",
            "description": "<p>展期开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "exhibition_endtime",
            "description": "<p>展期结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "exhibition_day",
            "description": "<p>展期天数</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_fee",
            "description": "<p>展期费用</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_guarantee_fee",
            "description": "<p>担保费抵扣金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_info_fee",
            "description": "<p>信息费抵扣金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "total_money",
            "description": "<p>应交金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>实交金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>申请原因</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件材料,附件id组成的数组[1,2,3]</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/exitImportantMatter",
    "title": "修改要事审批申请[admin/CostApply/exitImportantMatter]",
    "version": "1.0.0",
    "name": "exitImportantMatter",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/exitImportantMatter"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>要事审批id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>重要说明</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件材料[1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "next_user_id",
            "description": "<p>下一个审批人</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/getAccountDetail",
    "title": "信息费支付(确定退费)[admin/CostApply/getAccountDetail]",
    "version": "1.0.0",
    "name": "getAccountDetail",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/getAccountDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"accountinfo\": [               账户信息\n    {\n    \"id\": 11,                     账户信息id\n    \"bank_account\": \"张三\",       银行户名\n    \"account_type\": 1,            账户类型 1业主 2客户 3收款确定书\n    \"account_source\": 1,          账户来源 1合同 2财务确认书 3其他\n    \"bank_card\": \"4521368\",       银行卡号\n    \"bank\": \"中国银行\",            开户银行\n    \"bank_branch\": \"车公庙支行\",   开户支行\n    \"money\": \"12458.00\",           支付金额(信息费，撤单) 转账金额(首期款转账) 应退金额(退保证金) 应退担保金额(现金按天退) 退款金额(额度退担保费 放尾款申请)\n    \"exhibition_fee\": \"12345.00\",  应退展期费(现金按天退专有)\n    \"actual_payment\": null,      实付金额(信息费，撤单) 实转金额(首期款转账) 实退金额(退保证金，现金按天退，额度退担保费) 实退金额(放尾款申请)\n    \"expense_taxation\": null     扣税费用(信息费)  手续费(首期款转账,退保证金，现金按天退，额度退担保费,撤单)  过账手续费(放尾款申请)\n    },\n    {\n    \"bank_account\": \"李四\",\n    \"account_type\": 2,\n    \"account_source\": 3,\n    \"bank_card\": \"123445\",\n    \"bank\": \"中国农业银行\",\n    \"bank_branch\": \"车公庙支行\",\n    \"money\": \"1456.00\",\n    \"exhibition_fee\": \"23456.00\",\n    \"actual_payment\": null,\n    \"expense_taxation\": null\n    }\n    ]\n\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/getOrderInfo",
    "title": "添加费用申请页面订单基本信息[admin/CostApply/getOrderInfo]",
    "version": "1.0.0",
    "name": "getOrderInfo",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/getOrderInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "process_type",
            "description": "<p>添加类型 1信息费支付 2首期转账 3退保证金 4现金按天退担保费 5额度退担保费 6额度类订单放尾款申请 7现金类订单放尾款申请 8展期申请</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n        \"orderinfo\": {                        订单基本信息\n        \"order_sn\": \"JYDB2018070002\",       业务单号\n        \"finance_sn\": \"100000001\",          财务编号\n        \"money\": \"7800000.00\",              担保金额\n        \"order_source\": 2,\n        \"source_info\": \"中原地产\",           来源机构\n        \"name\": \"梁小健\",                    理财经理\n        \"sname\": \"担保业务02部\",             所属部门\n        \"info_fee\": \"21504.00\",             信息费金额(预计信息费)\n        \"estateinfo\": \"绿海湾花园A座1单元1601,绿海湾花园A座1单元1601a\",    房产名称\n        \"order_source_str\": \"银行介绍\",                 业务来源\n        \"costomerinfo\": \"杨丽娟,梁玉生,孙士钧,刘佩铃\"       担保申请人\n        \"paymatters\": \"首期款转账\",                        付款事项\n        \"estateOwner\": \"杨丽娟,杨丽娟\",                    业主姓名\n        \"associated\": \"TMXJ2018070003,JYXJ2018070005,JYXJ2018070006\"  关联订单\n        \"ac_deposit\": \"1.00\",                      实收保证金\n        \"ac_guarantee_fee\": \"62400.00\",            担保费(实收担保费)\n        \"dept_manager_name\": \"刘传英\",             部门经理\n        \"guarantee_fee\": \"45000.00\",               预收担保费\n        \"ac_fee\": \"0.00\",                           手续费\n        \"ac_exhibition_fee\": \"0.00\",                展期费\n        \"ac_overdue_money\": \"0.00\",                 逾期金额\n        \"guarantee_rate\": 0.8,                     担保费率\n         \"seller\": \"张念友\",                       卖方姓名\n        \"buyer\": \"敬若冰\"                          买方姓名\n        }\n    \"dpInfo\": {                               首期款信息\n    \"dp_strike_price\": \"5900000.00\",             成交价格\n    \"dp_earnest_money\": \"80000.00\",             定金金额\n    \"dp_money\": null,                          首期款金额\n    \"dp_supervise_bank\": \"建设银行\",           资金监管银行\n    \"dp_supervise_bank_branch\": null,          资金监管支行\n    \"dp_supervise_date\": \"2018-04-24\",         监管日期\n    \"dp_buy_way\": \"按揭购房\",                  购房方式  1全款购房2按揭购房\n    \"dp_now_mortgage\": \"5.00\"                  现按揭成数\n    }\n    \"mortgage_info\": [                               现按揭信息\n    {\n    \"type\": \"NOW\",\n    \"mortgage_type\": 1,\n    \"money\": \"900000.00\",                     现按揭金额\n    \"organization_type\": \"1\",\n    \"organization\": \"建设银行-深圳振兴支行\",     现按揭机构\n    \"mortgage_type_str\": \"公积金贷款\",           现按揭类型\n    \"organization_type_str\": \"银行\"         现按揭机构类型\n    },\n    {\n    \"type\": \"NOW\",\n    \"mortgage_type\": 2,\n    \"money\": \"2050000.00\",\n    \"organization_type\": \"1\",\n    \"organization\": \"建设银行-深圳振兴支行\",\n    \"mortgage_type_str\": \"商业贷款\",\n    \"organization_type_str\": \"银行\"\n    }\n    ]\n    \"estate_info\": [   房产信息\n    {\n    \"estate_name\": \"国际新城一栋\",                  房产名称\n    \"estate_region\": \"深圳市|罗湖区|桂园街道\",      所属城区\n    \"estate_area\": 70,                             房产面积\n    \"estate_owner\": \"杨丽娟\",                       产权人\n    \"estate_certtype\": 1,                          产证类型\n    \"estate_certnum\": 11111,                       产证编码\n    \"house_type\": 1                                房产类型 1分户 2分栋\n    },\n    {\n    \"estate_name\": \"国际新城一栋\",\n    \"estate_district\": \"440303\",\n    \"estate_area\": 70,\n    \"estate_certtype\": 1,\n    \"estate_certnum\": 11111,\n    \"house_type\": 1\n    }\n    ],\n    \"advance_info\": [              担保费信息\n    {\n    \"advance_money\": \"12000000.00\",  垫资金额\n    \"advance_day\": 5,                垫资天数\n    \"advance_rate\": 0.06,            垫资费率\n    \"advance_fee\": \"36000.0\",        垫资费\n    \"remark\": \"\",                    备注说明\n    \"id\": 3\n    }\n    ]\n    \"outbackinfo\": {                       出账回款信息\n             \"out_account_total\": \"1500000.00\",    垫资金额\n             \"account_com_total\": 1500000,         垫资出账金额\n             \"account_cus_total\": 600000,\n             \"out_time\": \"2018-08-23\",           垫资出账时间\n             \"expectday\": \"2018-08-28\",          合同回款日\n             \"readyin_money\": 0,                 已回款金额\n             \"unreadyin_money\": 1500000,         待回款金额(展期申请信息里面也取该值)\n             \"return_time\": null                 最近回款时间\n         }\n    \"cost_info\": {                         费用信息(展期专用)\n        \"sumexhibition_day\": \"17\",           已展期总天数\n        \"sumexhibition_fee\": \"1010.00\",      已收展期费\n        \"sumperiods\": 2,                     已展期期数\n        \"guarantee_fee\": \"3000.00\",          预收担保费\n        \"info_fee\": \"0.00\",                  预计信息费\n        \"ac_guarantee_fee\": \"4534.00\",      实收担保费\n        \"ac_overdue_money\": \"453.00\",        实收逾期费\n        \"exhibition_rate\": 0.12,              展期费率(展期申请信息)\n        \"yuqimoney\": \"213.00\",              应收逾期费\n        \"yuqiday\": 2                         逾期天数\n        }\n    \"linesInfo\": {               额度(现金)赎楼信息\n        \"create_time\": 1534935737,       银行放款时间(额度放尾款)  回款时间(现金放尾款)\n        \"loan_money\": \"2836000.00\",      银行放款金额(额度放尾款)  实际回款金额(现金放尾款)\n        \"foreclosure\": 0,                赎楼金额\n        \"ac_return_money\": \"0.00\",       赎楼返还款\n        \"sum_return_money\": null,        已退赎楼金额\n        \"back_floor\": 2836000            可退赎楼金额\n        },\n    \"canBackInfo\": {           罚息信息\n        \"ac_default_interest\": \"0.00\",    实收罚息金额\n        \"used_interest\": 0,                   已用罚息金额\n        \"sum_default_interest\": null,     已退罚息金额\n        \"can_back_money\": 0               可退罚息金额\n        },\n    \"shortLoan\": {            短贷利息信息\n        \"ac_short_loan_interest\": \"0.00\",      实收短贷利息\n         \"used_short_loan\" :   \"0\"             已用短贷利息\n        \"sum_short_loan\": null,                已退短贷利息金额\n        \"can_back_short_loan\": 0               可退短贷利息金额\n        }\n     }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/importantMatSubDealWith",
    "title": "要事审批提交审批[admin/CostApply/importantMatSubDealWith]",
    "version": "1.0.0",
    "name": "importantMatSubDealWith",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/importantMatSubDealWith"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "proc_id",
            "description": "<p>处理明细表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>审批意见</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_approval",
            "description": "<p>审批结果 1通过 2驳回 3同意并结束流程</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "next_user_id",
            "description": "<p>下一步审批人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "backtoback",
            "description": "<p>是否退回之后直接返回本节点 1 返回 不返回就不需要传值</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "back_proc_id",
            "description": "<p>退回节点id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "process_id",
            "description": "<p>流程步骤表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "process_name",
            "description": "<p>节点名称(当前步骤名称,审批节点)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "next_process_name",
            "description": "<p>流向的审批节点名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_next_user",
            "description": "<p>是否需要选择审查人员 0不需要 1需要</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/importantMatterList",
    "title": "要事审批列表[admin/CostApply/importantMatterList]",
    "version": "1.0.0",
    "name": "importantMatterList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/importantMatterList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/importantMatterRecords",
    "title": "要事审批页面信息[admin/CostApply/importantMatterRecords]",
    "version": "1.0.0",
    "name": "importantMatterRecords",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Approval/importantMatterRecords"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>联系电话</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>所属部门</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/infoCostList",
    "title": "信息费支付申请列表[admin/CostApply/infoCostList]",
    "version": "1.0.0",
    "name": "infoCostList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/infoCostList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n        \"total\": 3,\n        \"per_page\": 10,\n        \"current_page\": 1,\n        \"last_page\": 1,\n        \"data\": [\n                {\n                \"id\": 8                          其他业务表主键id\n                \"process_sn\": \"201808150008\",   流程单号\n                \"order_sn\": \"DQJK2018070004\",   业务单号\n                \"finance_sn\": \"100000023\",      财务编号\n                \"type\": \"DQJK\",                 业务类型\n                \"estate_name\": null,           房产名称\n                \"estate_owner\": null,          业主姓名\n                \"money\": \"0.00\",               支付金额\n                \"stage\": \"10001\",\n                \"create_time\": \"2018-08-15 20:26:11\",   申请时间\n                \"name\": \"管理员\"                        申请人\n                \"type_text\": \"短期借款\",                业务类型\n                \"stage_text\": \"待核算专员审批\"          审批状态\n                },\n                {\n                \"process_sn\": \"201808150007\",\n                \"order_sn\": \"PDXJ2018070002\",\n                \"finance_sn\": \"100000011\",\n                \"type\": \"PDXJ\",\n                \"estate_name\": \"大芬油画苑C栋0单元902\",\n                \"estate_owner\": \"毛淑荣\",\n                \"money\": \"0.00\",\n                \"stage\": \"10001\",\n                \"create_time\": \"2018-08-15 20:25:52\",\n                \"name\": \"管理员\"\n                }\n            ]\n         }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/initiateCommunica",
    "title": "发起沟通[admin/CostApply/initiateCommunica]",
    "version": "1.0.0",
    "name": "initiateCommunica",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/initiateCommunica"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>沟通类别  ZQSQ(展期申请)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "proc_id",
            "description": "<p>处理明细表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "user_id_arr",
            "description": "<p>沟通对象,二维数组 [0 =&gt;['user_id(int)'=&gt;'用户id','user_name（string）'=&gt;'用户名称']]</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>沟通内容</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/isCheckOrder",
    "title": "申请验证[admin/CostApply/isCheckOrder]",
    "version": "1.0.0",
    "name": "isCheckOrder",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/isCheckOrder"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "process_type",
            "description": "<p>添加类型 1信息费支付 2首期转账 3退保证金 4现金按天退担保费 5额度退担保费 6额度类订单放尾款申请 7现金类订单放尾款申请 8展期申请 10(撤单退担保费) 11(撤单不退担保费) 12(撤单保费调整)</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/otherApprovalList",
    "title": "其他退费审核列表[admin/CostApply/otherApprovalList]",
    "version": "1.0.0",
    "name": "otherApprovalList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/otherApprovalList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "other_type",
            "description": "<p>区分从哪个入口调用该接口 1 其它退费审批列表 2其他退费管理列表</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"proc_id\": 5960,                  处理明细表主键id\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程单号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"type\": \"DQJK\",\n    \"estate_name\": null,           房产名称\n    \"estate_owner\": null,          业主姓名\n    \"money\": \"0.00\",               支付金额\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15\",   申请时间\n    \"name\": \"管理员\"                  理财经理\n    \"process_type_text\": \"现金单退担保费\",          付款事项\n    \"stage_text\": \"待核算专员审批\"                  审批状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/otherRefundList",
    "title": "其他退费申请列表[admin/CostApply/otherRefundList]",
    "version": "1.0.0",
    "name": "otherRefundList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/otherRefundList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程单号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"type\": \"DQJK\",\n    \"estate_name\": null,           房产名称\n    \"estate_owner\": null,          业主姓名\n    \"money\": \"0.00\",               付款金额\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15\",   申请时间\n    \"name\": \"管理员\"                        理财经理\n    \"process_type_text\": \"额度单退担保费\"    付款事项\n    \"stage_text\": \"待部门经理审批\"           审批状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/payDetailRejected",
    "title": "支付(退费)详情驳回[admin/CostApply/payDetailRejected]",
    "version": "1.0.0",
    "name": "payDetailRejected",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/payDetailRejected"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>驳回原因</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/renewalAppList",
    "title": "展期申请审批列表[admin/CostApply/renewalAppList]",
    "version": "1.0.0",
    "name": "renewalAppList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/renewalAppList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"proc_id\": 5960,                  处理明细表主键id\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程单号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"estate_name\": null,           房产名称\n    \"return_money\": \"1125.00\",     待回款金额\n    \"money\": \"4000.00\",            实交展期费\n    \"exhibition_day\": 2,           展期天数\n    \"exhibition_fee\": \"20000.00\",  展期费用\n    \"stage\": \"302\",\n    \"create_time\": \"2018-08-24\",      申请时间\n    \"name\": \"刘志刚\",                理财经理\n    \"stage_text\": \"待部门经理审批\"   审批状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/rollOverList",
    "title": "展期申请列表[admin/CostApply/rollOverList]",
    "version": "1.0.0",
    "name": "rollOverList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/rollOverList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程单号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"type\": \"DQJK\",                 业务类型\n    \"estate_name\": null,           房产名称\n    \"return_money\": \"1125.00\",     待回款金额\n    \"money\": \"4000.00\",            实交展期费\n    \"exhibition_day\": 2,           展期天数\n    \"exhibition_fee\": \"20000.00\",  展期费用\n    \"stage\": \"302\",\n    \"create_time\": \"2018-08-24\",      申请时间\n    \"name\": \"刘志刚\",                理财经理\n    \"stage_text\": \"待部门经理审批\"   订单状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/subDealWith",
    "title": "提交审批[admin/CostApply/subDealWith]",
    "version": "1.0.0",
    "name": "subDealWith",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/subDealWith"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "proc_id",
            "description": "<p>处理明细表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_approval",
            "description": "<p>审批结果 1通过 2驳回</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>审批意见</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "backtoback",
            "description": "<p>是否退回之后直接返回本节点 1 返回 不返回就不需要传值</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "back_proc_id",
            "description": "<p>退回节点id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/subRejected",
    "title": "费用支付完驳回[admin/CostApply/subRejected]",
    "version": "1.0.0",
    "name": "subRejected",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/subRejected"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>其他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>驳回原因</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/CostApply/tailSectionList",
    "title": "放尾款申请列表[admin/CostApply/tailSectionList]",
    "version": "1.0.0",
    "name": "tailSectionList",
    "group": "CostApply",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/CostApply/tailSectionList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\n\"data\": {\n    \"total\": 3,\n    \"per_page\": 10,\n    \"current_page\": 1,\n    \"last_page\": 1,\n    \"data\": [\n    {\n    \"id\": 8                          其他业务表主键id\n    \"process_sn\": \"201808150008\",   流程单号\n    \"order_sn\": \"DQJK2018070004\",   业务单号\n    \"finance_sn\": \"100000023\",      财务编号\n    \"type\": \"DQJK\",\n    \"estate_name\": null,           房产名称\n    \"estate_owner\": null,          业主姓名\n    \"money\": \"0.00\",               退款金额\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15\",   申请时间\n    \"name\": \"管理员\"                        理财经理\n    \"stage_text\": \"待部门经理审批\"           审批状态\n    },\n    {\n    \"process_sn\": \"201808150007\",\n    \"order_sn\": \"PDXJ2018070002\",\n    \"finance_sn\": \"100000011\",\n    \"type\": \"PDXJ\",\n    \"estate_name\": \"大芬油画苑C栋0单元902\",\n    \"estate_owner\": \"毛淑荣\",\n    \"money\": \"0.00\",\n    \"stage\": \"10001\",\n    \"create_time\": \"2018-08-15 20:25:52\",\n    \"name\": \"管理员\"\n    }\n    ]\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/CostApply.php",
    "groupTitle": "CostApply"
  },
  {
    "type": "post",
    "url": "admin/Credit/addCredit",
    "title": "新增征信[admin/Credit/addCredit]",
    "version": "1.0.0",
    "name": "addCredit",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/addCredit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>所属类型 ：(个人、企业)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "datacenter_id",
            "description": "<p>数据中心客户id(下拉接口对应id)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "mobile",
            "description": "<p>联系电话</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "customer_name",
            "description": "<p>用户/企业名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "gender",
            "description": "<p>性别</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "certdata",
            "description": "<p>证件信息</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "financing_manager_id",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "financing_dept_id",
            "description": "<p>理财经理部门id</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "picture",
            "description": "<p>授权材料</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark_base",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "get",
    "url": "admin/Credit/creditDetail",
    "title": "征信详情(批量)[admin/Credit/creditDetail]",
    "version": "1.0.0",
    "name": "creditDetail",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/creditDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "id",
            "description": "<p>征信id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "base_data",
            "description": "<p>基础信息</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "search_data",
            "description": "<p>查询信息</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "review_data",
            "description": "<p>审核信息</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_upload",
            "description": "<p>打开征信上传按钮权限 1 有权限 2 无权限</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "file_auth",
            "description": "<p>打开征信文件权限 1 有权限 2 无权限</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "get",
    "url": "admin/Credit/creditList",
    "title": "征信列表[admin/Credit/creditList]",
    "version": "1.0.0",
    "name": "creditList",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/creditList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "time_solt",
            "description": "<p>时间类型：下拉框显示(申请时间、提交人行时间、征信录入时间)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "time_type",
            "description": "<p>时间类型：下拉框显示(申请时间、提交人行时间、征信录入时间)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "credit_result",
            "description": "<p>征信结果 ：下拉框显示(正常、异常)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "checkstatus",
            "description": "<p>查询状态 ：(正在查询、征信报告已出、查询失败)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "financing_manager_id",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>列表数据集</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>数据获取状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "get",
    "url": "admin/Credit/delCredit",
    "title": "删除征信[admin/Credit/delCredit]",
    "version": "1.0.0",
    "name": "delCredit",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/delCredit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>征信id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "get",
    "url": "admin/Credit/downCredit",
    "title": "下载征信报告[admin/Credit/downCredit]",
    "version": "1.0.0",
    "name": "downCredit",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/downCredit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>征信文件id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "post",
    "url": "admin/Credit/editCredit",
    "title": "编辑征信[admin/Credit/editCredit]",
    "version": "1.0.0",
    "name": "editCredit",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/editCredit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>征信id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "customer_id",
            "description": "<p>客户id</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "certdata",
            "description": "<p>证件信息</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "financing_manager_id",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "financing_dept_id",
            "description": "<p>理财经理部门id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "picture",
            "description": "<p>授权材料</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "remark_base",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "post",
    "url": "admin/Credit/editOrder",
    "title": "编辑派单[admin/Credit/editOrder]",
    "version": "1.0.0",
    "name": "editOrder",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/editOrder"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>征信id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "is_auth_accessory",
            "description": "<p>是否授权人行(0否1是)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "is_auth_accessory_py",
            "description": "<p>是否授权鹏元（0否1是）</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "post",
    "url": "admin/Credit/editReviewinfo",
    "title": "编辑审核信息[admin/Credit/editReviewinfo]",
    "version": "1.0.0",
    "name": "editReviewinfo",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/editReviewinfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>征信id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "credit_result",
            "description": "<p>查询状态</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark_trial",
            "description": "<p>征信审核备注</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "picture",
            "description": "<p>文件数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "get",
    "url": "admin/Credit/exportCredit",
    "title": "导出征信列表[admin/Credit/exportCredit]",
    "version": "1.0.0",
    "name": "exportCredit",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/exportCredit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "time_solt",
            "description": "<p>时间类型：下拉框显示(申请时间、提交人行时间、征信录入时间)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "time_type",
            "description": "<p>时间类型：下拉框显示(申请时间、提交人行时间、征信录入时间)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "credit_result",
            "description": "<p>征信结果 ：下拉框显示(正常、异常)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "checkstatus",
            "description": "<p>查询状态 ：(正在查询、征信报告已出、查询失败)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "financing_manager_id",
            "description": "<p>理财经理</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "get",
    "url": "admin/Credit/getCreditinfo",
    "title": "获取征信信息[admin/Credit/getCreditinfo]",
    "version": "1.0.0",
    "name": "getCreditinfo",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/getCreditinfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>征信id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "get",
    "url": "admin/Credit/getDictionaryByType",
    "title": "获取数据字典类型[admin/Credit/getDictionaryByType]",
    "version": "1.0.0",
    "name": "getDictionaryByType",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/getDictionaryByType"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>数据字典数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Dictionary.php",
    "groupTitle": "Credit"
  },
  {
    "type": "get",
    "url": "admin/Credit/lookCredit",
    "title": "查看征信报告[admin/Credit/lookCredit]",
    "version": "1.0.0",
    "name": "lookCredit",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/lookCredit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>图片id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "fileurl",
            "description": "<p>征信报告路径（仅用于展示）</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "get",
    "url": "admin/Credit/ordersnList",
    "title": "模糊获取业务单号[admin/Credit/ordersnList]",
    "version": "1.0.0",
    "name": "ordersnList",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/ordersnList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "post",
    "url": "admin/Credit/submitTobank",
    "title": "提交至人行(批量)[admin/Credit/submitTobank]",
    "version": "1.0.0",
    "name": "submitTobank",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/submitTobank"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "ids",
            "description": "<p>征信ids</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "post",
    "url": "admin/Credit/uploadCredit",
    "title": "上传征信信息[admin/Credit/uploadCredit]",
    "version": "1.0.0",
    "name": "uploadCredit",
    "group": "Credit",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Credit/uploadCredit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>征信id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "checkstatus",
            "description": "<p>查询状态</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark_inquiry",
            "description": "<p>征信查询备注</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "file",
            "description": "<p>文件数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Credit.php",
    "groupTitle": "Credit"
  },
  {
    "type": "post",
    "url": "admin/Customer/addCard",
    "title": "新增证件[admin/Customer/addCard]",
    "version": "1.0.0",
    "name": "addCard",
    "group": "Customer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Customer/addCard"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>用户id（老系统用户id）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1个人2企业</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "certdata",
            "description": "<p>证件信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Customer.php",
    "groupTitle": "Customer"
  },
  {
    "type": "post",
    "url": "admin/Customer/addZCCustomer",
    "title": "添加客户管理系统客户[admin/Customer/addZCCustomer]",
    "version": "1.0.0",
    "name": "addZCCustomer",
    "group": "Customer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Customer/addZCCustomer"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>客户类型</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "name",
            "description": "<p>客户姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "gender",
            "description": "<p>客户性别（征信才需要）</p>"
          },
          {
            "group": "Parameter",
            "type": "str",
            "optional": false,
            "field": "mobile",
            "description": "<p>电话</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "certdata",
            "description": "<p>证件数据 certcode证件号码certtype证件类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "customermanager",
            "description": "<p>客户经理（征信才需要）</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Customer.php",
    "groupTitle": "Customer"
  },
  {
    "type": "post",
    "url": "admin/Customer/getCusinfo",
    "title": "根据证件号自动匹配用户信息[admin/Customer/getCusinfo]",
    "version": "1.0.0",
    "name": "getCusinfo",
    "group": "Customer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Customer/getCusinfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "certcode",
            "description": "<p>证件号码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "certtype",
            "description": "<p>证件类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>类型： 1 个人 2企业</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "selectdata",
            "description": "<p>用户名称下拉框数据</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "carddata",
            "description": "<p>证件信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Customer.php",
    "groupTitle": "Customer"
  },
  {
    "type": "post",
    "url": "admin/Customer/updateZCCustomer",
    "title": "更新客户管理系统客户[admin/Customer/updateZCCustomer]",
    "version": "1.0.0",
    "name": "updateZCCustomer",
    "group": "Customer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Customer/updateZCCustomer"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>客户id</p>"
          },
          {
            "group": "Parameter",
            "type": "str",
            "optional": false,
            "field": "mobile",
            "description": "<p>电话</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Customer.php",
    "groupTitle": "Customer"
  },
  {
    "type": "post",
    "url": "admin/Customer/zcCustomer",
    "title": "获取客户管理系统客户列表[admin/Customer/zcCustomer]",
    "version": "1.0.0",
    "name": "zcCustomer",
    "group": "Customer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Customer/zcCustomer"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>客户类型</p>"
          },
          {
            "group": "Parameter",
            "type": "str",
            "optional": false,
            "field": "certType",
            "description": "<p>证件类型</p>"
          },
          {
            "group": "Parameter",
            "type": "str",
            "optional": false,
            "field": "certcode",
            "description": "<p>证件号码</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Customer.php",
    "groupTitle": "Customer"
  },
  {
    "type": "post",
    "url": "admin/Dictionary/addDictionary",
    "title": "添加数据字典[admin/Dictionary/addDictionary]",
    "version": "1.0.0",
    "name": "addDictionary",
    "group": "Dictionary",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Dictionary/addDictionary"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>所属分类(添加二级分类时才传该参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>标识</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "valname",
            "description": "<p>名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Dictionary.php",
    "groupTitle": "Dictionary"
  },
  {
    "type": "post",
    "url": "admin/Dictionary/delDictionary",
    "title": "数据字典禁用和删除[admin/Dictionary/delDictionary]",
    "version": "1.0.0",
    "name": "delDictionary",
    "group": "Dictionary",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Dictionary/delDictionary"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>数据字典表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1 代表禁用  2代表删除</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Dictionary.php",
    "groupTitle": "Dictionary"
  },
  {
    "type": "post",
    "url": "admin/Dictionary/editDictionary",
    "title": "数据字典编辑[admin/Dictionary/editDictionary]",
    "version": "1.0.0",
    "name": "editDictionary",
    "group": "Dictionary",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Dictionary/editDictionary"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>数据字典表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>所属分类(编辑二级分类时才传该参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>标识</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "valname",
            "description": "<p>名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Dictionary.php",
    "groupTitle": "Dictionary"
  },
  {
    "type": "post",
    "url": "admin/Dictionary/getPrimaryData",
    "title": "获取数据字典列表[admin/Dictionary/getPrimaryData]",
    "version": "1.0.0",
    "name": "getPrimaryData",
    "group": "Dictionary",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Dictionary/getPrimaryData"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>一级数据字典传0 二级传对应的type</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "key",
            "description": "<p>名称值等关键字</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>数据字典表主键id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>数据类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>标识</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "valname",
            "description": "<p>名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>录入时间</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Dictionary.php",
    "groupTitle": "Dictionary"
  },
  {
    "type": "post",
    "url": "admin/Dictionary/showDictionary",
    "title": "数据字典查询[admin/Dictionary/showDictionary]",
    "version": "1.0.0",
    "name": "showDictionary",
    "group": "Dictionary",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Dictionary/showDictionary"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>数据字典表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>数据字典表主键id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>标识</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "valname",
            "description": "<p>名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "nameone",
            "description": "<p>一级分类名称(编辑二级分类时才返回该值)</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Dictionary.php",
    "groupTitle": "Dictionary"
  },
  {
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "application/model/Order.php",
    "group": "F__phpstudy_WWW_businesssys_api_application_model_Order_php",
    "groupTitle": "F__phpstudy_WWW_businesssys_api_application_model_Order_php",
    "name": ""
  },
  {
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "application/model/Order.php",
    "group": "F__phpstudy_WWW_businesssys_api_application_model_Order_php",
    "groupTitle": "F__phpstudy_WWW_businesssys_api_application_model_Order_php",
    "name": ""
  },
  {
    "type": "post",
    "url": "admin/FinancialReceipts/FinancialBackdetail",
    "title": "财务回款详情[admin/FinancialReceipts/FinancialBackdetail]",
    "version": "1.0.0",
    "name": "FinancialBackdetail",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/FinancialBackdetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_sonorder",
            "description": "<p>1列表页订单 2子单列表页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "return_id",
            "description": "<p>回款表ID(列表页ID)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n\"baseinfo\": {\n\"estate_name\": [\n\"吉祥龙花园A座2单元10\"\n],\n\"estate_owner\": \"llx\",\n\"type_text\": \"交易现金\",\n\"type\": \"JYXJ\",\n\"finance_sn\": \"100000406\",\n\"return_money_status\": 1,\n\"return_money_status_text\": \"回款待完成\",\n\"order_sn\": \"JYXJ2018080050\",\n\"money\": \"2000000.00\",\n\"default_interest\": \"0.00\",\n\"self_financing\": \"0.00\",\n\"channel_money\": \"2000000.00\",\n\"company_money\": \"0.00\",\n\"can_money\": 2000000,\n\"out_money\": 2000000,\n\"use_money\": 0,\n\"notarization\": \"2018-08-29\",\n\"return_money_amount\": \"2000000.00\",\n\"return_money_mode\": \"通过业主回款\"\n},\n\"backcardinfo\": [\n{\n\"id\": 665,\n\"bankaccount\": \"回款测试\",\n\"accounttype\": \"卖方\",\n\"bankcard\": \"14545121545211\",\n\"openbank\": \"工商银行\",\n\"verify_card_status\": \"已完成\",\n\"type_text\": \"过账卡、回款卡\"\n},\n{\n\"id\": 666,\n\"bankaccount\": \"回款测试1\",\n\"accounttype\": \"买方\",\n\"bankcard\": \"45155468445417\",\n\"openbank\": \"工商银行\",\n\"verify_card_status\": \"已完成\",\n\"type_text\": \"回款卡\"\n}\n],\n\"outbackinfo\": {\n\"out_account_total\": 2000000,\n\"account_com_total\": 2000000,\n\"account_cus_total\": 0,\n\"out_time\": \"2018-08-29\",\n\"expectday\": \"2018-09-07\",\n\"readyin_money\": 0,\n\"unreadyin_money\": 2000000,\n\"sq_money\": null,//首期款金额\n\"return_time\": null,\n\"ac_return_time\": null//实际回款时间\n},\n\"fee\": {\n\"gufee_total\": 0,//正常担保费\n\"exhibition_fee_total\": 0,//实际展期费\n\"on_gufee\": \"12000.00\",\n\"ac_exhibition_fee\": \"0.00\",\n\"info_fee\": \"0.00\",\n\"ac_overdue_money\": 0,\n\"ready_overdue_money\": \"0.00\",\n\"ac_transfer_fee\": \"0.00\",\n\"ac_other_money\": \"0.00\",\n\"ac_fee\": \"300.00\",\n\"totol_money\": 0,\n\"ready_money\": 12300,\n\"un_money\": -12300\n},\n\"backmoneyrecord\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "post",
    "url": "admin/FinancialReceipts/addBackmoneyrecord",
    "title": "新增/编辑回款入账[admin/FinancialReceipts/addBackmoneyrecord]",
    "version": "1.0.0",
    "name": "addBackmoneyrecord",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/addBackmoneyrecord"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>回款记录id （编辑的时候才需要传）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "unmoney",
            "description": "<p>待回款金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "money",
            "description": "<p>回款金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_card_id",
            "description": "<p>银行卡ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_card",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_account",
            "description": "<p>银行账户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>支行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_name",
            "description": "<p>银行账号别名（下拉选中的值）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "customer_bank_card",
            "description": "<p>业主汇款账号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "customer_bank",
            "description": "<p>业主汇款账号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "customer_bank_account",
            "description": "<p>业主汇款账号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_guarantee_bank_id",
            "description": "<p>业主汇款账号ID</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "ac_return_time",
            "description": "<p>实际回款时间</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "return_time",
            "description": "<p>预计回款时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "attachment",
            "description": "<p>授权材料（数组）eg:[1,2]</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "get",
    "url": "admin/FinancialReceipts/collectList",
    "title": "费用信息列表[admin/FinancialReceipts/collectList]",
    "version": "1.0.0",
    "name": "collectList",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/collectList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "number",
            "optional": false,
            "field": "type",
            "description": "<p>费用类型 1正常担保 2展期 3逾期 数据字典类型 PAYBACK_FEE_TYPE</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "list",
            "description": "<p>费用信息列表.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.cal_date",
            "description": "<p>计算费用日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.wait_money",
            "description": "<p>待收回款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.calc_total_money",
            "description": "<p>计算费用总额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.type",
            "description": "<p>费用类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.rate",
            "description": "<p>计算费率%</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.money",
            "description": "<p>当日费用</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.act_calc_total_amont",
            "description": "<p>实际计算费用总和</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.receive_total_amount",
            "description": "<p>已收计算费用总和</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.less_calc_total_amont",
            "description": "<p>剩余计算费用总和</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "get",
    "url": "admin/FinancialReceipts/costList",
    "title": "回款入账列表 [admin/FinancialReceipts/costList]",
    "version": "1.0.0",
    "name": "costList",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/costList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "list",
            "description": "<p>回款入账列表.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.type",
            "description": "<p>操作类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.item",
            "description": "<p>项目明细</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.statustext",
            "description": "<p>状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.money",
            "description": "<p>金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.cost_date",
            "description": "<p>时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.out_money_total",
            "description": "<p>累计出账总额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_money_already",
            "description": "<p>已收回款总额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_money_wait",
            "description": "<p>待收回款总额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "get",
    "url": "admin/FinancialReceipts/extensionList",
    "title": "展期信息列表[admin/FinancialReceipts/extensionList]",
    "version": "1.0.0",
    "name": "extensionList",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/extensionList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "list",
            "description": "<p>展期信息列表.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_money",
            "description": "<p>待收回款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.exhibition_starttime",
            "description": "<p>展期开始时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.exhibition_endtime",
            "description": "<p>展期合同结束时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.actual_exhibition_endtime",
            "description": "<p>展期实际结束时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.actual_exhibition_day",
            "description": "<p>实际展期天数</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.exhibition_rate",
            "description": "<p>展期费率</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.total_money",
            "description": "<p>实际展期费</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.money",
            "description": "<p>已收展期费  // 展期应交金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.create_user",
            "description": "<p>展期申请人</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "extension",
            "description": "<p>展期信息列表.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "extension.exten_time",
            "description": "<p>展期次数</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "extension.exten_days",
            "description": "<p>实际展期总天数</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "extension.exten_total_money",
            "description": "<p>实际展期费总额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "extension.exten_receive_money",
            "description": "<p>已收展期费总额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "post",
    "url": "admin/FinancialReceipts/finishPayback",
    "title": "回款完成 [admin/FinancialReceipts/finishPayback]",
    "version": "1.0.0",
    "name": "finishPayback",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/finishPayback"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>pending|success  类型</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "number",
            "optional": false,
            "field": "code",
            "description": "<p>1可以回款完成 -1 不能完成回款</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "get",
    "url": "admin/FinancialReceipts/getAllreceiptcard",
    "title": "获取所有公司回款卡账户信息[admin/FinancialReceipts/getAllreceiptcard]",
    "version": "1.0.0",
    "name": "getAllreceiptcard",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/getAllreceiptcard"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "name",
            "description": "<p>账号名称</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>银行卡id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>银行卡别名（下拉展示的值）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "openbank",
            "description": "<p>银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankaccount",
            "description": "<p>开户人</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankcard",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>支行</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "get",
    "url": "admin/FinancialReceipts/getAllreceiptincard",
    "title": "获取所有业主回款卡账户信息[admin/FinancialReceipts/getAllreceiptincard]",
    "version": "1.0.0",
    "name": "getAllreceiptincard",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/getAllreceiptincard"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "openbank",
            "description": "<p>银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankaccount",
            "description": "<p>开户人</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankcard",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "Allbank",
            "description": "<p>所有出账银行，仅展示</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "get",
    "url": "admin/FinancialReceipts/getReceiptinfo",
    "title": "获取回款记录信息[admin/FinancialReceipts/getReceiptinfo]",
    "version": "1.0.0",
    "name": "getReceiptinfo",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/getReceiptinfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>回款记录ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "money",
            "description": "<p>回款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank_name",
            "description": "<p>回款账号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "return_time",
            "description": "<p>回款到账时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "get",
    "url": "admin/FinancialReceipts/index",
    "title": "回款管理列表[admin/FinancialReceipts/index]",
    "version": "1.0.0",
    "name": "index",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/index"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "return_money_status",
            "description": "<p>回款状态（1回款待完成 2回款完成待复核 3回款完成待核算 4回款已完成） 数据字典类型 PAYBACK_STATUS</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "city",
            "description": "<p>城市</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "district",
            "description": "<p>城区</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "list",
            "description": "<p>回款管理列表.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_money_amount",
            "description": "<p>应收回款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_money",
            "description": "<p>已收回款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_time",
            "description": "<p>回款到账时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_money_status_text",
            "description": "<p>回款状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.financing_manager",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "post",
    "url": "admin/FinancialReceipts/payBackEnterRecheck",
    "title": "回款入账复核 | 回款入账核算| 作废 [admin/FinancialReceipts/payBackEnterRecheck]",
    "version": "1.0.0",
    "name": "payBackEnterRecheck",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/payBackEnterRecheck"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "id",
            "optional": false,
            "field": "id",
            "description": "<p>入账id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>审批意见  1:通过 2:驳回 3:作废</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>原因</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "get",
    "url": "admin/FinancialReceipts/payBackList",
    "title": "回款入账审核列表[admin/FinancialReceipts/payBackList]",
    "version": "1.0.0",
    "name": "payBackList",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/payBackList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "return_money_status",
            "description": "<p>回款状态 1回款入账待复核 2回款入账待核算 3回款入账已完成 4驳回待处理 -1已作废</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "list",
            "description": "<p>回款入账审核列表.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.bank_account",
            "description": "<p>回款户名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.bank_card",
            "description": "<p>回款账号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.bank",
            "description": "<p>回款银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_money_amount",
            "description": "<p>应收回款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_money",
            "description": "<p>已收回款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.money",
            "description": "<p>本次回款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_time",
            "description": "<p>本次回款到账时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.ac_return_time",
            "description": "<p>实际回款时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.return_money_status_text",
            "description": "<p>回款入账状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.financing_manager",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "post",
    "url": "admin/FinancialReceipts/payBackRecheck",
    "title": "回款完成待复核|回款完成待核算 [admin/FinancialReceipts/payBackRecheck]",
    "version": "1.0.0",
    "name": "payBackRecheck",
    "group": "FinancialReceipts",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialReceipts/payBackRecheck"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>审批意见  1:通过 2:驳回</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>原因</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialReceipts.php",
    "groupTitle": "FinancialReceipts"
  },
  {
    "type": "get",
    "url": "admin/FinancialWriteoff/determineWriteoff",
    "title": "确认核销[admin/FinancialWriteoff/determineWriteoff]",
    "version": "1.0.0",
    "name": "determineWriteoff",
    "group": "FinancialWriteoff",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialWriteoff/determineWriteoff"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼派单表id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialWriteoff.php",
    "groupTitle": "FinancialWriteoff"
  },
  {
    "type": "get",
    "url": "admin/FinancialWriteoff/financialDetail",
    "title": "财务审核详情页[admin/FinancialWriteoff/financialDetail]",
    "version": "1.0.0",
    "name": "financialDetail",
    "group": "FinancialWriteoff",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialWriteoff/financialDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼派单id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n      \"basic_information\": {                      基本信息\n      \"order_sn\": \"JYDB2018050137123456\",    业务单号\n      \"type\": \"JYDB\",        业务类型\n      \"finance_sn\": \"100000048\",      财务序号\n      \"guarantee_money\": \"2.00\",      担保金额\n      \"guarantee_per\": 2,            担保成数\n      \"financing_manager_name\": \"夏丽平\",    理财经理\n      \"dept_manager_name\": \"杜欣\",           部门经理\n      \"deptname\": \"总经办\"                   所属部门\n      },\n      \"estate_info\": [   房产信息\n      {\n      \"estate_name\": \"国际新城一栋\",                  房产名称\n      \"estate_region\": \"深圳市|罗湖区|桂园街道\",      所属城区\n      \"estate_area\": 70,                             房产面积\n      \"estate_certtype\": 1,                          产证类型\n      \"estate_certnum\": 11111,                       产证编码\n      \"house_type\": 1                                房产类型 1分户 2分栋\n      },\n      {\n      \"estate_name\": \"国际新城一栋\",\n      \"estate_district\": \"440303\",\n      \"estate_area\": 70,\n      \"estate_certtype\": 1,\n      \"estate_certnum\": 11111,\n      \"house_type\": 1\n      }\n      ],\n      \"seller_info\": [       买方信息(is_seller = 1 && is_comborrower = 0) 买方共同借款人(is_seller = 1 && is_comborrower = 1)\n      {                      卖方信息(is_seller = 2 && is_comborrower = 0) 卖方共同借款人(is_seller = 2 && is_comborrower = 1)\n      \"is_seller\": 2,               客户 1买方 2卖方\n      \"is_comborrower\": 0,           共同借款人属性 0借款人 1共同借款人\n      \"cname\": \"张三\",                 卖方姓名\n      \"ctype\": 1,                      卖方类型 1个人 2企业\n      \"certtype\": 1,                   证件类型\n      \"certcode\": \"11111122322\",       证件号码\n      \"mobile\": \"18825454079\",         电话号码\n      \"is_guarantee\": 0                 担保申请人 1是 0否\n      },\n      {\n      \"cname\": \"张三\",\n      \"ctype\": 1,\n      \"certtype\": 1,\n      \"certcode\": \"11111122322\",\n      \"mobile\": \"18825454079\",\n      \"is_guarantee\": 0\n      }\n      ],\n      \"preliminary_question\": [    风控初审问题汇总\n      {\n      \"describe\": \"呵呵456\",     问题描述\n      \"status\": 0               是否解决  0未解决 1已经解决\n      },\n      {\n      \"describe\": \"呵呵帅那个帅789\",\n      \"status\": 0\n      }\n      ],\n      \"needing_attention\": [   风控提醒注意事项\n      {\n      \"process_name\": \"收到公司的\",    来源\n      \"item\": \"啥打法是否\"             注意事项\n      },\n      {\n      \"process_name\": \"测试\",\n      \"item\": \"测试注意事项\"\n      }\n      ],\n      \"arrears_info\": [    欠款及出账金额\n      {\n      \"organization\": \"银行\",      欠款机构名称\n      \"interest_balance\": \"111111.11\",    欠款金额\n      \"mortgage_type_name\": \"商业贷款\",   欠款类型\n      \"accumulation_fund\": \"2.00\"         出账金额\n      },\n      {\n      \"organization\": \"银行\",\n      \"interest_balance\": \"111111.11\",\n      \"mortgage_type_name\": \"公积金贷款\",\n      \"accumulation_fund\": \"2.00\"\n      }\n      ],\n      \"reimbursement_info\": [   预录赎楼还款账户\n      {\n      \"bankaccount\": \"张三\",   银行户名\n      \"accounttype\": 1,        账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它\n      \"bankcard\": \"111111\",    银行卡号\n      \"openbank\": \"中国银行\"    开户银行\n      },\n      {\n      \"bankaccount\": \"李四\",\n      \"accounttype\": 5,\n      \"bankcard\": \"111\",\n      \"openbank\": \"工商银行\"\n      }\n      ],\n      \"cost_account\":{     费用入账\n      \"guarantee_fee\": \"1000.00\",   担保费\n      \"fee\": \"-15.00\",              手续费\n      \"self_financing\": \"30.00\",    自筹金额\n      \"short_loan_interest\": \"-12.30\",   短贷利息\n      \"return_money\": \"12.50\",           赎楼返还款\n      \"default_interest\": \"0.00\",        罚息\n      \"overdue_money\": \"0.00\",           逾期金额\n      \"other_money\": \"0.00\"             其他\n      },\n      \"lend_books\": [    银行放款入账\n      {\n      \"bank_money\": \"56786.00\",             放款金额\n      \"lender_bank\": \"中国银行\",           放款银行\n      \"receivable_account\": \"中国银行账户\",    收款账户\n      \"bank_money_time\": \"2019-11-03\",        到账时间\n      \"remark\": \"法国红酒狂欢节\",             备注说明\n      \"operation_name\": \"杜欣\"                入账人员\n      },\n      {\n      \"bank_money\": \"123456.00\",\n      \"lender_bank\": \"中国银行\",\n      \"receivable_account\": \"中国银行账户\",\n      \"bank_money_time\": \"2019-11-02\",\n      \"remark\": \"啊是的范德萨\",\n      \"operation_name\": \"杜欣\"\n      }\n      ],\n      \"status_info\": {        各种需要用到的状态字段\n      \"guarantee_fee_status\": 2,     （担保费）收费状态 1未收齐 2已收齐\n      \"bank_money_status\": 1,         银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理\n      \"instruct_status\": 3,           指令状态（1待申请 2待发送 3已发送）\n      \"is_bank_loan_finish\": 1,             发送指令后银行放款 1放款完成 （默认为空）\n      \"is_comborrower_sell\": 1       是否卖方有共同借款人 0否 1是\n      }\n      \"dispatch\": [\n      {        赎楼状态\n      \"ransom_type_text\": \"商业贷款\",     赎楼类型\n      \"ransom_bank\": 中国银行-车公庙支行,           赎楼银行\n      \"ransom_status_text\": 已完成,         赎楼状态\n      \"ransomer\": \"张三\",             赎楼员\n      }\n      ],\n      \"debitinfolog\": [{        出账流水记录\n      \"money\": 1500,     出账金额\n      \"item_text\": \"银行罚息\",  出账项目\n      \"way_text\": 现金,  出账方式\n      \"is_prestore_text\": \"是\",  是否预存\n      \"ransomer\": \"张三\",  赎楼员\n      \"cut_money\": 1500,  确认扣款金额\n      \"account_status_text\": \"银行已扣款\",  出账状态\n      \"outok_time\": \"2018-10-24\",  出账时间\n      },\n      {\n      'cut_money':50000,  总共确认扣款金额\n      }],\n      }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/FinancialWriteoff.php",
    "groupTitle": "FinancialWriteoff"
  },
  {
    "type": "get",
    "url": "admin/FinancialWriteoff/financialOff",
    "title": "财务核销列表[admin/FinancialWriteoff/financialOff]",
    "version": "1.0.0",
    "name": "financialOff",
    "group": "FinancialWriteoff",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FinancialWriteoff/financialOff"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_verify",
            "description": "<p>核销状态（0：未核销 1已核销）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type_text",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_verify_text",
            "description": "<p>核销状态（0：未核销 1已核销）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransomer",
            "description": "<p>赎楼员</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransom_bank",
            "description": "<p>赎楼银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransom_end_time",
            "description": "<p>赎楼时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "financing_manager",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FinancialWriteoff.php",
    "groupTitle": "FinancialWriteoff"
  },
  {
    "type": "post",
    "url": "admin/Financial/addBankWater",
    "title": "增加银行入账流水[admin/Financial/addBankWater]",
    "version": "1.0.0",
    "name": "addBankWater",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/addBankWater"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "loan_money",
            "description": "<p>银行放款金额</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "bank_card_id",
            "description": "<p>收款账户id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "lender_object",
            "description": "<p>放款银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "receivable_account",
            "description": "<p>收款账户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "into_money_time",
            "description": "<p>到账时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_loan_finish",
            "description": "<p>银行放款是否完成 0未完成 1已完成</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/addBooksWater",
    "title": "增加财务入账流水[admin/Financial/addBooksWater]",
    "version": "1.0.0",
    "name": "addBooksWater",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/addBooksWater"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "guarantee_fee",
            "description": "<p>担保费</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "fee",
            "description": "<p>手续费</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "self_financing",
            "description": "<p>自筹金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "short_loan_interest",
            "description": "<p>短贷利息</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "return_money",
            "description": "<p>赎楼返还款</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "default_interest",
            "description": "<p>罚息</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "overdue_money",
            "description": "<p>逾期金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "exhibition_fee",
            "description": "<p>展期费</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "transfer_fee",
            "description": "<p>过账手续费</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "cost_time",
            "description": "<p>入账时间(2018-05-08)</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "deposit",
            "description": "<p>保证金</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "other_money",
            "description": "<p>其它</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注说明</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "guarantee_fee_status",
            "description": "<p>收费状态 1未收齐 2已收齐</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/bankHasList",
    "title": "银行放款已入账列表[admin/Financial/bankHasList]",
    "version": "1.0.0",
    "name": "bankHasList",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/bankHasList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "loan_money_status",
            "description": "<p>银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/bankLendList",
    "title": "银行放款待入账列表[admin/Financial/bankLendList]",
    "version": "1.0.0",
    "name": "bankLendList",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/bankLendList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "loan_money_status",
            "description": "<p>银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"total\": 2,\n      \"per_page\": 20,\n      \"current_page\": 1,\n      \"last_page\": 1,\n      \"data\": [\n          {\n          \"order_sn\": \"JYDB2018050096\",\n          \"finance_sn\": \"100000047\",\n          \"type\": \"JYDB\",\n          \"name\": \"夏丽平\",\n          \"estate_name\": \"国际新城\",\n          \"estate_owner\": null,\n          \" loan_money_time\": \"2018-05-08 14:50:07\",\n          \"guarantee_money\": \"2.00\",\n          \" loan_money\": \"0.00\",\n          \"loan_money_status\": 1\n          },\n          {\n          \"order_sn\": \"JYDB2018050095\",\n          \"finance_sn\": \"100000047\",\n          \"type\": \"JYDB\",\n          \"name\": \"夏丽平\",\n          \"estate_name\": \"国际新城\",\n          \"estate_owner\": null,\n          \" loan_money_time\": \"2018-05-08 14:46:58\",\n          \"guarantee_money\": \"2.00\",\n          \" loan_money\": \"0.00\",\n          \"loan_money_status\": 1\n          }\n      ]\n  }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "total",
            "description": "<p>总条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示的条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "current_page",
            "description": "<p>当前页</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "last_page",
            "description": "<p>总页数</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "loan_money_time",
            "description": "<p>复核时间</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "guarantee_money",
            "description": "<p>担保金额</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "loan_money",
            "description": "<p>银行放款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "loan_money_status",
            "description": "<p>入账状态 1待入账 2待复核 3已复核 4驳回待处理</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/bookedHasList",
    "title": "财务费用已入账列表[admin/Financial/bookedHasList]",
    "version": "1.0.0",
    "name": "bookedHasList",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/bookedHasList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "guarantee_fee_status",
            "description": "<p>收费状态 1未收齐 2已收齐</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/bookedList",
    "title": "财务费用待入账列表[admin/Financial/bookedList]",
    "version": "1.0.0",
    "name": "bookedList",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/bookedList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "guarantee_fee_status",
            "description": "<p>收费状态 1未收齐 2已收齐</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"total\": 2,\n      \"per_page\": 20,\n      \"current_page\": 1,\n      \"last_page\": 1,\n      \"data\": [\n          {\n          \"order_sn\": \"JYDB2018050096\",\n          \"finance_sn\": \"100000047\",\n          \"type\": \"JYDB\",\n          \"name\": \"夏丽平\",\n          \"estate_name\": \"国际新城\",\n          \"estate_owner\": null,\n          \"ac_guarantee_fee_time\": \"2018-05-08 14:50:07\",\n          \"guarantee_fee\": \"2.00\",\n          \"ac_guarantee_fee\": \"0.00\",\n          \"guarantee_fee_status\": 1\n          },\n          {\n          \"order_sn\": \"JYDB2018050095\",\n          \"finance_sn\": \"100000047\",\n          \"type\": \"JYDB\",\n          \"name\": \"夏丽平\",\n          \"estate_name\": \"国际新城\",\n          \"estate_owner\": null,\n          \"ac_guarantee_fee_time\": \"2018-05-08 14:46:58\",\n          \"guarantee_fee\": \"2.00\",\n          \"ac_guarantee_fee\": \"0.00\",\n          \"guarantee_fee_status\": 1\n          }\n      ]\n  }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "total",
            "description": "<p>总条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示的条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "current_page",
            "description": "<p>当前页</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "last_page",
            "description": "<p>总页数</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ac_guarantee_fee_time",
            "description": "<p>入账时间</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "guarantee_fee",
            "description": "<p>应收担保费</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "ac_guarantee_fee",
            "description": "<p>实收担保费</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "guarantee_fee_status",
            "description": "<p>收费状态 1未收齐 2已收齐</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/editReview",
    "title": "银行放款入账复核[admin/Financial/editReview]",
    "version": "1.0.0",
    "name": "editReview",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/editReview"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>按钮区分  1 确认复核 2驳回</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "get",
    "url": "admin/Financial/exportBankHas",
    "title": "银行放款已入账导出[admin/Financial/exportBankHas]",
    "version": "1.0.0",
    "name": "exportBankHas",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/exportBankHas"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "loan_money_status",
            "description": "<p>银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "get",
    "url": "admin/Financial/exportFinanceList",
    "title": "财务费用已入账列表导出[admin/Financial/exportFinanceList]",
    "version": "1.0.0",
    "name": "exportFinanceList",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/exportFinanceList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/exportInstructionHas",
    "title": "导出已发送指令列表[admin/Financial/exportInstructionHas]",
    "version": "1.0.0",
    "name": "exportInstructionHas",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/exportInstructionHas"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_lend",
            "description": "<p>是否放款（1是 2否）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/foreclosureInfo",
    "title": "赎楼出账表信息[admin/Financial/foreclosureInfo]",
    "version": "1.0.0",
    "name": "foreclosureInfo",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/foreclosureInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n      \"basic_information\": {                      基本信息\n      \"order_sn\": \"JYDB2018050137123456\",    业务单号\n      \"type\": \"JYDB\",        业务类型\n      \"finance_sn\": \"100000048\",      财务序号\n      \"order_source\": 1,\n      \"source_info\": \"万科地产\",          订单来源\n      \"order_source_str\": \"合作中介\",     来源机构\n      \"financing_manager_name\": \"夏丽平\",    理财经理\n      \"dept_manager_name\": \"杜欣\",           部门经理\n      \"deptname\": \"总经办\"                   所属部门\n      },\n      \"estate_info\": [   房产信息\n      {\n      \"estate_name\": \"国际新城一栋\",                  房产名称\n      \"estate_region\": \"深圳市|罗湖区|桂园街道\",      所属城区\n      \"estate_area\": 70,                             房产面积\n      \"estate_certtype\": 1,                          产证类型\n      \"estate_certnum\": 11111,                       产证编码\n      \"house_type\": 1                                房产类型 1分户 2分栋\n      },\n      {\n      \"estate_name\": \"国际新城一栋\",\n      \"estate_district\": \"440303\",\n      \"estate_area\": 70,\n      \"estate_certtype\": 1,\n      \"estate_certnum\": 11111,\n      \"house_type\": 1\n      }\n      ],\n      \"seller_info\": [    买方信息(is_seller = 1 && is_comborrower = 0) 买方共同借款人(is_seller = 1 && is_comborrower = 1)\n      {               卖方信息(is_seller = 2 && is_comborrower = 0) 卖方共同借款人(is_seller = 2 && is_comborrower = 1)\n      \"is_seller\": 2,\n      \"is_comborrower\": 0,           共同借款人属性 0借款人 1共同借款人\n      \"cname\": \"张三\",                 卖方姓名\n      \"ctype\": 1,                      卖方类型 1个人 2企业\n      \"certtype\": 1,                   证件类型\n      \"certcode\": \"11111122322\",       证件号码\n      \"mobile\": \"18825454079\",         电话号码\n      \"is_guarantee\": 0                 担保申请人 1是 0否\n      \"is_seller_str\": \"买方\",\n      \"is_comborrower_str\": \"买方共同借款人\",     所属角色\n      \"ctype_str\": \"个人\",                      卖方类型\n      \"certtype_str\": \"身份证\"                    证件类型\n      },\n      {\n      \"cname\": \"张三\",\n      \"ctype\": 1,\n      \"certtype\": 1,\n      \"certcode\": \"11111122322\",\n      \"mobile\": \"18825454079\",\n      \"is_guarantee\": 0\n      }\n      ],\n      \"sqk_info\": {                               首期款信息\n      \"dp_strike_price\": \"5900000.00\",             成交价格\n      \"dp_earnest_money\": \"80000.00\",             定金金额\n      \"dp_supervise_guarantee\": null,            担保公司监管\n      \"dp_supervise_buyer\": null,                 买方本人监管\n      \"dp_supervise_bank\": \"建设银行\",           监管银行\n      \"dp_supervise_bank_branch\": null,\n      \"dp_supervise_date\": \"2018-04-24\",         监管日期\n      \"dp_buy_way\": \"按揭购房\",                  购房方式\n      \"dp_now_mortgage\": \"5.00\"                  现按揭成数\n      },\n      \"mortgage_info\": [                               现按揭信息\n      {\n      \"type\": \"NOW\",\n      \"mortgage_type\": 1,\n      \"money\": \"900000.00\",                     现按揭金额\n      \"organization_type\": \"1\",\n      \"organization\": \"建设银行-深圳振兴支行\",     现按揭机构\n      \"mortgage_type_str\": \"公积金贷款\",           现按揭类型\n      \"organization_type_str\": \"银行\"         现按揭机构类型\n      },\n      {\n      \"type\": \"NOW\",\n      \"mortgage_type\": 2,\n      \"money\": \"2050000.00\",\n      \"organization_type\": \"1\",\n      \"organization\": \"建设银行-深圳振兴支行\",\n      \"mortgage_type_str\": \"商业贷款\",\n      \"organization_type_str\": \"银行\"\n      }\n      ],\n      \"preliminary_question\": [    风控初审问题汇总\n      {\n      \"describe\": \"呵呵456\",     问题描述\n      \"status\": 0               是否解决  0未解决 1已经解决\n      },\n      {\n      \"describe\": \"呵呵帅那个帅789\",\n      \"status\": 0\n      }\n      ],\n      \"needing_attention\": [   风控提醒注意事项\n      {\n      \"process_name\": \"收到公司的\",    来源\n      \"item\": \"啥打法是否\"             注意事项\n      },\n      {\n      \"process_name\": \"测试\",\n      \"item\": \"测试注意事项\"\n      }\n      ],\n      \"reimbursement_info\": [   银行账户信息\n      {\n      \"bankaccount\": \"张三\",   银行户名\n      \"accounttype\": 1,        账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它 6公司个人账户\n      \"bankcard\": \"111111\",    银行卡号\n      \"openbank\": \"中国银行\"    开户银行\n      \"type\": 2,\n      \"verify_card_status\": 0,\n      \"type_text\": \"尾款卡\",                  账号用途\n      \"verify_card_status_text\": \"已完成\",    核卡状态\n      \"accounttype_str\": \"卖方\"               账号归属\n      },\n      {\n      \"bankaccount\": \"李四\",\n      \"accounttype\": 5,\n      \"bankcard\": \"111\",\n      \"openbank\": \"工商银行\"\n      }\n      ],\n\n      \"advancemoney_info\": [    垫资费计算\n      {\n      \"advance_money\": \"650000.00\",     垫资金额\n      \"advance_day\": 30,               垫资天数\n      \"advance_rate\": 0.5,             垫资费率\n      \"advance_fee\": \"97500.0\",        垫资费\n      \"remark\": null,                  备注\n      \"id\": 115\n      }\n      ],\n\n      \"cost_account\":{     预收费用信息 与 实际费用入账\n      \"ac_guarantee_fee\": \"1000.00\",   实收担保费\n      \"ac_fee\": \"-15.00\",              手续费\n      \"ac_self_financing\": \"30.00\",    自筹金额(实际费用入账)\n      \"short_loan_interest\": \"-12.30\",   短贷利息\n      \"return_money\": \"12.50\",           赎楼返还款\n      \"default_interest\": \"0.00\",        罚息\n      \"overdue_money\": \"0.00\",           逾期费\n      \"exhibition_fee\": \"1000.00\",      展期费\n      \"transfer_fee\": \"10000.00\",       过账手续费\n      \"other_money\": \"0.00\"             其他\n      \"notarization\": \"2018-07-26\",     公正日期\n      \"money\": \"46465.00\",              担保金额(额度类)  垫资金额(现金类)\n      \"project_money_date\": null,       预计用款日\n      \"guarantee_per\": 0.35,            担保成数(垫资成数)\n      \"guarantee_rate\": 4,              担保费率\n      \"guarantee_fee\": \"225826007.64\",  预收担保费\n      \"account_per\": 41986.52,          出账成数\n      \"fee\": \"456456.00\",               预收手续费\n      \"self_financing\": \"30.00\",        自筹金额(预收费用信息)\n      \"info_fee\": \"0.00\",               预计信息费\n      \"total_fee\": \"226282463.64\",      预收费用合计\n      \"return_money_mode\": null,\n      \"return_money_amount\": 1425,      回款金额\n      \"turn_into_date\": null,           存入日期\n      \"turn_back_date\": null,           转回日期\n      \"chuzhangsummoney\": 5645650191    预计出账总额\n      \"return_money_mode_str\": \"直接回款\"   回款方式\n      },\n      \"lend_books\": [    银行放款入账\n      {\n      \" loan_money\": \"56786.00\",             放款金额\n      \"lender_object\": \"中国银行\",           放款银行\n      \"receivable_account\": \"中国银行账户\",    收款账户\n      \"into_money_time\": \"2019-11-03\",        到账时间\n      \"remark\": \"法国红酒狂欢节\",             备注说明\n      \"operation_name\": \"杜欣\"                入账人员\n      },\n      {\n      \" loan_money\": \"123456.00\",\n      \"lender_object\": \"中国银行\",\n      \"receivable_account\": \"中国银行账户\",\n      \"into_money_time\": \"2019-11-02\",\n      \"remark\": \"啊是的范德萨\",\n      \"operation_name\": \"杜欣\"\n      }\n      ],\n      \"arrears_info\": [    欠款及预计出账金额\n      {\n      \"ransom_status_text\": \"已完成\",       赎楼状态\n      \"ransomer\": \"朱碧莲\",         赎楼员\n      \"organization\": \"银行\",      欠款机构名称\n      \"interest_balance\": \"111111.11\",    欠款金额\n      \"mortgage_type_name\": \"商业贷款\",   欠款类型\n      \"accumulation_fund\": \"2.00\"         预计出账金额\n      }\n      {\n      \"organization\": \"银行\",\n      \"interest_balance\": \"111111.11\",\n      \"mortgage_type_name\": \"公积金贷款\",\n      \"accumulation_fund\": \"2.00\"\n      }\n      ],\n      \"fund_channel\": [                 资金渠道信息\n      {\n      \"fund_channel_name\": \"华安\",      资金渠道\n      \"money\": \"2000000.00\",             申请金额\n      \"actual_account_money\": \"2000000.00\",    实际入账金额\n      \"is_loan_finish\": 1,\n      \"trust_contract_num\": \"25421155\",         信托合同号\n      \"loan_day\": 5                             借款天数\n      }\n      ]\n      \"status_info\": {        各种需要用到的其他字段\n      \"guarantee_fee_status\": 2,     （担保费）收费状态 1未收齐 2已收齐\n      \"loan_money_status\": 1,         银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理\n      \"instruct_status\": 3,           主表指令状态（1待申请 2待发送 3已发送）\n      \"is_loan_finish\": 1,             银行放款是否完成 0未完成 1已完成\n      \"loan_money\": \"4200000.00\",      银行放款入账(实收金额总计)  资金渠道信息(渠道实际入账总计)\n      \"com_loan_money\": null,         公司放款金额\n      \"guarantee_fee\": 14526          垫资费计算(垫资费总计)\n      \"is_comborrower_sell\": 1       是否卖方有共同借款人 0否 1是\n      \"chile_instruct_status\"  3     子单指令状态（资金渠道表指令状态）\n      \"is_dispatch\": 1,               是否派单 0未派单 1已派单 2退回\n      \"endowmentsum\": 120000          资金渠道信息(垫资总计)\n      }\n      }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/instructionHasList",
    "title": "已发送指令列表[admin/Financial/instructionHasList]",
    "version": "1.0.0",
    "name": "instructionHasList",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/instructionHasList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "instruct_status",
            "description": "<p>指令状态（1待申请 2待发送 3已发送）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_lend",
            "description": "<p>是否放款（1是 2否）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/instructionList",
    "title": "待发送指令列表[admin/Financial/instructionList]",
    "version": "1.0.0",
    "name": "instructionList",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/instructionList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "instruct_status",
            "description": "<p>指令状态（1待申请 2待发送 3已发送）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_lend",
            "description": "<p>是否放款（1是 2否）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"total\": 2,\n      \"per_page\": 20,\n      \"current_page\": 1,\n      \"last_page\": 1,\n      \"data\": [\n          {\n          \"order_sn\": \"JYDB2018050096\",\n          \"finance_sn\": \"100000047\",\n          \"type\": \"JYDB\",\n          \"name\": \"夏丽平\",\n          \"estate_name\": \"国际新城\",\n          \"estate_owner\": null,\n          \"instruct_status\": 1,\n          \"is_loan_finish\": 1\n      \"dp_redeem_bank\": \"工商银行\",        赎楼短贷银行\n      \"dp_redeem_bank_branch\": \"深圳宝安支行\",    赎楼短贷银行支行\n      \"organization\": \"中国银行-深圳腾龙支行\",      赎楼银行\n      \"mortgage_sum\": [                             所有的赎楼银行\n      \"中国银行-深圳腾龙支行\",\n      \"中国银行-深圳腾龙支行\"\n      ]\n          },\n          {\n          \"order_sn\": \"JYDB2018050095\",\n          \"finance_sn\": \"100000047\",\n          \"type\": \"JYDB\",\n          \"name\": \"夏丽平\",\n          \"estate_name\": \"国际新城\",\n          \"estate_owner\": null,\n          \"instruct_status\": 3,\n          \"is_loan_finish\": 1,\n          }\n      ]\n  }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "total",
            "description": "<p>总条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示的条数</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "current_page",
            "description": "<p>当前页</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "last_page",
            "description": "<p>总页数</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "instruct_status",
            "description": "<p>指令状态（1待申请 2待发送 3已发送）</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_loan_finish",
            "description": "<p>是否放款  0否  1是</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/instructionsSend",
    "title": "指令发送[admin/Financial/instructionsSend]",
    "version": "1.0.0",
    "name": "instructionsSend",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/instructionsSend"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1申请发送 2撤回发送 3确认放款 4确认发送</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/isCollected",
    "title": "担保费是否已收齐[admin/Financial/isCollected]",
    "version": "1.0.0",
    "name": "isCollected",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/isCollected"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>是否收齐 1已收齐 2未收齐</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/isLoanFinish",
    "title": "银行放款入账是否已收齐[admin/Financial/isLoanFinish]",
    "version": "1.0.0",
    "name": "isLoanFinish",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/isLoanFinish"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>是否收齐 1已收齐 2未收齐</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/paymentAccount",
    "title": "公司收款账户[admin/Financial/paymentAccount]",
    "version": "1.0.0",
    "name": "paymentAccount",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/paymentAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>1 获取银行放款入账收款账户  2 获取渠道放款收款账户</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_card_id",
            "description": "<p>收款账户id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>收款账户</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/showBankLendDetail",
    "title": "银行放款入账流水明细[admin/Financial/showBankLendDetail]",
    "version": "1.0.0",
    "name": "showBankLendDetail",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/showBankLendDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n      \"code\": 1,\n      \"msg\": \"操作成功\",\n       data\": {\n          \"orderinfo\": {\n              \"order_sn\": \"JYDB2018050137123456\",\n              \"type\": \"JYDB\",\n              \"name\": \"夏丽平\",\n              \"deptname\": \"财务中心\",\n              \"finance_sn\": \"100000048\",\n              \"guarantee_money\": \"2.00\",\n              \"loan_money\": \"180265.00\",\n              \"is_loan_finish\": 0,\n              \"loan_money_status\": 1,\n              \"chuzhang_money\": \"4.00\",\n              \"dp_redeem_bank\": \"农业\"\n            },\n        \"BankLendInfo\": [\n              {\n              \"loan_money\": \"56786.00\",\n              \"lender_object\": \"中国银行\",\n              \"receivable_account\": \"中国银行账户\",\n              \"into_money_time\": \"2019-11-03\",\n              \"remark\": \"法国红酒狂欢节\",\n              \"operation_name\": \"杜欣\"\n              },\n              {\n              \"loan_money\": \"123456.00\",\n              \"lender_object\": \"中国银行\",\n              \"receivable_account\": \"中国银行账户\",\n              \"into_money_time\": \"2019-11-02\",\n              \"remark\": \"啊是的范德萨\",\n              \"operation_name\": \"杜欣\"\n              }\n           ]\n       }\n  }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>所在部门</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "guarantee_money",
            "description": "<p>担保金额</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "loan_money",
            "description": "<p>实收金额总计(银行放款金额总计)</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "chuzhang_money",
            "description": "<p>出账金额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "loan_money_status",
            "description": "<p>入账状态 1待入账 2待复核 3已复核 4驳回待处理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_loan_finish",
            "description": "<p>银行放款是否完成 0未完成 1已完成</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "dp_redeem_bank",
            "description": "<p>放款银行(新增入账流水表单里面的放款银行)</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "lender_object",
            "description": "<p>放款银行(流水明细)</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "receivable_account",
            "description": "<p>收款账户</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "into_money_time",
            "description": "<p>到账时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注说明</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "operation_name",
            "description": "<p>入账人员</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Financial/showBooksDetail",
    "title": "财务入账流水明细[admin/Financial/showBooksDetail]",
    "version": "1.0.0",
    "name": "showBooksDetail",
    "group": "Financial",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Financial/showBooksDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n      \"code\": 1,\n      \"msg\": \"操作成功\",\n       data\": {\n          \"orderinfo\": {\n              \"order_sn\": \"JYDB2018050137123456\",\n              \"type\": \"JYDB\",\n              \"name\": \"夏丽平\",\n              \"deptname\": \"财务中心\",\n              \"finance_sn\": \"100000048\",\n              \"self_financing\": \"2.00\",\n              \"guarantee_fee\": \"2.00\",\n              \"fee\": \"2.00\",\n              \"guarantee_fee_status\": 2,\n              \"receivable_amount\": 4,\n              \"shiShouMoney\": 3665.1,\n              \"danBaoMoney\": 3500\n            },\n          \"booksWaterInfo\": [\n              {\n              \"total_money\": \"1634.70\",\n              \"remark\": \"测试测试测试测试测试\",\n              \"create_time\": \"2018-05-10 10:34:30\",\n              \"operation_name\": \"杜欣\",\n              \"arrinfo\": [\n                  {\n                  \"names\": \"担保费\",\n                  \"money\": \"1500.00\"\n                  },\n                  {\n                  \"names\": \"手续费\",\n                  \"money\": \"-13.50\"\n                  },\n                  {\n                  \"names\": \"自筹金额\",\n                  \"money\": \"100.50\"\n                  },\n                  {\n                  \"names\": \"短贷利息\",\n                  \"money\": \"200.30\"\n                  },\n                  {\n                  \"names\": \"赎楼返还款\",\n                  \"money\": \"-152.60\"\n                  }\n                ]\n              },\n              {\n              \"total_money\": \"1015.20\",\n              \"remark\": \"测试测试测试测试测试从\",\n              \"create_time\": \"2018-05-10 10:28:58\",\n              \"operation_name\": \"杜欣\",\n              \"arrinfo\": [\n                  {\n                  \"names\": \"担保费\",\n                  \"money\": \"1000.00\"\n                  },\n                  {\n                  \"names\": \"手续费\",\n                  \"money\": \"-15.00\"\n                  },\n                  {\n                  \"names\": \"自筹金额\",\n                  \"money\": \"30.00\"\n                  },\n                  {\n                  \"names\": \"短贷利息\",\n                  \"money\": \"-12.30\"\n                  },\n                  {\n                  \"names\": \"赎楼返还款\",\n                  \"money\": \"12.50\"\n                  }\n                ]\n              },\n           ]\n       }\n  }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>所在部门</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "self_financing",
            "description": "<p>自筹金额</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "guarantee_fee",
            "description": "<p>应收担保费</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "fee",
            "description": "<p>应收手续费</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "guarantee_fee_status",
            "description": "<p>担保费是否收齐 1未收齐 2已收齐</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "receivable_amount",
            "description": "<p>应收金额</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "shiShouMoney",
            "description": "<p>实收金额总计</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "danBaoMoney",
            "description": "<p>担保费总计</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "total_money",
            "description": "<p>入账金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注说明</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "cost_time",
            "description": "<p>入账时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>操作时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "operation_name",
            "description": "<p>操作人</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "names",
            "description": "<p>费用项目</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>费用金额</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Financial.php",
    "groupTitle": "Financial"
  },
  {
    "type": "post",
    "url": "admin/Foreclo/caiwuInfo",
    "title": "财务审核详情页[admin/Foreclo/caiwuInfo]",
    "version": "1.0.0",
    "name": "caiwuInfo",
    "group": "Foreclo",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclo/caiwuInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼派单表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n    \"basic_information\": {                      基本信息\n    \"order_sn\": \"JYDB2018050137123456\",    业务单号\n    \"type\": \"JYDB\",        业务类型\n    \"finance_sn\": \"100000048\",      财务序号\n    \"order_source\": 1,\n    \"source_info\": \"万科地产\",          订单来源\n    \"order_source_str\": \"合作中介\",     来源机构\n    \"financing_manager_name\": \"夏丽平\",    理财经理\n    \"dept_manager_name\": \"杜欣\",           部门经理\n    \"deptname\": \"总经办\"                   所属部门\n    },\n    \"estate_info\": [   房产信息\n    {\n    \"estate_name\": \"国际新城一栋\",                  房产名称\n    \"estate_region\": \"深圳市|罗湖区|桂园街道\",      所属城区\n    \"estate_area\": 70,                             房产面积\n    \"estate_certtype\": 1,                          产证类型\n    \"estate_certnum\": 11111,                       产证编码\n    \"house_type\": 1                                房产类型 1分户 2分栋\n    },\n    {\n    \"estate_name\": \"国际新城一栋\",\n    \"estate_district\": \"440303\",\n    \"estate_area\": 70,\n    \"estate_certtype\": 1,\n    \"estate_certnum\": 11111,\n    \"house_type\": 1\n    }\n    ],\n    \"seller_info\": [    买方信息(is_seller = 1 && is_comborrower = 0) 买方共同借款人(is_seller = 1 && is_comborrower = 1)\n    {               卖方信息(is_seller = 2 && is_comborrower = 0) 卖方共同借款人(is_seller = 2 && is_comborrower = 1)\n    \"is_seller\": 2,\n    \"is_comborrower\": 0,           共同借款人属性 0借款人 1共同借款人\n    \"cname\": \"张三\",                 卖方姓名\n    \"ctype\": 1,                      卖方类型 1个人 2企业\n    \"certtype\": 1,                   证件类型\n    \"certcode\": \"11111122322\",       证件号码\n    \"mobile\": \"18825454079\",         电话号码\n    \"is_guarantee\": 0                 担保申请人 1是 0否\n    \"is_guarantee_str\": 0                 担保申请人 是   否\n    \"is_seller_str\": \"买方\",\n    \"is_comborrower_str\": \"买方共同借款人\",     所属角色\n    \"ctype_str\": \"个人\",                      卖方类型\n    \"certtype_str\": \"身份证\"                    证件类型\n    },\n    {\n    \"cname\": \"张三\",\n    \"ctype\": 1,\n    \"certtype\": 1,\n    \"certcode\": \"11111122322\",\n    \"mobile\": \"18825454079\",\n    \"is_guarantee\": 0\n    }\n    ],\n    \"sqk_info\": {                               首期款信息\n        \"dp_strike_price\": \"5900000.00\",             成交价格\n        \"dp_earnest_money\": \"80000.00\",             定金金额\n        \"dp_supervise_guarantee\": null,            担保公司监管\n        \"dp_supervise_buyer\": null,                 买方本人监管\n        \"dp_supervise_bank\": \"建设银行\",           监管银行\n        \"dp_supervise_bank_branch\": null,\n        \"dp_supervise_date\": \"2018-04-24\",         监管日期\n        \"dp_buy_way\": \"按揭购房\",                  购房方式\n        \"dp_now_mortgage\": \"5.00\"                  现按揭成数\n        },\n    \"mortgage_info\": [                               现按揭信息\n            {\n            \"type\": \"NOW\",\n            \"mortgage_type\": 1,\n            \"money\": \"900000.00\",                     现按揭金额\n            \"organization_type\": \"1\",\n            \"organization\": \"建设银行-深圳振兴支行\",     现按揭机构\n            \"mortgage_type_str\": \"公积金贷款\",           现按揭类型\n            \"organization_type_str\": \"银行\"         现按揭机构类型\n            },\n            {\n            \"type\": \"NOW\",\n            \"mortgage_type\": 2,\n            \"money\": \"2050000.00\",\n            \"organization_type\": \"1\",\n            \"organization\": \"建设银行-深圳振兴支行\",\n            \"mortgage_type_str\": \"商业贷款\",\n            \"organization_type_str\": \"银行\"\n            }\n        ],\n    \"preliminary_question\": [    风控初审问题汇总\n    {\n    \"describe\": \"呵呵456\",     问题描述\n    \"status\": 0               是否解决  0未解决 1已经解决\n    },\n    {\n    \"describe\": \"呵呵帅那个帅789\",\n    \"status\": 0\n    }\n    ],\n    \"needing_attention\": [   风控提醒注意事项\n    {\n    \"process_name\": \"收到公司的\",    来源\n    \"item\": \"啥打法是否\"             注意事项\n    },\n    {\n    \"process_name\": \"测试\",\n    \"item\": \"测试注意事项\"\n    }\n    ],\n    \"reimbursement_info\": [   银行账户信息\n    {\n    \"bankaccount\": \"张三\",   银行户名\n    \"accounttype\": 1,        账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它 6公司个人账户\n    \"bankcard\": \"111111\",    银行卡号\n    \"openbank\": \"中国银行\"    开户银行\n    \"type\": 2,\n    \"verify_card_status\": 0,\n    \"type_text\": \"尾款卡\",                  账号用途\n    \"verify_card_status_text\": \"已完成\",    核卡状态\n    \"accounttype_str\": \"卖方\"               账号归属\n    },\n    {\n    \"bankaccount\": \"李四\",\n    \"accounttype\": 5,\n    \"bankcard\": \"111\",\n    \"openbank\": \"工商银行\"\n    }\n    ],\n\n    \"advancemoney_info\": [    垫资费计算\n    {\n    \"advance_money\": \"650000.00\",     垫资金额\n    \"advance_day\": 30,               垫资天数\n    \"advance_rate\": 0.5,             垫资费率\n    \"advance_fee\": \"97500.0\",        垫资费\n    \"remark\": null,                  备注\n    \"id\": 115\n    }\n    ],\n\n    \"cost_account\":{     预收费用信息 与 实际费用入账\n    \"ac_guarantee_fee\": \"1000.00\",   实收担保费\n    \"ac_fee\": \"-15.00\",              手续费\n    \"ac_self_financing\": \"30.00\",    自筹金额(实际费用入账)\n    \"short_loan_interest\": \"-12.30\",   短贷利息\n    \"return_money\": \"12.50\",           赎楼返还款\n    \"default_interest\": \"0.00\",        罚息\n    \"overdue_money\": \"0.00\",           逾期费\n    \"exhibition_fee\": \"1000.00\",      展期费\n    \"transfer_fee\": \"10000.00\",       过账手续费\n    \"other_money\": \"0.00\"             其他\n    \"notarization\": \"2018-07-26\",     公正日期\n    \"money\": \"46465.00\",              担保金额(额度类)  垫资金额(现金类)\n    \"project_money_date\": null,       预计用款日\n    \"guarantee_per\": 0.35,            担保成数(垫资成数)\n    \"guarantee_rate\": 4,              担保费率\n    \"guarantee_fee\": \"225826007.64\",  预收担保费\n    \"account_per\": 41986.52,          出账成数\n    \"fee\": \"456456.00\",               预收手续费\n    \"self_financing\": \"30.00\",        自筹金额(预收费用信息)\n    \"info_fee\": \"0.00\",               预计信息费\n    \"total_fee\": \"226282463.64\",      预收费用合计\n    \"return_money_mode\": null,\n    \"return_money_amount\": 1425,      回款金额\n     \"turn_into_date\": null,           存入日期\n    \"turn_back_date\": null,           转回日期\n    \"chuzhangsummoney\": 5645650191    预计出账总额\n    \"return_money_mode_str\": \"直接回款\"   回款方式\n    },\n    \"lend_books\": [    银行放款入账\n    {\n    \" loan_money\": \"56786.00\",             放款金额\n    \"lender_object\": \"中国银行\",           放款银行\n    \"receivable_account\": \"中国银行账户\",    收款账户\n    \"into_money_time\": \"2019-11-03\",        到账时间\n    \"remark\": \"法国红酒狂欢节\",             备注说明\n    \"operation_name\": \"杜欣\"                入账人员\n    },\n    {\n    \" loan_money\": \"123456.00\",\n    \"lender_object\": \"中国银行\",\n    \"receivable_account\": \"中国银行账户\",\n    \"into_money_time\": \"2019-11-02\",\n    \"remark\": \"啊是的范德萨\",\n    \"operation_name\": \"杜欣\"\n    }\n    ],\n    \"arrears_info\": [    欠款及预计出账金额\n    {\n    \"ransom_status_text\": \"已完成\",       赎楼状态\n    \"ransomer\": \"朱碧莲\",         赎楼员\n    \"organization\": \"银行\",      欠款机构名称\n    \"interest_balance\": \"111111.11\",    欠款金额\n    \"mortgage_type_name\": \"商业贷款\",   欠款类型\n    \"accumulation_fund\": \"2.00\"         预计出账金额\n    }\n    {\n    \"organization\": \"银行\",\n    \"interest_balance\": \"111111.11\",\n    \"mortgage_type_name\": \"公积金贷款\",\n    \"accumulation_fund\": \"2.00\"\n    }\n    ],\n    \"fund_channel\": [                 资金渠道信息\n    {\n    \"fund_channel_name\": \"华安\",      资金渠道\n    \"money\": \"2000000.00\",             申请金额\n    \"actual_account_money\": \"2000000.00\",    实际入账金额\n    \"is_loan_finish\": 1,\n    \"trust_contract_num\": \"25421155\",         信托合同号\n    \"loan_day\": 5                             借款天数\n    }\n    \"status_info\": {        各种需要用到的其他字段\n    \"guarantee_fee_status\": 2,     （担保费）收费状态 1未收齐 2已收齐\n    \"loan_money_status\": 1,         银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理\n    \"instruct_status\": 3,           主表指令状态（1待申请 2待发送 3已发送）\n    \"is_loan_finish\": 1,             银行放款是否完成 0未完成 1已完成\n    \"loan_money\": \"4200000.00\",      银行放款入账(实收金额总计)  资金渠道信息(渠道实际入账总计)\n    \"com_loan_money\": null,\n    \"guarantee_fee\": 14526          垫资费计算(垫资费总计)\n    \"is_comborrower_sell\": 1       是否卖方有共同借款人 0否 1是\n    \"chile_instruct_status\"  3     子单指令状态（资金渠道表指令状态）\n    \"is_dispatch\": 1,               是否派单 0未派单 1已派单 2退回\n     \"endowmentsum\": 120000          资金渠道信息(垫资总计)\n    }\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Foreclo.php",
    "groupTitle": "Foreclo"
  },
  {
    "type": "post",
    "url": "admin/Foreclo/dataList",
    "title": "资料入架待入架列表[admin/Foreclo/dataList ]",
    "version": "1.0.0",
    "name": "dataList",
    "group": "Foreclo",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclo/dataList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_combined_loan",
            "description": "<p>是否组合贷款（0否 1是）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n\"code\": 1,\n\"msg\": \"操作成功\",\n\"data\": {\n\"total\": 19,             总条数\n\"per_page\": \"2\",         每页显示的条数\n\"current_page\": 1,       当前页\n\"last_page\": 10,         总页数\n\"data\": [\n{\n\"order_sn\": \"JYDB2018050137123456\",    业务单号\n\"type\": \"JYDB\",                        订单类型\n\"create_time\": \"2018-05-09 17:04:06\",  报单时间\n\"name\": \"夏丽平\",                        理财经理\n\"estate_name\": \"国际新城一栋\",           房产名称\n\"estate_owner\": \"张三,李四\",             业主姓名\n\"is_combined_loan\": 1,                   是否组合贷 1是 0否\n\"order_status\": \"待注销过户\",             订单状态\n\"estate_ecity_name\": \"深圳市\",            城市\n\"estate_district_name\": \"罗湖区\",         城区\n\"proc_id\"                                 处理明细表主键id\n\"organization\": [                        赎楼银行\n{\n\"organization\": \"银行\"\n},\n{\n\"organization\": \"银行\"\n},\n{\n\"organization\": \"银行\"\n}\n]\n},\n{\n\"order_sn\": \"JYDB2018050159\",\n\"type\": \"JYDB\",\n\"create_time\": \"2018-05-12 10:15:45\",\n\"name\": \"夏丽平\",\n\"estate_name\": \"国际新城一栋\",\n\"estate_owner\": \"张三,李四\",\n\"is_combined_loan\": null,\n\"order_status\": \"待指派赎楼员\",\n\"estate_ecity_name\": \"深圳市\",\n\"estate_district_name\": \"罗湖区\",\n\"organization\": []\n}\n]\n}\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Foreclo.php",
    "groupTitle": "Foreclo"
  },
  {
    "type": "post",
    "url": "admin/Foreclo/finauditList",
    "title": "财务待审核列表[admin/Foreclo/finauditList ]",
    "version": "1.0.0",
    "name": "finauditList",
    "group": "Foreclo",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclo/finauditList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_type",
            "description": "<p>赎楼类型 1公积金贷款 2商业贷款 3装修贷/消费贷</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_status",
            "description": "<p>当前状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n\"code\": 1,\n\"msg\": \"操作成功\",\n\"data\": {\n\"total\": 19,             总条数\n\"per_page\": \"2\",         每页显示的条数\n\"current_page\": 1,       当前页\n\"last_page\": 10,         总页数\n\"data\": [\n{\n\"id\": 39,                        赎楼派单表主键id\n\"order_sn\": \"JYDB2018050285\",    业务单号\n\"ransom_bank\": \"农业银行\",        赎楼银行\n\"ransom_status\": 202,\n\"ransom_type\": 1,\n\"ransomer\": \"李四\",               赎楼员\n\"create_time\": \"2018-05-22\",      派单日期\n\"type\": \"FJYDB\",                  订单类型\n\"finance_sn\": \"100000104\",        财务序号\n\"financing_manager_id\": 17,\n\"estate_name\": \"名称1阁栋名称1010\",      房产名称\n\"estate_owner\": \"张三,测试第二次\",       业主姓名\n\"ransom_status_text\": \"待赎楼经理审批\",   当前状态\n\"ransom_type_text\": \"公积金贷款\",         赎楼类型\n\"type_text\": \"非交易担保\",                订单类型\n\"financing_manager\": \"杨亚丽\"             理财经理\n},\n{\n\"id\": 38,\n\"order_sn\": \"JYDB2018050285\",\n\"ransom_bank\": \"中国银行\",\n\"ransom_status\": 14,\n\"ransom_type\": 1,\n\"ransomer\": \"张三\",\n\"create_time\": \"2018-05-22\",\n\"type\": \"FJYDB\",\n\"finance_sn\": \"100000104\",\n\"financing_manager_id\": 17,\n\"estate_name\": \"名称1阁栋名称1010\",\n\"estate_owner\": \"张三,测试第二次\",\n\"ransom_status_text\": \"待赎楼经理审批\",\n\"ransom_type_text\": \"公积金贷款\",\n\"type_text\": \"非交易担保\",\n\"financing_manager\": \"杨亚丽\"\n\"proc_id\": 192\n}\n]\n}\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Foreclo.php",
    "groupTitle": "Foreclo"
  },
  {
    "type": "post",
    "url": "admin/Foreclo/foreProcList",
    "title": "财务赎楼流程列表[admin/Foreclo/foreProcList ]",
    "version": "1.0.0",
    "name": "foreProcList",
    "group": "Foreclo",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclo/foreProcList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "dispatch_id",
            "description": "<p>赎楼派单表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n\"code\": 1,\n\"msg\": \"操作成功\",\n\"data\": {\n\"total\": 19,             总条数\n\"per_page\": \"2\",         每页显示的条数\n\"current_page\": 1,       当前页\n\"last_page\": 10,         总页数\n\"data\": [\n{\n\"create_time\": \"2018-05-25 15:55:31\",    时间\n\"operate\": \"待业务报单\",                  操作\n\"operate_node\": \"待业务报单\",             操作节点\n\"operate_det\": \"创建订单\",               操作详情\n\"name\": \"管理员\"                         操作人员\n},\n{\n\"create_time\": \"2018-05-25 11:56:07\",\n\"operate\": \"风控审批流\",\n\"operate_node\": \"风控部门提交审批\",\n\"operate_det\": \"刘林4:审批通过,流向=>待审查助理审批\",\n\"name\": \"刘林4\"\n}\n]\n}\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Foreclo.php",
    "groupTitle": "Foreclo"
  },
  {
    "type": "post",
    "url": "admin/Foreclo/hasBeenList",
    "title": "资料入架已入架列表[admin/Foreclo/hasBeenList ]",
    "version": "1.0.0",
    "name": "hasBeenList",
    "group": "Foreclo",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclo/hasBeenList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_combined_loan",
            "description": "<p>是否组合贷款（0否 1是）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n\"code\": 1,\n\"msg\": \"操作成功\",\n\"data\": {\n\"total\": 19,             总条数\n\"per_page\": \"2\",         每页显示的条数\n\"current_page\": 1,       当前页\n\"last_page\": 10,         总页数\n\"data\": [\n{\n\"order_sn\": \"JYDB2018050137123456\",    业务单号\n\"type\": \"JYDB\",                        订单类型\n\"create_time\": \"2018-05-09 17:04:06\",  报单时间\n\"name\": \"夏丽平\",                        理财经理\n\"estate_name\": \"国际新城一栋\",           房产名称\n\"estate_owner\": \"张三,李四\",             业主姓名\n\"is_combined_loan\": 1,                   是否组合贷 1是 0否\n\"order_status\": \"待注销过户\",             订单状态\n\"estate_ecity_name\": \"深圳市\",            城市\n\"estate_district_name\": \"罗湖区\",         城区\n\"proc_id\"                                 处理明细表主键id\n\"organization\": [                        赎楼银行\n{\n\"organization\": \"银行\"\n},\n{\n\"organization\": \"银行\"\n},\n{\n\"organization\": \"银行\"\n}\n]\n},\n{\n\"order_sn\": \"JYDB2018050159\",\n\"type\": \"JYDB\",\n\"create_time\": \"2018-05-12 10:15:45\",\n\"name\": \"夏丽平\",\n\"estate_name\": \"国际新城一栋\",\n\"estate_owner\": \"张三,李四\",\n\"is_combined_loan\": null,\n\"order_status\": \"待指派赎楼员\",\n\"estate_ecity_name\": \"深圳市\",\n\"estate_district_name\": \"罗湖区\",\n\"organization\": []\n}\n]\n}\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Foreclo.php",
    "groupTitle": "Foreclo"
  },
  {
    "type": "post",
    "url": "admin/Foreclo/haveOnList",
    "title": "财务已审核列表[admin/Foreclo/haveOnList ]",
    "version": "1.0.0",
    "name": "haveOnList",
    "group": "Foreclo",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclo/haveOnList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_type",
            "description": "<p>赎楼类型 1公积金贷款 2商业贷款 3装修贷/消费贷</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_status",
            "description": "<p>当前状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclo.php",
    "groupTitle": "Foreclo"
  },
  {
    "type": "post",
    "url": "admin/Foreclo/submitFinancial",
    "title": "财务审核提交审批[admin/Foreclo/submitFinancial]",
    "version": "1.0.0",
    "name": "submitFinancial",
    "group": "Foreclo",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclo/submitFinancial"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_approval",
            "description": "<p>审批结果 1通过 2驳回</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>驳回原因</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "dispatch_id",
            "description": "<p>赎楼派单表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_status",
            "description": "<p>子订单状态对应的code</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclo.php",
    "groupTitle": "Foreclo"
  },
  {
    "type": "post",
    "url": "admin/Foreclosure/applyAccount",
    "title": "申请赎楼出账[admin/Foreclosure/applyAccount]",
    "version": "1.0.0",
    "name": "applyAccount",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/applyAccount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼派单表id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "item",
            "description": "<p>出账类型（1.当前账目类型 2.银行罚息）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "money",
            "description": "<p>出账金额</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "way",
            "description": "<p>出账方式(1现金 2支票)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_prestore",
            "description": "<p>是否预存（现金）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "prestore_day",
            "description": "<p>预存天数（现金预存单）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_type",
            "description": "<p>账户类型（1.跟单员账户，2卖方账户，3卖方共同借款人账户,4.买方账户，5公司个人账户）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "out_bank_card",
            "description": "<p>收款卡号（现金）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "out_bank",
            "description": "<p>收款银行（现金）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "out_bank_account",
            "description": "<p>收款账户（现金）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "bank",
            "description": "<p>支票银行（支票）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cheque_num",
            "description": "<p>支票号码（支票）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cid",
            "description": "<p>支票id（支票）</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "post",
    "url": "admin/Foreclosure/backOrder",
    "title": "退回派单[admin/Foreclosure/backOrder]",
    "version": "1.0.0",
    "name": "backOrder",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/backOrder"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼派单表id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "operate_reason",
            "description": "<p>退单原因</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "post",
    "url": "admin/Foreclosure/changeRomsomer",
    "title": "改派赎楼员[admin/Foreclosure/changeRomsomer]",
    "version": "1.0.0",
    "name": "changeRomsomer",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/changeRomsomer"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼派单表id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransome_id",
            "description": "<p>赎楼员id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "ransomer",
            "description": "<p>赎楼员姓名</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "get",
    "url": "admin/Foreclosure/completeRomsom",
    "title": "完成赎楼[admin/Foreclosure/completeRomsom]",
    "version": "1.0.0",
    "name": "completeRomsom",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/completeRomsom"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼派单表id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "post",
    "url": "admin/Foreclosure/determineMoney",
    "title": "确定扣款[admin/Foreclosure/determineMoney]",
    "version": "1.0.0",
    "name": "determineMoney",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/determineMoney"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>出账id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "money",
            "description": "<p>确认扣款金额</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "get",
    "url": "admin/Foreclosure/getOrderreceipt",
    "title": "获取收款账户信息[admin/Foreclosure/getOrderreceipt]",
    "version": "1.0.0",
    "name": "getOrderreceipt",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/getOrderreceipt"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "accounttype",
            "description": "<p>账户类型（1卖方账户，2卖方共同借款人账户,3.买方账户,4买方共同借款人,5其他,6公司个人账户,7第三方账户,8公司账户，9赎楼员账户）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransomer_id",
            "description": "<p>赎楼员id（当选择赎楼员账户时才需要）</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank_account",
            "description": "<p>开户人</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank_card",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "accounttype_text",
            "description": "<p>账户类型（买卖方预留账户的时候才有：1卖方 2卖方共同借款人）</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "get",
    "url": "admin/Foreclosure/getRomsomer",
    "title": "模糊获取赎楼员[admin/Foreclosure/getRomsomer]",
    "version": "1.0.0",
    "name": "getRomsomer",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/getRomsomer"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>赎楼员姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": ""
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "get",
    "url": "admin/Foreclosure/lookdetail",
    "title": "查看详情[admin/Foreclosure/lookdetail]",
    "version": "1.0.0",
    "name": "lookdetail",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/lookdetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼出账</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "money_text",
            "description": "<p>出账金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "way_text",
            "description": "<p>出账方式</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_prestore_text",
            "description": "<p>是否预存(现金)</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "prestore_day",
            "description": "<p>预存天数(现金)</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "account_type",
            "description": "<p>账户类型（现金）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "receipt_text",
            "description": "<p>收款账户（现金）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "cheque_num",
            "description": "<p>支票号码（支票）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>支票银行（支票）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>申请时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "account_status_text",
            "description": "<p>出账状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "outok_time",
            "description": "<p>出账时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "debit_text",
            "description": "<p>出账账户</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "get",
    "url": "admin/Foreclosure/ransomAllOutaccountExport",
    "title": "赎楼出账列表导出[admin/Foreclosure/ransomAllOutaccountExport]",
    "version": "1.0.0",
    "name": "ransomAllOutaccountExport",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/ransomAllOutaccountExport"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_status",
            "description": "<p>赎楼状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_type",
            "description": "<p>赎楼类型（1商业贷款 2公积金贷款 3家装/消费贷）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "get",
    "url": "admin/Foreclosure/ransomDetail",
    "title": "赎楼详情页[admin/Foreclosure/ransomDetail]",
    "version": "1.0.0",
    "name": "ransomDetail",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/ransomDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼派单id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"orderinfo\": {\n      \"estate_name\": [\n      \"东雨田村二期雨山阁13B\"\n      ],\n      \"estate_owner\": \"叶腾飞、滕天磊\",\n      \"type_text\": \"非交易现金\",\n      \"type\": \"TMXJ\",\n      \"finance_sn\": \"100000038\",\n      \"order_sn\": \"TMXJ2018070006\",\n      \"ransom_status_text\": \"已完成\",\n      \"ransom_status\": 207,\n      \"money\": \"132416.00\",\n      \"default_interest\": \"136.00\",\n      \"self_financing\": \"356.00\",\n      \"channel_money\": \"26482.00\",\n      \"company_money\": \"105934.00\",\n      \"can_money\": 132908,\n      \"out_money\": 1200,\n      \"use_money\": 131708\n      },\n      \"dispatch\": {\n      \"ransom_type_text\": \"公积金贷款\",\n      \"ransom_status_text\": \"已完成\",\n      \"ransomer\": \"聂梦\",\n      \"ransomer_id\": 392,\n      \"ransom_bank\": \"建设银行-深圳东湖支行\",\n      \"arrears\": \"10000.00\"\n      },\n      \"debitinfolog\": {\n      \"info\": {\n      \"cut_money\": 1000\n      },\n      \"totlearr\": [\n      {\n      \"id\": 95,\n      \"money\": \"1200.00\",\n      \"cut_status\": 1,\n      \"item_text\": \"银行罚息\",\n      \"cut_money\": \"1000.00\",\n      \"last_money\": 200,\n      \"way_text\": \"现金\",\n      \"is_prestore_text\": \"否\",\n      \"account_type_text\": \"跟单员账户\",\n      \"ransomer\": \"聂梦\",\n      \"create_time\": \"2018-07-26 15:11\",\n      \"account_status\": 5,\n      \"account_status_text\": \"银行已扣款\",\n      \"outok_time\": \"2018-07-26 15:11\"\n      }\n      ]\n      },\n      \"receipt_img\": [],\n      \"checkinfo\": [],\n      \"preliminary_question\": [\n      {\n      \"describe\": \"无\",\n      \"status\": 1\n      }\n      ],\n      \"needing_attention\": [\n      {\n      \"process_name\": \"待部门经理审批\",\n      \"item\": null\n      },\n      {\n      \"process_name\": \"待审查助理审批\",\n      \"item\": null\n      },\n      {\n      \"process_name\": \"待审查员审批\",\n      \"item\": null\n      },\n      {\n      \"process_name\": \"待审查主管审批\",\n      \"item\": null\n      },\n      {\n      \"process_name\": \"待资料入架\",\n      \"item\": null\n      }\n      ],\n      \"reimbursement_info\": [\n      {\n      \"id\": 163,\n      \"bankaccount\": \"章泽雨 \",\n      \"accounttype\": 3,\n      \"bankcard\": \"611023197708149013\",\n      \"openbank\": \"工商银行\",\n      \"verify_card_status\": 0,\n      \"type\": false,\n      \"type_text\": \"\",\n      \"verify_card_status_text\": \"待核卡\",\n      \"accounttype_str\": \"买方\"\n      },\n      {\n      \"id\": 164,\n      \"bankaccount\": \"滕天磊 \",\n      \"accounttype\": 2,\n      \"bankcard\": \"611023197807252673\",\n      \"openbank\": \"工商银行\",\n      \"verify_card_status\": 3,\n      \"type\": [],\n      \"type_text\": \"\",\n      \"verify_card_status_text\": \"已完成\",\n      \"accounttype_str\": \"卖方共同借款人\"\n      },\n      {\n      \"id\": 165,\n      \"bankaccount\": \"邹明哲 \",\n      \"accounttype\": 2,\n      \"bankcard\": \"611023197008222638\",\n      \"openbank\": \"建设银行\",\n      \"verify_card_status\": 3,\n      \"type\": [],\n      \"type_text\": \"\",\n      \"verify_card_status_text\": \"已完成\",\n      \"accounttype_str\": \"卖方共同借款人\"\n      },\n      {\n      \"id\": 166,\n      \"bankaccount\": \"方高俊 \",\n      \"accounttype\": 1,\n      \"bankcard\": \"611023197907164611\",\n      \"openbank\": \"建设银行\",\n      \"verify_card_status\": 3,\n      \"type\": [],\n      \"type_text\": \"\",\n      \"verify_card_status_text\": \"已完成\",\n      \"accounttype_str\": \"卖方\"\n      }\n      ]\n      }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "get",
    "url": "admin/Foreclosure/ransomList",
    "title": "赎楼列表[admin/Foreclosure/ransomList]",
    "version": "1.0.0",
    "name": "ransomList",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/ransomList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_status",
            "description": "<p>赎楼状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_type",
            "description": "<p>赎楼类型（1商业贷款 2公积金贷款 3家装/消费贷）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "finance_sn",
            "description": "<p>财务序号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type_text",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransom_status_text",
            "description": "<p>赎楼状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransom_type_text",
            "description": "<p>赎楼类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransomer",
            "description": "<p>赎楼员</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ransom_bank",
            "description": "<p>赎楼银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>派单时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "financing_manager",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "get",
    "url": "admin/Foreclosure/ransomOutaccountExport",
    "title": "赎楼出账列表导出[admin/Foreclosure/ransomOutaccountExport]",
    "version": "1.0.0",
    "name": "ransomOutaccountExport",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/ransomOutaccountExport"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_status",
            "description": "<p>赎楼状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransom_type",
            "description": "<p>赎楼类型（1商业贷款 2公积金贷款 3家装/消费贷）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "get",
    "url": "admin/Foreclosure/reEditmoney",
    "title": "重新编辑扣款金额[admin/Foreclosure/reEditmoney]",
    "version": "1.0.0",
    "name": "reEditmoney",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/reEditmoney"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>出账id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "money",
            "description": "<p>确认扣款金额</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "post",
    "url": "admin/Foreclosure/determineMoney",
    "title": "上传回执[admin/Foreclosure/uploadReceipt]",
    "version": "1.0.0",
    "name": "uploadReceipt",
    "group": "Foreclosure",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Foreclosure/uploadReceipt"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>赎楼出账表id</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "receipt_img",
            "description": "<p>回执图片id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Foreclosure.php",
    "groupTitle": "Foreclosure"
  },
  {
    "type": "post",
    "url": "admin/FundManagement/addChannel",
    "title": "新增渠道[admin/FundManagement/addChannel]",
    "version": "1.0.0",
    "name": "addChannel",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/addChannel"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>渠道ID(编辑的时候才需要传)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>渠道名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cash_source_id",
            "description": "<p>资金来源id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "cash_source_name",
            "description": "<p>资金来源名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_limit",
            "description": "<p>订单限额</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_interest",
            "description": "<p>利息可用 1是 0否'</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "post",
    "url": "admin/FundManagement/addChannelacount",
    "title": "新增渠道账户[admin/FundManagement/addChannelacount]",
    "version": "1.0.0",
    "name": "addChannelacount",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/addChannelacount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>渠道账户ID(编辑的时候才需要传)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fund_channel_id",
            "description": "<p>渠道id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "account_name",
            "description": "<p>账户名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "credit_quota",
            "description": "<p>授信额度</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "deposit_ratio",
            "description": "<p>保证金比例</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fund_cost",
            "description": "<p>资金成本</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "channel_fee",
            "description": "<p>通道费</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "premium_rate",
            "description": "<p>保费费率</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "finance_advisor_fee",
            "description": "<p>财务顾问费</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "other_fee",
            "description": "<p>其它费用</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "single_limit",
            "description": "<p>单笔限额，0表上没限额</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "borrower_limit",
            "description": "<p>单个借款人限额</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "sign_date",
            "description": "<p>签约日期</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "due_date",
            "description": "<p>到期日期</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "post",
    "url": "admin/FundManagement/addQuota",
    "title": "新增额度[admin/FundManagement/addQuota]",
    "version": "1.0.0",
    "name": "addQuota",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/addQuota"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>银行额度id(编辑的时候才需要传)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>支行</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "bank_id",
            "description": "<p>银行id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "bank_branch_id",
            "description": "<p>支行id</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "business_breed",
            "description": "<p>业务品种[1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "credit_quota",
            "description": "<p>授信额度</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "deposit_ratio",
            "description": "<p>保证金比例</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "paving_deposit",
            "description": "<p>铺地保证金</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "single_limit",
            "description": "<p>单笔上限</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "customeranager",
            "description": "<p>银行经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>银行经理手机</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "sign_date",
            "description": "<p>签约时间</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "due_date",
            "description": "<p>到期时间</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "post",
    "url": "admin/FundManagement/addorcutDeposit",
    "title": "增存保证金[admin/FundManagement/addorcutDeposit]",
    "version": "1.0.0",
    "name": "addorcutDeposit",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/addorcutDeposit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fund_source_id",
            "description": "<p>授信银行id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fund_source",
            "description": "<p>资金来源 （1：银行  2：渠道）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>增解保证金 （1：增存  2解付）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "credit_quota",
            "description": "<p>授信额度</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "enable_quota",
            "description": "<p>启用额度</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "deposit_ratio",
            "description": "<p>保证金比例</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stay_quota",
            "description": "<p>在保金额</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "deposit",
            "description": "<p>保证金金额</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "paving_deposit",
            "description": "<p>铺地保证金</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "money",
            "description": "<p>增付金额</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "get",
    "url": "admin/FundManagement/bankquotaList",
    "title": "银行额度列表[admin/FundManagement/bankquotaList]",
    "version": "1.0.0",
    "name": "bankquotaList",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/bankquotaList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态（1正常 2禁用）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>授信银行id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>授信银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>授信支行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "credit_quota",
            "description": "<p>授信额度</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "enable_quota",
            "description": "<p>启用额度</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "stay_quota",
            "description": "<p>在保额度</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "canuse_quota",
            "description": "<p>可用额度</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deposit_ratio",
            "description": "<p>保证金比例</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deposit",
            "description": "<p>保证金金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "single_limit",
            "description": "<p>单笔上限</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "paving_deposit",
            "description": "<p>铺地保证金</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "due_date",
            "description": "<p>到期日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "status_text",
            "description": "<p>状态（文本）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_ontime",
            "description": "<p>是否过期  1未过期 2过期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "asset_value",
            "description": "<p>净资产金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_overquota",
            "description": "<p>是否超过净资产10倍  1超过 2未超过</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "get",
    "url": "admin/FundManagement/channelDetail",
    "title": "渠道现金详情页[admin/FundManagement/channelDetail]",
    "version": "1.0.0",
    "name": "channelDetail",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/channelDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>授信银行id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "quotainfo",
            "description": "<p>资金来源1银行 2渠道</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n      \"quotainfo\":[{\n      \"bank\" :\"中国银行\",银行\n      \"bank_branch\" :\"深圳腾龙支行\",支行\n      \"business_breed\": [\"1\",\"2\",\"3\"],品种类型\n      \"business_breed_text\": [\"一笔款\",\"二笔款\",\"拍卖款\"],品种类型文本\n      \"credit_quota\": \"6000000\",授信额度\n      \"enable_quota\": \"0.00\",启用额度\n      \"deposit_ratio\": 6.5,保证金比例\n      \"deposit\": \"0.00\",保证金金额\n      \"paving_deposit\": \"600\",铺地保证金\n      \"single_limit\": \"0.00\",单笔上限\n      \"customeranager\": \"银行经理银行经理银行经理\",银行客户经理\n      \"mobile\": \"0798-6666666\",联系电话\n      \"sign_date\": \"2018-07-12\",签约日期\n      \"due_date\": \"2019-07-25\",到期时间\n      \"status\": 1,状态\n      \"status_text\": \"正常\",状态文本\n      }],\n      \"depositLog\":[{\n      \"id\" :\"1\",记录id\n      \"type\" :\"1\",增付\n      \"money\": 600.00,增付金额\n      \"deposit\": \"60600.00\",保证金金额\n      \"enable_quota\": \"2009230.77\",启用额度\n      \"create_uid\": \"1\",用户id\n      \"create_time\": 2018-08-02 14:20,新增时间\n      \"type_text\": \"增存保证金\",增付（文本）\n      \"username\": \"管理员\",用户姓名\n      }],\n      },",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "get",
    "url": "admin/FundManagement/channelcashList",
    "title": "渠道现金列表[admin/FundManagement/channelcashList]",
    "version": "1.0.0",
    "name": "channelcashList",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/channelcashList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态（1正常 2禁用）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>渠道id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>渠道名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "credit_quota_total",
            "description": "<p>授信总额度</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "enable_quota_total",
            "description": "<p>启用（总）额度</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "occupy_quota",
            "description": "<p>占用额度</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "interest",
            "description": "<p>利息金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_interest",
            "description": "<p>利息可用</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "surplus_enable_quota",
            "description": "<p>剩余启用额度</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "forecast_repay_tomorrow",
            "description": "<p>预计明天还款</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "pass_non_lend",
            "description": "<p>已过未放款</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "day_push",
            "description": "<p>当日推送</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "day_lend",
            "description": "<p>当日放款</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deposit_total",
            "description": "<p>保证金总额</p>"
          },
          {
            "group": "Success 200",
            "type": "date",
            "optional": false,
            "field": "due_date",
            "description": "<p>过期时间</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "status_text",
            "description": "<p>状态（文本）</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_interest_text",
            "description": "<p>利息可用(文本)</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_ontime",
            "description": "<p>是否过期  1未过期 2过期</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "get",
    "url": "admin/FundManagement/getchannelInfo",
    "title": "获取渠道现金信息[admin/FundManagement/getchannelInfo]",
    "version": "1.0.0",
    "name": "getchannelInfo",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/getchannelInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>渠道id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>渠道名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "cash_source_id",
            "description": "<p>资金来源id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "cash_source_name",
            "description": "<p>资金来源名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "order_limit",
            "description": "<p>订单限额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_interest",
            "description": "<p>利息可用 1是 0否</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "get",
    "url": "admin/FundManagement/getchannelacountInfo",
    "title": "获取渠道账户信息[admin/FundManagement/getchannelacountInfo]",
    "version": "1.0.0",
    "name": "getchannelacountInfo",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/getchannelacountInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>渠道账户id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "fund_channel_id",
            "description": "<p>渠道id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "account_name",
            "description": "<p>账户名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "credit_quota",
            "description": "<p>授信额度</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "deposit_ratio",
            "description": "<p>保证金比例</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "fund_cost",
            "description": "<p>资金成本</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "channel_fee",
            "description": "<p>通道费</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "premium_rate",
            "description": "<p>保费费率</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "finance_advisor_fee",
            "description": "<p>财务顾问费</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "other_fee",
            "description": "<p>其它费用</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "single_limit",
            "description": "<p>单笔限额，0表上没限额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "borrower_limit",
            "description": "<p>单个借款人限额</p>"
          },
          {
            "group": "Success 200",
            "type": "date",
            "optional": false,
            "field": "sign_date",
            "description": "<p>签约日期</p>"
          },
          {
            "group": "Success 200",
            "type": "date",
            "optional": false,
            "field": "due_date",
            "description": "<p>到期日期</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "get",
    "url": "admin/FundManagement/getquotaInfo",
    "title": "获取银行额度信息[admin/FundManagement/getquotaInfo]",
    "version": "1.0.0",
    "name": "getquotaInfo",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/getquotaInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>授信银行id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank",
            "description": "<p>授信银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bank_branch",
            "description": "<p>授信支行</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "business_breed",
            "description": "<p>业务品种</p>"
          },
          {
            "group": "Success 200",
            "type": "double",
            "optional": false,
            "field": "credit_quota",
            "description": "<p>授信额度</p>"
          },
          {
            "group": "Success 200",
            "type": "double",
            "optional": false,
            "field": "enable_quota",
            "description": "<p>启用额度</p>"
          },
          {
            "group": "Success 200",
            "type": "double",
            "optional": false,
            "field": "deposit_ratio",
            "description": "<p>保证金比例</p>"
          },
          {
            "group": "Success 200",
            "type": "double",
            "optional": false,
            "field": "deposit",
            "description": "<p>保证金金额</p>"
          },
          {
            "group": "Success 200",
            "type": "double",
            "optional": false,
            "field": "stay_quota",
            "description": "<p>在保金额</p>"
          },
          {
            "group": "Success 200",
            "type": "double",
            "optional": false,
            "field": "paving_deposit",
            "description": "<p>铺地保证金</p>"
          },
          {
            "group": "Success 200",
            "type": "double",
            "optional": false,
            "field": "single_limit",
            "description": "<p>单笔上限</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "customeranager",
            "description": "<p>银行客户经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>联系电话</p>"
          },
          {
            "group": "Success 200",
            "type": "date",
            "optional": false,
            "field": "sign_date",
            "description": "<p>签约日期</p>"
          },
          {
            "group": "Success 200",
            "type": "date",
            "optional": false,
            "field": "due_date",
            "description": "<p>到期日期</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "get",
    "url": "admin/FundManagement/quotaDetail",
    "title": "银行额度详情页[admin/FundManagement/quotaDetail]",
    "version": "1.0.0",
    "name": "quotaDetail",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/quotaDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>授信银行id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fund_source",
            "description": "<p>资金来源（1：银行  2渠道）</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n      \"quotainfo\":[{\n      \"bank\" :\"中国银行\",银行\n      \"bank_branch\" :\"深圳腾龙支行\",支行\n      \"business_breed\": [\"1\",\"2\",\"3\"],品种类型\n      \"business_breed_text\": [\"一笔款\",\"二笔款\",\"拍卖款\"],品种类型文本\n      \"credit_quota\": \"6000000\",授信额度\n      \"enable_quota\": \"0.00\",启用额度\n      \"deposit_ratio\": 6.5,保证金比例\n      \"deposit\": \"0.00\",保证金金额\n      \"paving_deposit\": \"600\",铺地保证金\n      \"single_limit\": \"0.00\",单笔上限\n      \"customeranager\": \"银行经理银行经理银行经理\",银行客户经理\n      \"mobile\": \"0798-6666666\",联系电话\n      \"sign_date\": \"2018-07-12\",签约日期\n      \"due_date\": \"2019-07-25\",到期时间\n      \"status\": 1,状态\n      \"status_text\": \"正常\",状态文本\n      }],\n      \"depositLog\":[{\n      \"id\" :\"1\",记录id\n      \"type\" :\"1\",增付\n      \"money\": 600.00,增付金额\n      \"deposit\": \"60600.00\",保证金金额\n      \"enable_quota\": \"2009230.77\",启用额度\n      \"create_uid\": \"1\",用户id\n      \"create_time\": 2018-08-02 14:20,新增时间\n      \"type_text\": \"增存保证金\",增付（文本）\n      \"username\": \"管理员\",用户姓名\n      }],\n      },",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "get",
    "url": "admin/FundManagement/setStatus",
    "title": "启用禁用状态[admin/FundManagement/setStatus]",
    "version": "1.0.0",
    "name": "setStatus",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/setStatus"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>需要操作的ID</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>启用禁用状态   1启用 2禁用</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "get",
    "url": "admin/FundManagement/setassetValue",
    "title": "设置净资产额[admin/FundManagement/setassetValue]",
    "version": "1.0.0",
    "name": "setassetValue",
    "group": "FundManagement",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/FundManagement/setassetValue"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "assetvalue",
            "description": "<p>净资产额</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/FundManagement.php",
    "groupTitle": "FundManagement"
  },
  {
    "type": "post",
    "url": "admin/Login/index",
    "title": "用户登录[admin/Login/index]",
    "version": "1.0.0",
    "name": "index",
    "group": "Login",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Login/index"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "username",
            "description": "<p>用户名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "headImg",
            "description": "<p>用户图像</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>用户id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "username",
            "description": "<p>用户名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "apiAuth",
            "description": "<p>api接口权限验证秘钥</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Login.php",
    "groupTitle": "Login"
  },
  {
    "type": "get",
    "url": "admin/Login/logout",
    "title": "退出登录[admin/Login/logout]",
    "version": "1.0.0",
    "name": "logout",
    "group": "Login",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Login/logout"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "apiAuth",
            "description": "<p>api接口权限验证秘钥</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Login.php",
    "groupTitle": "Login"
  },
  {
    "type": "post",
    "url": "admin/Log/orderLog",
    "title": "查询订单日志[admin/Log/orderLog]",
    "version": "1.0.0",
    "name": "orderLog",
    "group": "Log",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Log/orderLog"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单主状态(非必填)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "tag",
            "description": "<p>(非必填)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "tableId",
            "description": "<p>(非必填)</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "create_time",
            "description": "<p>时间</p>"
          },
          {
            "group": "Success 200",
            "optional": false,
            "field": "operate_node",
            "description": "<p>操作节点</p>"
          },
          {
            "group": "Success 200",
            "optional": false,
            "field": "operate_det",
            "description": "<p>操作详情</p>"
          },
          {
            "group": "Success 200",
            "optional": false,
            "field": "operate",
            "description": "<p>操作</p>"
          },
          {
            "group": "Success 200",
            "optional": false,
            "field": "name",
            "description": "<p>操作人</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Log.php",
    "groupTitle": "Log"
  },
  {
    "type": "post",
    "url": "admin/Menu/add",
    "title": "新增菜单[admin/Menu/add]",
    "version": "1.0.0",
    "name": "add",
    "group": "Menu",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Menu/add"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "url",
            "description": "<p>菜单url</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>菜单名</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "sort",
            "description": "<p>菜单排序</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fid",
            "description": "<p>上级菜单</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Menu.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "admin/Menu/changeStatus",
    "title": "菜单状态编辑[admin/Menu/changeStatus]",
    "version": "1.0.0",
    "name": "changeStatus",
    "group": "Menu",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Menu/changeStatus"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>菜单id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>菜单状态</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Menu.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "admin/Menu/del",
    "title": "删除菜单[admin/Menu/del]",
    "version": "1.0.0",
    "name": "del",
    "group": "Menu",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Menu/del"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>菜单id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Menu.php",
    "groupTitle": "Menu"
  },
  {
    "type": "post",
    "url": "admin/Menu/edit",
    "title": "编辑菜单[admin/Menu/edit]",
    "version": "1.0.0",
    "name": "edit",
    "group": "Menu",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Menu/edit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "url",
            "description": "<p>菜单url</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>菜单名</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "sort",
            "description": "<p>菜单排序</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fid",
            "description": "<p>上级菜单</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Menu.php",
    "groupTitle": "Menu"
  },
  {
    "type": "get",
    "url": "admin/Menu/index",
    "title": "获取菜单列表[admin/Menu/index]",
    "version": "1.0.0",
    "name": "index",
    "group": "Menu",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Menu/index"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "showName",
            "description": "<p>展示菜单名</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "lv",
            "description": "<p>菜单等级（0：顶级菜单1：二级菜单依次类推）</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "fid",
            "description": "<p>上级菜单（0：顶级菜单 其他：上级菜单id）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "url",
            "description": "<p>菜单url</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Menu.php",
    "groupTitle": "Menu"
  },
  {
    "type": "post",
    "url": "home/News/addBanner",
    "title": "添加和编辑广告位[home/News/addBanner]",
    "version": "1.0.0",
    "name": "addBanner",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/addBanner"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>banner表主键id(编辑广告位时需传该参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态 0：停用；1：正常（默认）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "img_url",
            "description": "<p>封面图片url</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "key_word",
            "description": "<p>封面图片跳转url</p>"
          }
        ]
      }
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/addNew",
    "title": "添加或编辑新闻[home/News/addNew ]",
    "version": "1.0.0",
    "name": "addNew",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/addNew"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>新闻表主键id(编辑新闻时需传该参数)</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>新闻类型 1公司新闻 2行业新闻</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "img1",
            "description": "<p>封面图片地址 images/xh829.png</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "title",
            "description": "<p>新闻标题</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "summary",
            "description": "<p>新闻摘要</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "source",
            "description": "<p>新闻来源</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "author",
            "description": "<p>新闻作者</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>新闻内容</p>"
          }
        ]
      }
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/bannerDetail",
    "title": "轮播图详情[home/News/bannerDetail]",
    "version": "1.0.0",
    "name": "bannerDetail",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/bannerDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>banner信息表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n        \"id\": 38,                        banner信息表主键id\n        \"img_url\": \"www.i444g.png\",      图片地址\n        \"status\": 1,                     状态 0：禁用；1：启用\n        \"key_word\": \"www.bad44du.com\"    URL链接\n        }",
          "type": "json"
        }
      ]
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/bannerList",
    "title": "广告位轮播图列表[home/News/bannerList]",
    "version": "1.0.0",
    "name": "bannerList",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/bannerList"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n  \"data\": [\n            {\n            \"id\": 35,                    banner信息表主键id\n            \"img_url\": \"www.img.png\",      图片url\n            \"key_word\": \"www.baidu.com\",   图片链接url\n            \"status\": 1,                   状态 0：禁用；1：正常\n            \"create_time\": \"2018-06-01 17:52:47\",    创建时间\n            \"name\": \"马特\"                           创建人\n            },\n            {\n            \"id\": 36,\n            \"img_url\": \"www.imsd123dsg.png\",\n            \"key_word\": \"www.bai333sdsddu.com\",\n            \"status\": 1,\n            \"create_time\": \"2018-06-01 17:53:09\",\n            \"name\": \"马特\"\n            }\n        ]",
          "type": "json"
        }
      ]
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/bannerOrderBy",
    "title": "拖动调整轮播图排序[home/News/bannerOrderBy]",
    "version": "1.0.0",
    "name": "bannerOrderBy",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/bannerOrderBy"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "id_arr",
            "description": "<p>拖动后将,banner信息表主键id,按照由上到下重新排序后,组装成的数组</p>"
          }
        ]
      }
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/delIndexBanner",
    "title": "删除首页banner图[home/News/delIndexBanner]",
    "version": "1.0.0",
    "name": "delIndexBanner",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/delIndexBanner"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>banner信息表主键id</p>"
          }
        ]
      }
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/delNew",
    "title": "删除新闻[home/News/delNew]",
    "version": "1.0.0",
    "name": "delNew",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/delNew"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>新闻表主键id</p>"
          }
        ]
      }
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/indexBannerInfo",
    "title": "首页banner图展示信息[home/News/indexBannerInfo]",
    "version": "1.0.0",
    "name": "indexBannerInfo",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/indexBannerInfo"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n  \"data\": [\n            {\n            \"img_url\": \"http://119.23.24.187\\\\businesssys_api\\\\public\\\\uploads\\\\20180607\\\\2b8197d496f4625d79ed1c9c3562c0c4.jpg\",\n            \"key_word\": \"www.bai333sdsddu.com\"\n            },\n            {\n            \"img_url\": \"http://119.23.24.187/businesssys_web/dist/b27deb860137e1d916dfecf71dc3ecd5.png\",\n            \"key_word\": \"www.baddu.com\"\n            }\n         }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "img_url",
            "description": "<p>图片地址</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "key_word",
            "description": "<p>图片链接地址</p>"
          }
        ]
      }
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/newDetail",
    "title": "新闻详情[home/News/newDetail]",
    "version": "1.0.0",
    "name": "newDetail",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/newDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>新闻表主键id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\":\n            {\n            \"id\": 2,                       新闻表主键id\n            \"type\": 1,                     新闻类型 1公司新闻  2行业新闻\n            \"title\": \"中美贸易战\",         新闻标题\n            \"summary\": \"经济\",             摘要\n            \"img1\": \"images/xh829.png\",    封面图面\n            \"source\": \"新浪新闻\",          新闻来源\n            \"author\": \"李四\",              作者\n            \"content\": \"阿萨德噶哒很高的合同人头还有人同行一人头\",   新闻内容\n            \"newsdate\": \"2017-10-10\",                    新闻时间\n            \"create_time\": \"1971-09-11 16:22:05\",        创建时间\n            \"name\": \"许小球\" ,                            创建人(作者)\n            \"deptname\": \"权证部\"                          创建人所属部门(来源)\n            }",
          "type": "json"
        }
      ]
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/newList",
    "title": "新闻列表[home/News/newList]",
    "version": "1.0.0",
    "name": "newList",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/newList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>新闻类型 1公司新闻 2行业新闻</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search_text",
            "description": "<p>新闻标题关键字</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": [\n            {\n                \"id\": 2,                       新闻表主键id\n                \"type\": 1,                     新闻类型 1公司新闻  2行业新闻\n                \"title\": \"中美贸易战\",         新闻标题\n                \"summary\": \"经济\",             摘要\n                \"img1\": \"images/xh829.png\",    封面图面1\n                \"img2\": \"images/xh829.png\",    封面图片2\n                \"img3\": null,                  封面图片3\n                \"source\": \"新浪新闻\",          新闻来源\n                \"author\": \"李四\",              作者\n                \"content\": \"阿萨德噶哒很高的合同人头还有人同行一人头\",   新闻内容\n                \"newsdate\": \"2017-10-10\",                    新闻时间\n                \"create_time\": \"1971-09-11 16:22:05\",        创建时间\n                \"name\": \"许小球\"                             创建人(作者)\n                \"deptname\": \"权证部\"                          创建人所属部门(来源)\n            },\n            {\n                \"id\": 3,\n                \"type\": 1,\n                \"title\": \"中国航母下水\",\n                \"summary\": \"军事\",\n                \"img1\": \"images/xh829.png\",\n                \"img2\": \"images/xh829.png\",\n                \"img3\": null,\n                \"source\": \"网易新闻\",\n                \"author\": \"王五\",\n                \"content\": \"阿嘎多嘎的说法噶是的发送到\",\n                \"newsdate\": \"2016-10-06\",\n                \"create_time\": \"1971-06-13 15:20:45\",\n                \"name\": \"许小球\"\n            }\n        ]",
          "type": "json"
        }
      ]
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "home/News/processList",
    "title": "待处理列表[home/News/processList]",
    "version": "1.0.0",
    "name": "processList",
    "group": "News",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/home/News/processList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n  \"data\": [\n            {\n            \"proc_id\": 633,                 处理明细表主键id\n            \"type\": \"JYDB\",                 业务类型简称\n            \"stage\": \"1002\",                订单状态\n            \"estate_name\": \"信心341011\",    房产名称\n            \"id\": 147,                      赎楼派单表主键id\n            \"order_sn\": \"JYDB2018050382\",   订单编号\n            \"create_time\": 1527815794,      时间\n            \"user_name\": \"管理员\",          审批人名称\n            \"dept_name\": \"财务部\"           审批人所在的部门\n            \"type_text\": \"交易担保\"          业务类型\n            \"is_wealth_managers\": \"1\"            是否是赎楼经理 1 是赎楼经理  2不是赎楼经理\n            },\n            {\n            \"type\": \"JYDB\",\n            \"estate_name\": \"信心341011\",\n            \"order_sn\": \"JYDB2018050382\",\n            \"user_name\": \"管理员\",\n            \"dept_name\": \"财务部\"\n            }\n        ]",
          "type": "json"
        }
      ]
    },
    "filename": "application/home/controller/News.php",
    "groupTitle": "News"
  },
  {
    "type": "post",
    "url": "admin/Nuclearcard/addnuclearCarddata",
    "title": "录入核卡信息[admin/Nuclearcard/addnuclearCarddata]",
    "version": "1.0.0",
    "name": "addnuclearCarddata",
    "group": "Nuclearcard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Nuclearcard/addnuclearCarddata"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>录入核卡信息id（新增的时候传0）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_guarantee_bank_id",
            "description": "<p>核卡id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "card_type",
            "description": "<p>卡号类型（1.个人 2.公司）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cyber_bank",
            "description": "<p>网银（0.未开通 1.已开通 2.已关闭）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "mobile_bank",
            "description": "<p>手机银行（0.未开通 1.已开通 2.已关闭）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "telephone_bank",
            "description": "<p>电话银行（0.未开通 1.已开通 2.已关闭）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "security_account",
            "description": "<p>证券账号（0.未绑定 1.已绑定 2.已解绑）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "credit_card",
            "description": "<p>信用卡（0.未绑定 1.已绑定 2.已解绑）</p>"
          },
          {
            "group": "Parameter",
            "type": "datatime",
            "optional": false,
            "field": "verify_card_time",
            "description": "<p>核卡时间</p>"
          },
          {
            "group": "Parameter",
            "type": "double",
            "optional": false,
            "field": "verify_card_data",
            "description": "<p>核卡资料</p>"
          },
          {
            "group": "Parameter",
            "type": "double",
            "optional": false,
            "field": "limit_public_day",
            "description": "<p>对公单日限额</p>"
          },
          {
            "group": "Parameter",
            "type": "double",
            "optional": false,
            "field": "limit_private_day",
            "description": "<p>对私单日限额</p>"
          },
          {
            "group": "Parameter",
            "type": "double",
            "optional": false,
            "field": "limit_public_single",
            "description": "<p>对公单笔限额</p>"
          },
          {
            "group": "Parameter",
            "type": "double",
            "optional": false,
            "field": "limit_private_single",
            "description": "<p>对私单笔限额</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "remark",
            "description": "<p>备注说明</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Nuclearcard.php",
    "groupTitle": "Nuclearcard"
  },
  {
    "type": "post",
    "url": "admin/Nuclearcard/addnuclearRecord",
    "title": "新增查账记录[admin/Nuclearcard/addnuclearRecord]",
    "version": "1.0.0",
    "name": "addnuclearRecord",
    "group": "Nuclearcard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Nuclearcard/addnuclearRecord"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_guarantee_bank_id",
            "description": "<p>核卡id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_balance",
            "description": "<p>账户余额（1.个人 2.公司）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_status",
            "description": "<p>账号状态（1正常 2冻结 3锁卡 4挂失 5注销）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "check_time",
            "description": "<p>查询时间</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "remark",
            "description": "<p>备注说明</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Nuclearcard.php",
    "groupTitle": "Nuclearcard"
  },
  {
    "type": "post",
    "url": "admin/Nuclearcard/getOtherinfo",
    "title": "获取录卡信息[admin/Nuclearcard/getOtherinfo]",
    "version": "1.0.0",
    "name": "getOtherinfo",
    "group": "Nuclearcard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Nuclearcard/getOtherinfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>卡号id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单号</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n\"vinfo\": [\n{\n\"id\": 4,\n\"order_sn\": \"JYDB2018070002\",\n\"process_name\": \"待审查经理审批\",\n\"item\": \"汇总问题没解决，限制了复审提交系统，未限制审查员提交系统\"\n},\n\"cinfo\": [\n{\n\"bankaccount\": \"杨丽娟\",\n\"accounttype\": 1,\n\"openbank\": \"建设银行\",\n\"bankcard\": \"6227007200050847179\",\n\"accounttype_text\": \"卖方\"\n},\n]\n}\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Nuclearcard.php",
    "groupTitle": "Nuclearcard"
  },
  {
    "type": "post",
    "url": "admin/Nuclearcard/nuclearBack",
    "title": "驳回[admin/Nuclearcard/nuclearBack]",
    "version": "1.0.0",
    "name": "nuclearBack",
    "group": "Nuclearcard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Nuclearcard/nuclearBack"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>核卡id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>驳回理由</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Nuclearcard.php",
    "groupTitle": "Nuclearcard"
  },
  {
    "type": "get",
    "url": "admin/Nuclearcard/nuclearEntryinfo",
    "title": "核卡录入信息[admin/Nuclearcard/nuclearEntryinfo]",
    "version": "1.0.0",
    "name": "nuclearEntryinfo",
    "group": "Nuclearcard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Nuclearcard/nuclearEntryinfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>卡号id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n\"data\": {\n      \"basic_information\": {\n      \"order_sn\": \"TMXJ2018070006\",\n      \"stage\": \"1015\",\n      \"type\": \"TMXJ\",\n      \"finance_sn\": \"100000038\",\n      \"order_source\": 1,\n      \"source_info\": \"中原地产\",\n      \"business_type\": 2,\n      \"order_source_str\": \"合作中介\",\n      \"financing_manager_name\": \"李刚3\",\n      \"dept_manager_name\": \"刘传英\",\n      \"deptname\": \"担保业务01部\",\n      \"type_text\": \"非交易现金\"\n      },\n      \"estate_info\": [\n      {\n      \"estate_name\": \"东雨田村二期雨山阁13B\",\n      \"estate_region\": \"深圳|福田\",\n      \"estate_area\": 132,\n      \"estate_certtype\": 2,\n      \"estate_certnum\": \"20170052938\",\n      \"house_type\": 1,\n      \"estate_certtype_str\": \"不动产证\"\n      }\n      ],\n      \"seller_info\": [\n      {\n      \"is_seller\": 2,\n      \"is_comborrower\": 0,\n      \"cname\": \"叶腾飞\",\n      \"ctype\": 1,\n      \"certtype\": 1,\n      \"certcode\": \"611023197108264691\",\n      \"mobile\": \"18124023571\",\n      \"is_guarantee\": 0,\n      \"is_seller_str\": \"卖方\",\n      \"is_comborrower_str\": \"卖方\",\n      \"ctype_str\": \"个人\",\n      \"certtype_str\": \"身份证\"\n      },\n      {\n      \"is_seller\": 2,\n      \"is_comborrower\": 1,\n      \"cname\": \"滕天磊\",\n      \"ctype\": 1,\n      \"certtype\": 1,\n      \"certcode\": \"611023197807252673\",\n      \"mobile\": \"15224855698\",\n      \"is_guarantee\": 0,\n      \"is_seller_str\": \"卖方\",\n      \"is_comborrower_str\": \"卖方共同借款人\",\n      \"ctype_str\": \"个人\",\n      \"certtype_str\": \"身份证\"\n      }\n      ],\n      \"preliminary_question\": [\n      {\n      \"describe\": \"无\",\n      \"status\": 1\n      }\n      ],\n      \"needing_attention\": [\n      {\n      \"process_name\": \"待部门经理审批\",\n      \"item\": null\n      },\n      {\n      \"process_name\": \"待审查助理审批\",\n      \"item\": null\n      },\n      {\n      \"process_name\": \"待审查员审批\",\n      \"item\": null\n      },\n      {\n      \"process_name\": \"待审查主管审批\",\n      \"item\": null\n      },\n      {\n      \"process_name\": \"待资料入架\",\n      \"item\": null\n      }\n      ],\n      \"cost_account\": {\n      \"ac_guarantee_fee\": \"12345.00\",\n      \"ac_fee\": \"502.00\",\n      \"ac_self_financing\": \"356.00\",\n      \"short_loan_interest\": \"144.00\",\n      \"return_money\": \"135.00\",\n      \"default_interest\": \"136.00\",\n      \"overdue_money\": \"123.00\",\n      \"exhibition_fee\": \"123.00\",\n      \"transfer_fee\": \"0.00\",\n      \"other_money\": \"0.00\",\n      \"notarization\": \"2018-07-19\",\n      \"money\": \"132416.00\",\n      \"project_money_date\": \"2018-07-19\",\n      \"guarantee_per\": 0.03,\n      \"guarantee_rate\": null,\n      \"guarantee_fee\": \"2173741.06\",\n      \"account_per\": 0.1,\n      \"fee\": \"132132.00\",\n      \"self_financing\": \"1313062.00\",\n      \"info_fee\": \"0.00\",\n      \"total_fee\": \"2305873.06\",\n      \"return_money_mode\": 2,\n      \"return_money_amount\": \"55261615.00\",\n      \"turn_into_date\": null,\n      \"turn_back_date\": null,\n      \"chuzhangsummoney\": 461300,\n      \"return_money_mode_str\": \"通过第三方回款\"\n      },\n      \"arrears_info\": [\n      {\n      \"ransom_status_text\": \"已完成\",\n      \"ransomer\": \"聂梦\",\n      \"organization\": \"建设银行-深圳东湖支行\",\n      \"interest_balance\": \"10000.00\",\n      \"mortgage_type_name\": \"公积金贷款\",\n      \"accumulation_fund\": \"0.00\"\n      }\n      ],\n      \"reimbursement_info\": [\n      {\n      \"id\": 163,\n      \"bankaccount\": \"章泽雨 \",\n      \"accounttype\": 3,\n      \"bankcard\": \"611023197708149013\",\n      \"openbank\": \"工商银行\",\n      \"verify_card_status\": 0,\n      \"type\": false,\n      \"type_text\": \"\",\n      \"verify_card_status_text\": \"待核卡\",\n      \"accounttype_str\": \"买方\"\n      },\n      {\n      \"id\": 164,\n      \"bankaccount\": \"滕天磊 \",\n      \"accounttype\": 2,\n      \"bankcard\": \"611023197807252673\",\n      \"openbank\": \"工商银行\",\n      \"verify_card_status\": 3,\n      \"type\": [],\n      \"type_text\": \"\",\n      \"verify_card_status_text\": \"已完成\",\n      \"accounttype_str\": \"卖方共同借款人\"\n      },\n      {\n      \"id\": 165,\n      \"bankaccount\": \"邹明哲 \",\n      \"accounttype\": 2,\n      \"bankcard\": \"611023197008222638\",\n      \"openbank\": \"建设银行\",\n      \"verify_card_status\": 3,\n      \"type\": [],\n      \"type_text\": \"\",\n      \"verify_card_status_text\": \"已完成\",\n      \"accounttype_str\": \"卖方共同借款人\"\n      },\n      {\n      \"id\": 166,\n      \"bankaccount\": \"方高俊 \",\n      \"accounttype\": 1,\n      \"bankcard\": \"611023197907164611\",\n      \"openbank\": \"建设银行\",\n      \"verify_card_status\": 3,\n      \"type\": [],\n      \"type_text\": \"\",\n      \"verify_card_status_text\": \"已完成\",\n      \"accounttype_str\": \"卖方\"\n      }\n      ],\n      \"sqk_info\": null,\n      \"advancemoney_info\": [\n      {\n      \"advance_money\": \"132416.00\",\n      \"advance_day\": 456,\n      \"advance_rate\": 3.6,\n      \"advance_fee\": \"2173741.1\",\n      \"remark\": null,\n      \"id\": 27\n      }\n      ],\n      \"nuclearentry\": {\n      \"limit_public_day\": null,对公单日限额\n      \"limit_private_day\": null,对私单日限额\n      \"limit_public_single\": null,对公单笔限额\n      \"limit_private_single\": null,对私单笔限额\n      \"id\": 1,\n      \"order_guarantee_bank_id\": 165,\n      \"card_type\": 1,卡号类型 1个人  2公司\n      \"verify_card_time\": \"2018-07-12\",核卡时间\n      \"cyber_bank\": 1,网银 0未开通 1已开通 2已关闭\n      \"telephone_bank\": 1,电话银行 0未开通 1已开通 2已关闭\n      \"mobile_bank\": 1,手机银行 0未开通 1已开通 2已关闭\n      \"security_account\": 1,证券账号 0未绑定 1已绑定 2已解绑\n      \"credit_card\": 1,信用卡 0未绑定 1已绑定 2已解绑\n      \"verify_card_data\": [\n      \"1\",\n      \"2\",\n      \"3\",\n      \"4\"\n      ],核卡资料\n      \"verify_card_name\": \"管理员\",\n      \"remark\": \"\",\n      \"verify_card_status\": 3,\n      \"card_type_text\": \"个人\",\n      \"cyber_bank_text\": \"已开通\",\n      \"telephone_bank_text\": \"已开通\",\n      \"mobile_bank_text\": \"已开通\",\n      \"security_account_text\": \"已绑定\",\n      \"credit_card_text\": \"已绑定\",\n      \"all_code\": [\n      {\n      \"code\": \"1\",\n      \"valname\": \"存折(卡)\"\n      },\n      {\n      \"code\": \"2\",\n      \"valname\": \"身份证原件\"\n      },\n      {\n      \"code\": \"3\",\n      \"valname\": \"网银盾\"\n      },\n      {\n      \"code\": \"4\",\n      \"valname\": \"密码\"\n      }\n      ]\n      }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Nuclearcard.php",
    "groupTitle": "Nuclearcard"
  },
  {
    "type": "get",
    "url": "admin/Nuclearcard/nuclearRecord",
    "title": "核卡查询记录[admin/Nuclearcard/nuclearRecord]",
    "version": "1.0.0",
    "name": "nuclearRecord",
    "group": "Nuclearcard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Nuclearcard/nuclearRecord"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>卡号id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_more",
            "description": "<p>是否查询更多（0：是 1否）</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "account_balance",
            "description": "<p>账号余额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "account_status",
            "description": "<p>账号状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "check_time",
            "description": "<p>查询时间</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_name",
            "description": "<p>操作人员</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_deptname",
            "description": "<p>所在部门</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Nuclearcard.php",
    "groupTitle": "Nuclearcard"
  },
  {
    "type": "post",
    "url": "admin/Nuclearcard/nuclearReview",
    "title": "审核通过[admin/Nuclearcard/nuclearReview]",
    "version": "1.0.0",
    "name": "nuclearReview",
    "group": "Nuclearcard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Nuclearcard/nuclearReview"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>核卡id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Nuclearcard.php",
    "groupTitle": "Nuclearcard"
  },
  {
    "type": "get",
    "url": "admin/Nuclearcard/nuclearcardList",
    "title": "核卡列表[admin/Nuclearcard/nuclearcardList]",
    "version": "1.0.0",
    "name": "nuclearcardList",
    "group": "Nuclearcard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Nuclearcard/nuclearcardList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "create_uid",
            "description": "<p>人员id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_type",
            "description": "<p>账号用途</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "account_status",
            "description": "<p>账号状态（1正常 2冻结 3锁卡 4挂失 5注销）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "verify_card_status",
            "description": "<p>核卡状态（0待核卡 1待财务复核 2驳回待处理 3已完成）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankcard",
            "description": "<p>银行卡号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankaccount",
            "description": "<p>银行户名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "openbank",
            "description": "<p>开户银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "account_type_text",
            "description": "<p>账号用途</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "verify_card_status_text",
            "description": "<p>核卡状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "account_status_text",
            "description": "<p>账号状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "verify_card_name",
            "description": "<p>核卡人员</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "financing_manager",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>总条数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Nuclearcard.php",
    "groupTitle": "Nuclearcard"
  },
  {
    "type": "get",
    "url": "admin/Nuclearcard/redemptioncardInfo",
    "title": "赎楼卡信息[admin/Nuclearcard/redemptioncardInfo]",
    "version": "1.0.0",
    "name": "redemptioncardInfo",
    "group": "Nuclearcard",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Nuclearcard/redemptioncardInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>卡号id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "accounttype_text",
            "description": "<p>账户类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "accounttype",
            "description": "<p>账户类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankaccount",
            "description": "<p>银行户名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "openbank",
            "description": "<p>开户银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "bankcard",
            "description": "<p>银行卡号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Nuclearcard.php",
    "groupTitle": "Nuclearcard"
  },
  {
    "type": "post",
    "url": "admin/OrderOthers/addDiscount",
    "title": "新增折扣申请[admin/OrderOthers/addDiscount]",
    "version": "1.0.0",
    "name": "addDiscount",
    "group": "OrderOthers",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderOthers/addDiscount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>折扣原因</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件ids [1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "guarantee_rate",
            "description": "<p>担保费率(额度类JYDB、FJYDB) 必须</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "otherDiscount",
            "description": "<p>垫资金额(现金类'JYXJ', 'TMXJ', 'DQJK', 'PDXJ', 'GMDZ', 'SQDZ') 必须</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "otherDiscount.id",
            "description": "<p>垫资费表 id (详情advanceMoney里对应记录id)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "otherDiscount.new_rate",
            "description": "<p>现日垫资费率</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"otherDiscount\": [\n         [id:1,new_rate:0.2]\n         [id:2,new_rate:0.2]\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/OrderOthers.php",
    "groupTitle": "OrderOthers"
  },
  {
    "type": "get",
    "url": "admin/OrderOthers/discountApplyList",
    "title": "折扣申请列表[admin/OrderOthers/discountApplyList]",
    "version": "1.0.0",
    "name": "discountApplyList",
    "group": "OrderOthers",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderOthers/discountApplyList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "list",
            "description": "<p>订单基本信息.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.process_sn",
            "description": "<p>流程编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.finance_sn",
            "description": "<p>财务编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.stage_text",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.create_time",
            "description": "<p>申请时间</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderOthers.php",
    "groupTitle": "OrderOthers"
  },
  {
    "type": "get",
    "url": "admin/OrderOthers/discountApprovalList",
    "title": "折扣申请审批列表[admin/OrderOthers/discountApprovalList]",
    "version": "1.0.0",
    "name": "discountApprovalList",
    "group": "OrderOthers",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderOthers/discountApprovalList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "create_uid",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>1含下属 0不含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "start_time",
            "description": "<p>开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "end_time",
            "description": "<p>结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "stage_code",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "search_text",
            "description": "<p>关键词</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "limit",
            "description": "<p>条数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "list",
            "description": "<p>订单基本信息.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.process_sn",
            "description": "<p>流程编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.finance_sn",
            "description": "<p>财务编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.stage_text",
            "description": "<p>审批状态</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.create_time",
            "description": "<p>申请时间</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderOthers.php",
    "groupTitle": "OrderOthers"
  },
  {
    "type": "post",
    "url": "admin/OrderOthers/discountDetails",
    "title": "折扣订单基本信息(详情页面)[admin/OrderOthers/discountDetails]",
    "version": "1.0.0",
    "name": "discountDetails",
    "group": "OrderOthers",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderOthers/discountDetails"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "other_id",
            "description": "<p>他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "proc_id",
            "description": "<p>申请列表详情不需要传该参数，审批列表需要传</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "number",
            "optional": false,
            "field": "is_approval",
            "description": "<p>是否可以审核 1可以审核0不可以审核</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "base_info",
            "description": "<p>订单基本信息</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.finance_sn",
            "description": "<p>财务编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.estate_name",
            "description": "<p>房产信息</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.money",
            "description": "<p>担保金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.self_financing",
            "description": "<p>自筹金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.ac_guarantee_fee",
            "description": "<p>实收担保费</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.order_dept",
            "description": "<p>所属部门</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.financing_manager_name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.financing_manager_mobile",
            "description": "<p>理财经理电话</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.dept_manger_name",
            "description": "<p>部门经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.dept_manger_mobile",
            "description": "<p>部门经理电话</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.guarantee_rate",
            "description": "<p>担保费率</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.guarantee_fee",
            "description": "<p>预计担保费</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "discount_info",
            "description": "<p>折扣申请信息</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "discount_info.process_sn",
            "description": "<p>流程编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "discount_info.reason",
            "description": "<p>折扣原因</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "discount_info.attachments",
            "description": "<p>附件</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "discount_info.other_discounts",
            "description": "<p>变更信息</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "old_rate",
            "description": "<p>额度原担保费率、现金原日垫资费率</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "old_money",
            "description": "<p>额度原担保费、现金原垫资费</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "new_rate",
            "description": "<p>额度现担保费率、现金现日垫资费率</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "new_money",
            "description": "<p>额度现担保费、现金现垫资费</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "advance_money",
            "description": "<p>垫资金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "advance_day",
            "description": "<p>垫资天数</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "approval_info",
            "description": "<p>审批记录</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "approval_info.process_name",
            "description": "<p>节点名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "approval_info.status_desc",
            "description": "<p>操作</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "approval_info.content",
            "description": "<p>审批意见</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "approval_info.finish_time",
            "description": "<p>审批时间</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "    HTTP/1.1 200 OK\n{\n   \"base_info\": {\n       \"id\": 33,\n       \"type\": \"JYDB\",\n       \"order_sn\": \"JYDB2018080007\",\n       \"finance_sn\": \"100000010\",\n       \"money\": \"834000.00\",\n       \"financing_manager_name\": \"徐霞\",\n       \"financing_manager_mobile\": \"13554767498\",\n       \"financing_dept_id\": 24,\n       \"dept_manager_id\": 119,\n       \"self_financing\": \"0.00\",\n       \"ac_guarantee_fee\": \"6672.00\",\n       \"guarantee_rate\": 0.8,\n       \"guarantee_fee\": \"6672.00\",\n       \"order_dept\": \"担保业务01部\",\n       \"dept_manger_name\": \"刘传英\",\n       \"dept_manger_mobile\": \"13570871906\"\n   },\n   \"discount_info\": {\n       \"id\": 113,\n       \"process_sn\": \"201809040020\",\n       \"reason\": \"八卦四路意馨居A#-B#栋B201,此物业是我司员工欧小利购买，特向领导申请此单额度赎楼按0.5收费，请领导审批谢谢！\",\n\"attachments\": [\n  {\n  \"id\": 19,\n  \"url\": \"http://119.23.24.187\\\\businesssys_api\\\\public/uploads/20180822/c3af32d7995d3faa8745bcf8025b020d.jpg\",\n  \"name\": \"Penguins.jpg\",\n  \"thum1\": \"uploads/thum/20180822/c3af32d7995d3faa8745bcf8025b020d.jpg\",\n \"ext\": \"jpg\"\n}\n ],\n       \"other_discounts\": [\n           {\n               \"order_other_id\": 113,\n               \"old_rate\": 0.8,\n               \"old_money\": \"6672.00\",\n               \"new_rate\": 1.1,\n               \"new_money\": \"9174.00\",\n                \"advance_money\": \"1200000.00\",\n                \"advance_day\": 10\n           }\n       ]\n   },\n   \"approval_info\": [\n       {\n           \"id\": 3490,\n           \"process_name\": \"待业务保单\",\n           \"auditor_name\": \"管理员\",\n           \"status_desc\": \"通过\",\n           \"content\": null,\n           \"finish_time\": 2018-09-04 19:08:53\n       }\n   ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/OrderOthers.php",
    "groupTitle": "OrderOthers"
  },
  {
    "type": "post",
    "url": "admin/OrderOthers/discountOrderDetail",
    "title": "新增折扣申请订单基本信息[admin/OrderOthers/discountOrderDetail]",
    "version": "1.0.0",
    "name": "discountOrderDetail",
    "group": "OrderOthers",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderOthers/discountOrderDetail"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "orderInfo",
            "description": "<p>订单基本信息.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.order_sn",
            "description": "<p>业务单号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.finance_sn",
            "description": "<p>财务编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.estate_name",
            "description": "<p>房产信息</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.money",
            "description": "<p>担保金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "list.self_financing",
            "description": "<p>自筹金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.ac_guarantee_fee",
            "description": "<p>实收担保费</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.order_dept",
            "description": "<p>所属部门</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.financing_manager_name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.financing_manager_mobile",
            "description": "<p>理财经理电话</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.dept_manger_name",
            "description": "<p>部门经理</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.dept_manger_mobile",
            "description": "<p>部门经理电话</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.guarantee_rate",
            "description": "<p>担保费率</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "orderInfo.guarantee_fee",
            "description": "<p>预计担保费</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "advanceMoney",
            "description": "<p>垫资信息</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "advanceMoney.advance_money",
            "description": "<p>垫资金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "advanceMoney.advance_day",
            "description": "<p>垫资天数</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "advanceMoney.advance_rate",
            "description": "<p>原日垫资费率/%</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "advanceMoney.advance_fee",
            "description": "<p>原垫资费/元</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "advanceMoney.plus_rate",
            "description": "<p>加收费率</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderOthers.php",
    "groupTitle": "OrderOthers"
  },
  {
    "type": "post",
    "url": "admin/OrderOthers/editDiscount",
    "title": "编辑折扣申请[admin/OrderOthers/editDiscount]",
    "version": "1.0.0",
    "name": "editDiscount",
    "group": "OrderOthers",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderOthers/editDiscount"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "other_id",
            "description": "<p>他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>折扣原因</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "attachment",
            "description": "<p>附件ids [1,2,3]</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "guarantee_rate",
            "description": "<p>担保费率(额度类JYDB、FJYDB) 必须</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "otherDiscount",
            "description": "<p>垫资金额(现金类'JYXJ', 'TMXJ', 'DQJK', 'PDXJ', 'GMDZ', 'SQDZ') 必须</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "otherDiscount.id",
            "description": "<p>垫资费表 id (详情advanceMoney里对应记录id)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "otherDiscount.new_rate",
            "description": "<p>现日垫资费率</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"otherDiscount\": [\n         [id:1,new_rate:0.2]\n         [id:2,new_rate:0.2]\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/OrderOthers.php",
    "groupTitle": "OrderOthers"
  },
  {
    "type": "get",
    "url": "admin/OrderOthers/getStage",
    "title": "获取审批状态 [admin/OrderOthers/getStage]",
    "version": "1.0.0",
    "name": "getStage",
    "group": "OrderOthers",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderOthers/getStage"
      }
    ],
    "filename": "application/admin/controller/OrderOthers.php",
    "groupTitle": "OrderOthers"
  },
  {
    "type": "post",
    "url": "admin/OrderOthers/subDealWith",
    "title": "提交折扣申请审批[admin/OrderOthers/subDealWith]",
    "version": "1.0.0",
    "name": "subDealWith",
    "group": "OrderOthers",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderOthers/subDealWith"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "other_id",
            "description": "<p>其他业务表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "proc_id",
            "description": "<p>处理明细表主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_approval",
            "description": "<p>审批结果 1通过 2驳回</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "content",
            "description": "<p>审批意见</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "backtoback",
            "description": "<p>是否退回之后直接返回本节点 1 返回 不返回就不需要传值</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "back_proc_id",
            "description": "<p>退回节点id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderOthers.php",
    "groupTitle": "OrderOthers"
  },
  {
    "type": "post",
    "url": "admin/OrderRelated/fundChannel",
    "title": "获取资金渠道[admin/OrderRelated/fundChannel]",
    "version": "1.0.0",
    "name": "fundChannel",
    "group": "OrderRelated",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderRelated/fundChannel"
      }
    ],
    "filename": "application/admin/controller/OrderRelated.php",
    "groupTitle": "OrderRelated"
  },
  {
    "type": "post",
    "url": "admin/OrderRelated/orderAccountType",
    "title": "根据订单类型获取账户类型[admin/OrderRelated/orderAccountType]",
    "version": "1.0.0",
    "name": "orderAccountType",
    "group": "OrderRelated",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderRelated/orderAccountType"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "repayment",
            "description": "<p>赎楼还款账户类型</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "last",
            "description": "<p>尾款账户类型</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "post",
            "description": "<p>过账账户账户类型、出账账户类型</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "supervision",
            "description": "<p>监管账户类型</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "payback",
            "description": "<p>回款账户类型</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderRelated.php",
    "groupTitle": "OrderRelated"
  },
  {
    "type": "post",
    "url": "admin/OrderRelated/orderStage",
    "title": "根据订单类型获取订单状态[admin/OrderRelated/orderStage]",
    "version": "1.0.0",
    "name": "orderStage",
    "group": "OrderRelated",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderRelated/orderStage"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderRelated.php",
    "groupTitle": "OrderRelated"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/changeChannel",
    "title": "变更渠道[admin/OrderWarrant/changeChannel]",
    "version": "1.0.0",
    "name": "changeChannel",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/changeChannel"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>资金渠道id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "channelId",
            "description": "<p>渠道id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "reason",
            "description": "<p>原因</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/dataSendList",
    "title": "资料送审列表[admin/OrderWarrant/dataSendList]",
    "version": "1.0.0",
    "name": "dataSendList",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/dataSendList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "estateCity",
            "description": "<p>所属城市</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "estateDistrict",
            "description": "<p>所属城区</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>含下属</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "deliveryStatus",
            "description": "<p>送审状态</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "fund_channel_id",
            "description": "<p>渠道id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estate_region",
            "description": "<p>城市城区</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "type_name",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "money",
            "description": "<p>垫资金额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "fund_channel_name",
            "description": "<p>资金渠道</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "delivery_status_name",
            "description": "<p>送审状态</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "create_time",
            "description": "<p>报单时间</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>渠道资金id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/details",
    "title": "详情[admin/OrderWarrant/details]",
    "version": "1.0.0",
    "name": "details",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/details"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>权证详情</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>判断图片显示 1不显示</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "is_finish",
            "description": "<p>是否完成 0未完成1已完成</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/index",
    "title": "取原产证列表[admin/OrderWarrant/index]",
    "version": "1.0.0",
    "name": "index",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/index"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "isGet",
            "description": "<p>取证状态0默认全部1已取2待取</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>暂时有JYDB一个类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>0不含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页显示数量</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_finish",
            "description": "<p>0待取原产证1已取原产证</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>取证日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>理财经理部门</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "estateInfo",
            "description": "<p>房产信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/mortgageList",
    "title": "注销抵押列表[admin/OrderWarrant/mortgageList]",
    "version": "1.0.0",
    "name": "mortgageList",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/mortgageList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "isGet",
            "description": "<p>取证状态0默认全部1已取2待取</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>暂时有JYDB一个类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>0不含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页显示数量</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_finish",
            "description": "<p>0待取原产证1已取原产证</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>取证日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>理财经理部门</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "estateInfo",
            "description": "<p>房产信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/newCertList",
    "title": "领取新证列表[admin/OrderWarrant/newCertList]",
    "version": "1.0.0",
    "name": "newCertList",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/newCertList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "isGet",
            "description": "<p>取证状态0默认全部1已取2待取</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>暂时有JYDB一个类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>0不含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页显示数量</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_finish",
            "description": "<p>0待取原产证1已取原产证</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>取证日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>理财经理部门</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "estateInfo",
            "description": "<p>房产信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/newMortgageList",
    "title": "抵押新证列表[admin/OrderWarrant/newMortgageList]",
    "version": "1.0.0",
    "name": "newMortgageList",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/newMortgageList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "isGet",
            "description": "<p>取证状态1已取2待取</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>暂时有JYDB一个类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>0不含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页显示数量</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_finish",
            "description": "<p>0待取原产证1已取原产证</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>取证日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>理财经理部门</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "estateInfo",
            "description": "<p>房产信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/ownershipList",
    "title": "递件过户列表[admin/OrderWarrant/ownershipList]",
    "version": "1.0.0",
    "name": "ownershipList",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/ownershipList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "isGet",
            "description": "<p>取证状态0默认全部1已取2待取</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>暂时有JYDB一个类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>0不含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页显示数量</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "is_finish",
            "description": "<p>0待取原产证1已取原产证</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>取证日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "deptname",
            "description": "<p>理财经理部门</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "estateInfo",
            "description": "<p>房产信息</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/reviewData",
    "title": "审核通过[admin/OrderWarrant/reviewData]",
    "version": "1.0.0",
    "name": "reviewData",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/reviewData"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>资金渠道id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "trust_contract_num",
            "description": "<p>信托合同号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "push_order_money",
            "description": "<p>推单金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "loan_day",
            "description": "<p>借款天数</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/update",
    "title": "完成原产证取回[admin/OrderWarrant/update]",
    "version": "1.0.0",
    "name": "update",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/update"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>权证id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/updateData",
    "title": "确认送审[admin/OrderWarrant/updateData]",
    "version": "1.0.0",
    "name": "updateData",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/updateData"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>资金渠道id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": ""
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/updateMortgage",
    "title": "完成注销抵押[admin/OrderWarrant/updateMortgage]",
    "version": "1.0.0",
    "name": "updateMortgage",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/updateMortgage"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>权证id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/updateNewCert",
    "title": "完成领取新证[admin/OrderWarrant/updateNewCert]",
    "version": "1.0.0",
    "name": "updateNewCert",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/updateNewCert"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>权证id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "receiptNo",
            "description": "<p>回执编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "receiptImg",
            "description": "<p>回执相片</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/updateNewMortgage",
    "title": "完成新证抵押[admin/OrderWarrant/updateNewMortgage]",
    "version": "1.0.0",
    "name": "updateNewMortgage",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/updateNewMortgage"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>权证id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "receiptNo",
            "description": "<p>回执编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "receiptImg",
            "description": "<p>回执相片</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/OrderWarrant/updateOwnership",
    "title": "完成过户[admin/OrderWarrant/updateOwnership]",
    "version": "1.0.0",
    "name": "updateOwnership",
    "group": "OrderWarrant",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrderWarrant/updateOwnership"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>权证id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "receiptNo",
            "description": "<p>回执编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "receiptImg",
            "description": "<p>回执相片</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrderWarrant.php",
    "groupTitle": "OrderWarrant"
  },
  {
    "type": "post",
    "url": "admin/Orders/recallOrder",
    "title": "撤单 [admin/Orders/recallOrder]",
    "version": "1.0.0",
    "name": "recallOrder",
    "group": "Order",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/recallOrder"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号   说明：订单状态&lt;1004 或者是 特权人，并且有按钮权限则显示撤单按钮</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Order"
  },
  {
    "type": "post",
    "url": "admin/Ransomer/addDispatch",
    "title": "指派赎楼员[admin/Ransomer/addDispatch]",
    "version": "1.0.0",
    "name": "addDispatch",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Ransomer/addDispatch"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isFinance",
            "description": "<p>是否不经财务派单1是0否</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "dispatch",
            "description": "<p>赎楼员id赎楼员姓名ransomer赎楼银行ransom_bank赎楼类型ransom_type,按揭信息id ：mortgage_id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Ransomer.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Orders/addOrder",
    "title": "新增订单[admin/Orders/addOrder]",
    "version": "1.0.0",
    "name": "addOrder",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/addOrder"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>业务类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "category",
            "description": "<p>业务分类 1额度 2现金</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>担保金额</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "financingManager",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "depId",
            "description": "<p>理财经理部门id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mortgageName",
            "description": "<p>按揭人姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mortgageMobile",
            "description": "<p>按揭人电话</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>部门经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>业务说明</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSource",
            "description": "<p>业务来源</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sourceInfo",
            "description": "<p>来源信息(来源机构)</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "estateData",
            "description": "<p>房产信息estate_name房产名称,estate_ecity（string)城市,estate_district{string}城区,estate_zone（string）片区estate_region地址名称house_type(int)房屋类型estate_certtype产证类型estate_certnum产证编码estate_area面积building_name楼盘名称estate_alias楼盘别名estate_unit栋阁名称estate_unit_alias栋阁别名estate_floor楼层estate_floor_plusminus楼层正负+-estate_house房号</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "mortgageData",
            "description": "<p>按揭信息  按揭数据类型type(string  'ORIGINAL','NOW') ,按揭类型mortgage_type(int),按揭金额money(float),按揭机构类型organization_type(string),按揭机构organization(string)本息余额,interest_balance(float)</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "strikePrice",
            "description": "<p>首期款成交价</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "earnestMoney",
            "description": "<p>首期款定金</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "dpMoney",
            "description": "<p>首期款金额</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "buyWay",
            "description": "<p>购房方式</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "nowMortgage",
            "description": "<p>首期款按揭成数</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "redeembank",
            "description": "<p>赎楼短贷银行</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "DpBankInfo",
            "description": "<p>首期款监管银行 dp_supervise_date(date)监管日期 dp_money(float)监管金额 dp_organization_type（int）监管机构 1银行2其他 dp_supervise_bank(string)监管银行（监管机构为1） dp_supervise_bank_branch监管支行（监管机构为1） dp_organization监管机构（监管机构为2）</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "returnMoneyArr",
            "description": "<p>回款方式(现金单) return_money_mode(int)回款方式 return_money_amount(float)回款金额 remark（string）回款备注</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "advance",
            "description": "<p>垫资费数组新增一个字段 plus_rate 加收费率</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "seller",
            "description": "<p>客户信息'ctype(int)所属类型,买卖方is_seller(int)1卖方2卖方,是否共同借款人is_comborrower(int)1是共同借款人0不是,姓名cname(string),certtype证件类型certtype,证件编号certcode,电话mobile电话,是否担保申请人is_guarantee0不是1是,datacenter_id客户管理系统ID</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isSellerComborrower",
            "description": "<p>卖方共同借款人0否1是</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isBuyerComborrower",
            "description": "<p>买方共同借款人0否1是</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "notarization",
            "description": "<p>公证日期</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "selfFinancing",
            "description": "<p>自筹金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "guaranteePer",
            "description": "<p>担保成数</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "guaranteeRate",
            "description": "<p>担保费率</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "accumulationFund",
            "description": "<p>公积金贷款出账</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "bussinessLoan",
            "description": "<p>商贷贷款出账</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "accountPer",
            "description": "<p>出账成数</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "guaranteeFee",
            "description": "<p>担保费</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "fee",
            "description": "<p>手续费</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "infoFee",
            "description": "<p>预计信息费</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "totalFee",
            "description": "<p>费用合计</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "attach",
            "description": "<p>附件['attachment_id'=&gt;]</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "lastParagrah",
            "description": "<p>尾款银行信息  还款账号类型type =2固定值，赎楼还款账户, 银行户名bankaccount , 账户类型accounttype 账户类型：1卖方 2卖方共同借款人, 银行卡号bankcard, 银行名称openbank</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "repayment",
            "description": "<p>赎楼银行信息  还款账号类型type =1固定值，赎楼还款账户,2尾款账户, 银行户名bankaccount , 账户类型accounttype 账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它, 银行卡号bankcard, 银行名称openbank</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Orders/cashMatInfo",
    "title": "现金垫资信息 [admin/Orders/cashMatInfo]",
    "version": "1.0.0",
    "name": "cashMatInfo",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/cashMatInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "notarization",
            "description": "<p>公证日期</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "self_financing",
            "description": "<p>自筹金额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "guarantee_per",
            "description": "<p>担保成数</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "guarantee_rate",
            "description": "<p>担保费率</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "out_account_total",
            "description": "<p>预计出账总额</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "account_per",
            "description": "<p>出账成数</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "guarantee_fee",
            "description": "<p>担保费</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "fee",
            "description": "<p>手续费</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "info_fee",
            "description": "<p>预计信息费</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "total_fee",
            "description": "<p>费用合计</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "order_source",
            "description": "<p>业务来源1合作中介 2银行介绍 3个人介绍 4房帮帮 5其它来源</p>"
          },
          {
            "group": "Success 200",
            "optional": false,
            "field": "fund_channel_per",
            "description": "<p>垫资成数</p>"
          },
          {
            "group": "Success 200",
            "optional": false,
            "field": "money",
            "description": "<p>订单金额（JYXJ 垫资总额）</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "source_info",
            "description": "<p>来源信息(来源机构)</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "guaranteeBank",
            "description": "<p>赎楼还款银行信息type还款账号类型：1赎楼还款账户2尾款账号信息,3过账账户信息,4回款账户信息  bankaccount银行户名accounttype账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它（当type为1时只能选1、2,bankcard卡号openbank银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "mortgage_name",
            "description": "<p>按揭人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "mortgage_mobile",
            "description": "<p>按揭人电话</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>业务说明</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "attachInfo",
            "description": "<p>附件信息name附件名称</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "fundChannel",
            "description": "<p>资金渠道信息</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "advanceMoney",
            "description": "<p>垫资费计算信息</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "turn_into_date",
            "description": "<p>存入日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "turn_back_date",
            "description": "<p>转回日期</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "return_money_mode",
            "description": "<p>回款方式</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "return_money_amount",
            "description": "<p>回款金额</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "financing_dept_id",
            "description": "<p>理财经理部门id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "financing_manager_id",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "dept_manager_id",
            "description": "<p>部门经理id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Ransomer/dispatchList",
    "title": "指派赎楼员列表[admin/Ransomer/dispatchList]",
    "version": "1.0.0",
    "name": "dispatchList",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Ransomer/dispatchList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Ransomer.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Orders/dqjkList",
    "title": "短期借款列表[admin/Orders/dqjkList]",
    "version": "1.0.0",
    "name": "dqjkList",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/dqjkList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "startTime",
            "description": "<p>订单开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endTime",
            "description": "<p>订单结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search_text",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "guaranteeFeeStatus",
            "description": "<p>待收担保费 1</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isReturnMoneyFinish",
            "description": "<p>待完成回款 1</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isWindControl",
            "description": "<p>待风控审批 1</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_data_entry",
            "description": "<p>待风控审批 0未审批1已审批</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "guarantee_fee_status",
            "description": "<p>担保费是否已收 1未收 2已收</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_return_money_finish",
            "description": "<p>待完成回款 0未完成1已完成</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Orders/exportOrderList",
    "title": "导出综合查询列表[admin/Orders/exportOrderList]",
    "version": "1.0.0",
    "name": "exportOrderList",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/exportOrderList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "startTime",
            "description": "<p>订单开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endTime",
            "description": "<p>订单结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "estateCity",
            "description": "<p>所属城市</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "estateDistrict",
            "description": "<p>所属城区</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>是否含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "typeStr",
            "description": "<p>订单类型文本</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "create_time",
            "description": "<p>报单时间</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "stageStr",
            "description": "<p>订单状态描述</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estate_region",
            "description": "<p>房产地区</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主产权人</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estateInfo",
            "description": "<p>房产信息</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "financing_manager_id",
            "description": "<p>理财经理id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Orders/orderDetails",
    "title": "JYDB、JYXJ、TMXJ、GMDZ订单详情[admin/Orders/orderDetails]",
    "version": "1.0.0",
    "name": "orderDetails",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/orderDetails"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n\"evaluation_price\" :房产评估价\n\"now_mortgage\" :房价评估信息-现按揭层数\n\"estateInfo\": [\n{\n\"estate_name\": \"名称1a11dsadaaa\", 楼盘名称\n\"estate_region\": \"深圳-罗湖桂园街道\", 楼盘市+区\n\"estate_area\": 12,面积\n\"estate_certtype\": 1,房产证类型\n\"estate_certnum\": \"23\",产证编号\n\"house_type\": 1,房屋类型\n\"id\": 390,房产id\n\"estate_floor_plusminus\": \"up\",楼层类型(up:地上楼层；down：地下楼层)\n\"estate_ecity\": \"440300\",市编号\n\"estate_district\": \"440303001\",区编号\n\"estate_unit_alias\": \"阁栋别名\",栋阁别名\n\"estate_alias\": \"别名1\",楼盘别名\n\"house_type_str\": \"分户\",\n\"estate_certtype_str\": \"房产证\".\n\"house_id\" 房号id\n}\n\"sellerInfo(客户信息)\": {\n{\n\"ctype\": 1,所属类型 1个人 2企业\n\"is_seller\": 2,客户 1买方 2卖方\n\"is_comborrower\": 0,共同借款人属性  0借款人 1共同借款人\n\"cname\": \"李四\",姓名\n\"certtype\": 1,证件类型\n\"certcode\": \"123456789\",证件编号\n\"mobile\": \"18825454079\",电话\n\"is_guarantee\": 0,是否担保申请人0不是1是\n\"id\": 827,客户id\n\"ctype_str\": \"个人\",\n\"certtype_str\": \"身份证\",\n\"is_guarantee_str\": \"否\"\n\"datacenter_id\" 客户管理系统id\n}\n],\n\"dp_strike_price\": \"123.00\",首期款成交价\n\"dp_earnest_money\": \"1.00\",首期款定金\n\"dp_money\": \"10.00\",首期款金额\n\"dp_supervise_bank\": \"农业银行\",监管银行\n\"dp_buy_way\": 1,购房方式\n\"dp_now_mortgage\": \"1.00\",现按揭成数(首期款信息使用)\n\"dp_redeem_bank\": \"农业银行\",赎楼短贷银行\n\"dp_supervise_date\": null,监管日期\n\"dp_supervise_guarantee\": null,担保公司监管\n\"dp_supervise_buyer\": null,买方本人监管\n\"dp_supervise_bank_branch\": null,   首期款 监管银行 支行\n\"dp_redeem_bank_branch\": null,   首期款 赎楼短贷银行 支行\n\"dp_buy_way_str\": \"全款购房\"\n\n},",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Orders/orderEdit",
    "title": "编辑订单[admin/Orders/orderEdit]",
    "version": "1.0.0",
    "name": "orderEdit",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/orderEdit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "estateData",
            "description": "<p>房产信息（DQJK没有）estate_name房产名称,estate_ecity（string)城市,estate_district{string}城区,estate_zone（string）片区estate_region地址名称house_type(int)房屋类型estate_certtype产证类型estate_certnum产证编码estate_area面积building_name楼盘名称estate_alias楼盘别名estate_unit栋阁名称estate_unit_alias栋阁别名estate_floor楼层estate_floor_plusminus楼层正负+-estate_house房号</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "seller",
            "description": "<p>客户信息（DQJK没有）'ctype(int)所属类型,买卖方is_seller(int)1卖方2卖方,是否共同借款人is_comborrower(int)1是共同借款人0不是,姓名cname(string),certtype证件类型certtype,证件编号certcode,电话mobile电话,是否担保申请人is_guarantee0不是1是,datacenter_id客户管理系统ID</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "strikePrice",
            "description": "<p>首期款成交价（JYDB、JYXJ、PDXJ、SQDZ）</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "earnestMoney",
            "description": "<p>首期款定金（JYDB、JYXJ、PDXJ、SQDZ）</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "dpMoney",
            "description": "<p>首期款金额（JYDB、JYXJ、PDXJ、SQDZ）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "superviseBank",
            "description": "<p>首期款监管银行</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "superviseDate",
            "description": "<p>首期款监管日期</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "nowMortgage",
            "description": "<p>首期款按揭成数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "buyWay",
            "description": "<p>购房方式 （JYDB、JYXJ、PDXJ、SQDZ）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "superviseGuarantee",
            "description": "<p>担保公司监管 （SQDZ）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "superviseBuyer",
            "description": "<p>买方本人监管 （SQDZ）</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "evaluation_price",
            "description": "<p>评估价(GMDZ、TMXJ)</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "now_mortgage",
            "description": "<p>现按揭成数(GMDZ、TMXJ)</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "mortgageData",
            "description": "<p>按揭信息（DQJK没有）  按揭数据类型type(string  'ORIGINAL','NOW') ,按揭类型mortgage_type(int),按揭金额money(float),按揭机构类型organization_type(string),按揭机构organization(string)本息余额,interest_balance(float)</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "DpBankInfo",
            "description": "<p>首期款监管银行 dp_supervise_date(date)监管日期 dp_money(float)监管金额 dp_organization_type（int）监管机构 1银行2其他 dp_supervise_bank(string)监管银行（监管机构为1） dp_supervise_bank_branch监管支行（监管机构为1） dp_organization监管机构（监管机构为2）</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "returnMoneyArr",
            "description": "<p>回款方式(现金单) return_money_mode(int)回款方式 return_money_amount(float)回款金额 remark（string）回款备注</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "advance",
            "description": "<p>垫资费数组新增一个字段 plus_rate 加收费率</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "notarization",
            "description": "<p>公证日期</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "money",
            "description": "<p>担保金额|垫资金额总计</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "selfFinancing",
            "description": "<p>自筹金额</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "guaranteePer",
            "description": "<p>担保成数|垫资成数</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "guaranteeRate",
            "description": "<p>担保费率（JYDB）</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "accumulationFund",
            "description": "<p>公积金贷款出账</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "bussinessLoan",
            "description": "<p>商贷贷款出账</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "consumerLoan",
            "description": "<p>消费贷款出账</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "guaranteeFee",
            "description": "<p>担保费</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "fee",
            "description": "<p>手续费</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "infoFee",
            "description": "<p>预计信息费</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "money_mode",
            "description": "<p>回款方式</p>"
          },
          {
            "group": "Parameter",
            "type": "float",
            "optional": false,
            "field": "returnMoney",
            "description": "<p>回款金额</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "channel",
            "description": "<p>渠道信息 fund_channel_id 渠道id fund_channel_name 渠道名称 money 渠道金额</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "repayment",
            "description": "<p>预录赎楼还款银行信息  还款账号类型type =1固定值，赎楼还款账户, 银行户名bankaccount , 账户类型accounttype 账户类型：1卖方 2卖方共同借款人, 银行卡号bankcard, 银行名称openbank</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "lastParagrah",
            "description": "<p>尾款银行信息  还款账号类型type =2固定值，赎楼还款账户, 银行户名bankaccount , 账户类型accounttype 账户类型：1卖方 2卖方共同借款人, 银行卡号bankcard, 银行名称openbank</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "postInfo",
            "description": "<p>过账账户信息  还款账号类型type =3 固定值，赎楼还款账户, 银行户名bankaccount , 账户类型 accounttype , 银行卡号bankcard, 银行名称openbank</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "returnMoneyInfo",
            "description": "<p>回款账户信息  还款账号类型type =4 固定值，赎楼还款账户, 银行户名bankaccount , 账户类型 accounttype , 银行卡号bankcard, 银行名称openbank</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "supervision",
            "description": "<p>监管银行信息  还款账号类型type =5 固定值，赎楼还款账户, 银行户名bankaccount , 账户类型 accounttype , 银行卡号bankcard, 银行名称openbank</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "debitInfo",
            "description": "<p>出账银行信息  还款账号类型type =6 固定值，赎楼还款账户, 银行户名bankaccount , 账户类型 accounttype , 银行卡号bankcard, 银行名称openbank</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "financingManager",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>部门经理id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "depId",
            "description": "<p>理财经理部门id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>业务类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSource",
            "description": "<p>业务来源</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "sourceInfo",
            "description": "<p>来源信息(来源机构)</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mortgageName",
            "description": "<p>按揭人姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mortgageMobile",
            "description": "<p>按揭人电话</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>业务说明</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "attach",
            "description": "<p>附件['attachment_id'=&gt;,id订单附件id]</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Orders/orderGuarantee",
    "title": "担保赎楼信息 [admin/Orders/orderGuarantee]",
    "version": "1.0.0",
    "name": "orderGuarantee",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/orderGuarantee"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "notarization",
            "description": "<p>公证日期</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "guarantee_money",
            "description": "<p>担保金额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "self_financing",
            "description": "<p>自筹金额</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "guarantee_per",
            "description": "<p>担保成数</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "guarantee_rate",
            "description": "<p>担保费率</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "out_account_total",
            "description": "<p>预计出账总额</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "account_per",
            "description": "<p>出账成数</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "guarantee_fee",
            "description": "<p>担保费</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "fee",
            "description": "<p>手续费</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "info_fee",
            "description": "<p>预计信息费</p>"
          },
          {
            "group": "Success 200",
            "type": "float",
            "optional": false,
            "field": "total_fee",
            "description": "<p>费用合计</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "order_source",
            "description": "<p>业务来源1合作中介 2银行介绍 3个人介绍 4房帮帮 5其它来源</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "source_info",
            "description": "<p>来源信息(来源机构)</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "guaranteeBank",
            "description": "<p>赎楼还款银行信息type还款账号类型：1赎楼还款账户2尾款账号信息bankaccount银行户名accounttype账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它（当type为1时只能选1、2,bankcard卡号openbank银行</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "mortgage_name",
            "description": "<p>按揭人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "mortgage_mobile",
            "description": "<p>按揭人电话</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>业务说明</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "attachInfo",
            "description": "<p>附件信息name附件名称</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "financing_dept_id",
            "description": "<p>理财经理部门id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "financing_manager_id",
            "description": "<p>理财经理id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "dept_manager_id",
            "description": "<p>部门经理id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Orders/orderList",
    "title": "订单列表[admin/Orders/orderList]",
    "version": "1.0.0",
    "name": "orderList",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/orderList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "startTime",
            "description": "<p>订单开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endTime",
            "description": "<p>订单结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "estateCity",
            "description": "<p>所属城市</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "estateDistrict",
            "description": "<p>所属城区</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "guaranteeFeeStatus",
            "description": "<p>待收担保费 1</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isBankLoan",
            "description": "<p>待银行放款 1</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isMortgage",
            "description": "<p>待过户抵押 1</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isForeclosure",
            "description": "<p>待完成赎楼 1</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isWindControl",
            "description": "<p>待风控审批 1</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_data_entry",
            "description": "<p>待风控审批 0未审批1已审批</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "guarantee_fee_status",
            "description": "<p>担保费是否已收 1未收 2已收</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_loan_finish",
            "description": "<p>待银行放款 0未完成1已完成</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_foreclosure_finish",
            "description": "<p>待赎楼完成 0未完成1已完成</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "is_mortgage_finish",
            "description": "<p>待过户抵押 0未完成1已完成</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/Orders/totalOrderList",
    "title": "综合订单列表[admin/Orders/totalOrderList]",
    "version": "1.0.0",
    "name": "totalOrderList",
    "group": "Orders",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Orders/totalOrderList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "startTime",
            "description": "<p>订单开始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "endTime",
            "description": "<p>订单结束时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "estateCity",
            "description": "<p>所属城市</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "estateDistrict",
            "description": "<p>所属城区</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>是否含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "typeStr",
            "description": "<p>订单类型文本</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "create_time",
            "description": "<p>报单时间</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "stage",
            "description": "<p>订单状态</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "stageStr",
            "description": "<p>订单状态描述</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estate_region",
            "description": "<p>房产地区</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estate_name",
            "description": "<p>房产名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estate_owner",
            "description": "<p>业主产权人</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "estateInfo",
            "description": "<p>房产信息</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "name",
            "description": "<p>理财经理姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "financing_manager_id",
            "description": "<p>理财经理id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Orders.php",
    "groupTitle": "Orders"
  },
  {
    "type": "post",
    "url": "admin/OrganiZation/addOrgani",
    "title": "添加组织结构[admin/OrganiZation/addOrgani ]",
    "version": "1.0.0",
    "name": "addOrgani",
    "group": "OrganiZation",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrganiZation/addOrgani"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>部门名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "parentid",
            "description": "<p>父部门id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "sort",
            "description": "<p>排序</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrganiZation.php",
    "groupTitle": "OrganiZation"
  },
  {
    "type": "post",
    "url": "admin/OrganiZation/bumenInfo",
    "title": "部门信息[admin/OrganiZation/bumenInfo ]",
    "version": "1.0.0",
    "name": "bumenInfo",
    "group": "OrganiZation",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrganiZation/bumenInfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>主键id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>类型</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>部门名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "parentid",
            "description": "<p>父部门id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "sort",
            "description": "<p>排序</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrganiZation.php",
    "groupTitle": "OrganiZation"
  },
  {
    "type": "post",
    "url": "admin/OrganiZation/delOrgani",
    "title": "删除部门[admin/OrganiZation/delOrgani ]",
    "version": "1.0.0",
    "name": "delOrgani",
    "group": "OrganiZation",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrganiZation/delOrgani"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>主键id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrganiZation.php",
    "groupTitle": "OrganiZation"
  },
  {
    "type": "post",
    "url": "admin/OrganiZation/editOrgani",
    "title": "编辑组织结构[admin/OrganiZation/editOrgani ]",
    "version": "1.0.0",
    "name": "editOrgani",
    "group": "OrganiZation",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrganiZation/editOrgani"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>主键id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>部门名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "parentid",
            "description": "<p>父部门id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "sort",
            "description": "<p>排序</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrganiZation.php",
    "groupTitle": "OrganiZation"
  },
  {
    "type": "post",
    "url": "admin/OrganiZation/showDigui",
    "title": "递归查询组织结构[admin/OrganiZation/showDigui ]",
    "version": "1.0.0",
    "name": "showDigui",
    "group": "OrganiZation",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrganiZation/showDigui"
      }
    ],
    "filename": "application/admin/controller/OrganiZation.php",
    "groupTitle": "OrganiZation"
  },
  {
    "type": "post",
    "url": "admin/OrganiZation/strucList",
    "title": "组织结构列表[admin/OrganiZation/strucList]",
    "version": "1.0.0",
    "name": "strucList",
    "group": "OrganiZation",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/OrganiZation/strucList"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>主键id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>部门名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "parentid",
            "description": "<p>父亲部门id</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "sort",
            "description": "<p>排序</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "create_time",
            "description": "<p>录入时间</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/OrganiZation.php",
    "groupTitle": "OrganiZation"
  },
  {
    "type": "post",
    "url": "admin/Ransomer/dispatchDetails",
    "title": "赎楼派单详情页[admin/Ransomer/dispatchDetails]",
    "version": "1.0.0",
    "name": "dispatchDetails",
    "group": "Ransomer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Ransomer/dispatchDetails"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "orderSn",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>派单id(退回派单列表详情需要)</p>"
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "type",
            "description": "<p>订单类型</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n \"data\": {\n      \"basic_information\": {                      基本信息\n      \"order_sn\": \"JYDB2018050137123456\",    业务单号\n      \"stage\"      1002       订单状态\n      \"type\": \"JYDB\",        业务类型\n      \"finance_sn\": \"100000048\",      财务序号\n      \"guarantee_money\": \"2.00\",      担保金额\n      \"guarantee_per\": 2,            担保成数\n      \"financing_manager_id\": \"夏丽平\",    理财经理\n      \"financing_manager_id\": \"杜欣\",           部门经理\n      \"financing_dept_id\": \"总经办\"                   所属部门\n      \"is_dispatch_finance\":                   是否需要财务审核派单 0不需要1需要\n      \"guarantee_fee_status\": （担保费）收费状态 1未收齐 2已收齐\n      \"is_loan_finish\":                   银行放款是否完成 0未完成1完成\n      \"source_info\": \"万科地产\",          订单来源\n      \"order_source_str\": \"合作中介\",     来源机构\n      },\n      \"estate_info\": [   房产信息\n      {\n      \"estate_name\": \"国际新城一栋\",                  房产名称\n      \"estate_region\": \"深圳市|罗湖区|桂园街道\",      所属城区\n      \"estate_area\": 70,                             房产面积\n      \"estate_certtype\": 1,                          产证类型\n      \"estate_certnum\": 11111,                       产证编码\n      \"house_type\": 1                                房产类型 1分户 2分栋\n      },\n      {\n      \"estate_name\": \"国际新城一栋\",\n      \"estate_district\": \"440303\",\n      \"estate_area\": 70,\n      \"estate_certtype\": 1,\n      \"estate_certnum\": 11111,\n      \"house_type\": 1\n      }\n      ],\n      \"seller_info\": [    买方信息(is_seller = 1 && is_comborrower = 0) 买方共同借款人(is_seller = 1 && is_comborrower = 1)\n      {               卖方信息(is_seller = 2 && is_comborrower = 0) 卖方共同借款人(is_seller = 2 && is_comborrower = 1)\n      \"is_seller\": 2,\n      \"is_comborrower\": 0,           共同借款人属性 0借款人 1共同借款人\n      \"cname\": \"张三\",                 卖方姓名\n      \"ctype\": 1,                      卖方类型 1个人 2企业\n      \"certtype\": 1,                   证件类型\n      \"certcode\": \"11111122322\",       证件号码\n      \"mobile\": \"18825454079\",         电话号码\n      \"is_guarantee\": 0                 担保申请人 1是 0否\n      \"is_seller_str\": \"买方\",\n      \"is_comborrower_str\": \"买方共同借款人\",     所属角色\n      \"ctype_str\": \"个人\",                      卖方类型\n      \"certtype_str\": \"身份证\"                    证件类型\n      },\n      {\n      \"cname\": \"张三\",\n      \"ctype\": 1,\n      \"certtype\": 1,\n      \"certcode\": \"11111122322\",\n      \"mobile\": \"18825454079\",\n      \"is_guarantee\": 0\n      }\n      ],\n      \"sqk_info\": {                               首期款信息\n      \"dp_strike_price\": \"5900000.00\",             成交价格\n      \"dp_earnest_money\": \"80000.00\",             定金金额\n      \"dp_supervise_guarantee\": null,            担保公司监管\n      \"dp_supervise_buyer\": null,                 买方本人监管\n      \"dp_supervise_bank\": \"建设银行\",           监管银行\n      \"dp_supervise_bank_branch\": null,\n      \"dp_supervise_date\": \"2018-04-24\",         监管日期\n      \"dp_buy_way\": \"按揭购房\",                  购房方式\n      \"dp_now_mortgage\": \"5.00\"                  现按揭成数\n      },\n      \"mortgage_info\": [                               现按揭信息\n      {\n      \"type\": \"NOW\",\n      \"mortgage_type\": 1,\n      \"money\": \"900000.00\",                     现按揭金额\n      \"organization_type\": \"1\",\n      \"organization\": \"建设银行-深圳振兴支行\",     现按揭机构\n      \"mortgage_type_str\": \"公积金贷款\",           现按揭类型\n      \"organization_type_str\": \"银行\"         现按揭机构类型\n      },\n      {\n      \"type\": \"NOW\",\n      \"mortgage_type\": 2,\n      \"money\": \"2050000.00\",\n      \"organization_type\": \"1\",\n      \"organization\": \"建设银行-深圳振兴支行\",\n      \"mortgage_type_str\": \"商业贷款\",\n      \"organization_type_str\": \"银行\"\n      }\n      ],\n      \"preliminary_question\": [    风控初审问题汇总\n      {\n      \"describe\": \"呵呵456\",     问题描述\n      \"status\": 0               是否解决  0未解决 1已经解决\n      },\n      {\n      \"describe\": \"呵呵帅那个帅789\",\n      \"status\": 0\n      }\n      ],\n      \"needing_attention\": [   风控提醒注意事项\n      {\n      \"process_name\": \"收到公司的\",    来源\n      \"item\": \"啥打法是否\"             注意事项\n      },\n      {\n      \"process_name\": \"测试\",\n      \"item\": \"测试注意事项\"\n      }\n      ],\n      \"reimbursement_info\": [   银行账户信息\n      {\n      \"bankaccount\": \"张三\",   银行户名\n      \"accounttype\": 1,        账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它 6公司个人账户\n      \"bankcard\": \"111111\",    银行卡号\n      \"openbank\": \"中国银行\"    开户银行\n      \"type\": 2,\n      \"verify_card_status\": 0,\n      \"type_text\": \"尾款卡\",                  账号用途\n      \"verify_card_status_text\": \"已完成\",    核卡状态\n      \"accounttype_str\": \"卖方\"               账号归属\n      },\n      {\n      \"bankaccount\": \"李四\",\n      \"accounttype\": 5,\n      \"bankcard\": \"111\",\n      \"openbank\": \"工商银行\"\n      }\n      ],\n\n      \"advancemoney_info\": [    垫资费计算\n      {\n      \"advance_money\": \"650000.00\",     垫资金额\n      \"advance_day\": 30,               垫资天数\n      \"advance_rate\": 0.5,             垫资费率\n      \"advance_fee\": \"97500.0\",        垫资费\n      \"remark\": null,                  备注\n      \"id\": 115\n      }\n      ],\n\n      \"cost_account\":{     预收费用信息 与 实际费用入账\n      \"ac_guarantee_fee\": \"1000.00\",   实收担保费\n      \"ac_fee\": \"-15.00\",              手续费\n      \"ac_self_financing\": \"30.00\",    自筹金额(实际费用入账)\n      \"short_loan_interest\": \"-12.30\",   短贷利息\n      \"return_money\": \"12.50\",           赎楼返还款\n      \"default_interest\": \"0.00\",        罚息\n      \"overdue_money\": \"0.00\",           逾期费\n      \"exhibition_fee\": \"1000.00\",      展期费\n      \"transfer_fee\": \"10000.00\",       过账手续费\n      \"other_money\": \"0.00\"             其他\n      \"notarization\": \"2018-07-26\",     公正日期\n      \"money\": \"46465.00\",              担保金额(额度类)  垫资金额(现金类)\n      \"project_money_date\": null,       预计用款日\n      \"guarantee_per\": 0.35,            担保成数(垫资成数)\n      \"guarantee_rate\": 4,              担保费率\n      \"guarantee_fee\": \"225826007.64\",  预收担保费\n      \"account_per\": 41986.52,          出账成数\n      \"fee\": \"456456.00\",               预收手续费\n      \"self_financing\": \"30.00\",        自筹金额(预收费用信息)\n      \"info_fee\": \"0.00\",               预计信息费\n      \"total_fee\": \"226282463.64\",      预收费用合计\n      \"return_money_mode\": null,\n      \"return_money_amount\": 1425,      回款金额\n      \"turn_into_date\": null,           存入日期\n      \"turn_back_date\": null,           转回日期\n      \"chuzhangsummoney\": 5645650191    预计出账总额\n      \"return_money_mode_str\": \"直接回款\"   回款方式\n      },\n      \"lend_books\": [    银行放款入账\n      {\n      \" loan_money\": \"56786.00\",             放款金额\n      \"lender_object\": \"中国银行\",           放款银行\n      \"receivable_account\": \"中国银行账户\",    收款账户\n      \"into_money_time\": \"2019-11-03\",        到账时间\n      \"remark\": \"法国红酒狂欢节\",             备注说明\n      \"operation_name\": \"杜欣\"                入账人员\n      },\n      {\n      \" loan_money\": \"123456.00\",\n      \"lender_object\": \"中国银行\",\n      \"receivable_account\": \"中国银行账户\",\n      \"into_money_time\": \"2019-11-02\",\n      \"remark\": \"啊是的范德萨\",\n      \"operation_name\": \"杜欣\"\n      }\n      ],\n      \"arrears_info\": [    欠款及预计出账金额\n      {\n      \"ransom_status_text\": \"已完成\",       赎楼状态\n      \"ransomer\": \"朱碧莲\",         赎楼员\n      \"organization\": \"银行\",      欠款机构名称\n      \"interest_balance\": \"111111.11\",    欠款金额\n      \"mortgage_type_name\": \"商业贷款\",   欠款类型\n      \"accumulation_fund\": \"2.00\"         预计出账金额\n      }\n      {\n      \"organization\": \"银行\",\n      \"interest_balance\": \"111111.11\",\n      \"mortgage_type_name\": \"公积金贷款\",\n      \"accumulation_fund\": \"2.00\"\n      }\n      ],\n      \"fund_channel\": [                 资金渠道信息\n      {\n      \"fund_channel_name\": \"华安\",      资金渠道\n      \"money\": \"2000000.00\",             申请金额\n      \"actual_account_money\": \"2000000.00\",    实际入账金额\n      \"is_loan_finish\": 1,\n      \"trust_contract_num\": \"25421155\",         信托合同号\n      \"loan_day\": 5                             借款天数\n      }\n      \"status_info\": {        各种需要用到的其他字段\n      \"guarantee_fee_status\": 2,     （担保费）收费状态 1未收齐 2已收齐\n      \"loan_money_status\": 1,         银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理\n      \"instruct_status\": 3,           主表指令状态（1待申请 2待发送 3已发送）\n      \"is_loan_finish\": 1,             银行放款是否完成 0未完成 1已完成\n      \"loan_money\": \"4200000.00\",      银行放款入账(实收金额总计)  资金渠道信息(渠道实际入账总计)\n      \"com_loan_money\": null,\n      \"guarantee_fee\": 14526          垫资费计算(垫资费总计)\n      \"is_comborrower_sell\": 1       是否卖方有共同借款人 0否 1是\n      \"chile_instruct_status\"  3     子单指令状态（资金渠道表指令状态）\n      \"is_dispatch\": 1,               是否派单 0未派单 1已派单 2退回\n      \"endowmentsum\": 120000          资金渠道信息(垫资总计)\n      }\n      \"dispatch\": {        赎楼状态\n      \"ransom_type\": 2,     赎楼类型\n      \"ransomer\": 1,         赎楼员\n      \"ransom_bank\": 3,           赎楼银行\n      \"ransom_status\": 1,             当前状态\n      }\n      }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Ransomer.php",
    "groupTitle": "Ransomer"
  },
  {
    "type": "post",
    "url": "admin/Ransomer/index",
    "title": "赎楼正常派单列表[admin/Ransomer/index]",
    "version": "1.0.0",
    "name": "index",
    "group": "Ransomer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Ransomer/index"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>1已指派2待指派</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>暂时有JYDB一个类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_combined_loan",
            "description": "<p>是否组合贷1是0否</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>0不含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页显示数量</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Ransomer.php",
    "groupTitle": "Ransomer"
  },
  {
    "type": "post",
    "url": "admin/Ransomer/otherList",
    "title": "赎楼其他派单列表[admin/Ransomer/otherList]",
    "version": "1.0.0",
    "name": "otherList",
    "group": "Ransomer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Ransomer/otherList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>暂时有JYDB一个类型</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "is_combined_loan",
            "description": "<p>是否组合贷1是0否</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>0不含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页显示数量</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Ransomer.php",
    "groupTitle": "Ransomer"
  },
  {
    "type": "post",
    "url": "admin/Ransomer/returnDispatchList",
    "title": "赎楼退回派单列表[admin/Ransomer/returnDispatchList]",
    "version": "1.0.0",
    "name": "returnDispatchList",
    "group": "Ransomer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Ransomer/returnDispatchList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "search",
            "description": "<p>查询名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "managerId",
            "description": "<p>理财经理</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>暂时有JYDB一个类型 拿字典的数据，字典标识ORDER_TYPE</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "subordinates",
            "description": "<p>0不含下属1含下属</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransomType",
            "description": "<p>赎楼类型1公积金2商业贷款（原按揭信息没有消费贷）</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页显示数量</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Ransomer.php",
    "groupTitle": "Ransomer"
  },
  {
    "type": "post",
    "url": "admin/Ransomer/updateDispatch",
    "title": "退回派单指派[admin/Ransomer/updateDispatch]",
    "version": "1.0.0",
    "name": "updateDispatch",
    "group": "Ransomer",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Ransomer/updateDispatch"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>派单表id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ransomeId",
            "description": "<p>赎楼员id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "ransomer",
            "description": "<p>赎楼员</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Ransomer.php",
    "groupTitle": "Ransomer"
  },
  {
    "type": "post",
    "url": "admin/Regions/getBuildingCity",
    "title": "获取楼盘城市选择接口[admin/Regions/getBuildingCity]",
    "version": "1.0.0",
    "name": "getBuildingCity",
    "group": "Regions",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Regions/getBuildingCity"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>地区表id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Regions.php",
    "groupTitle": "Regions"
  },
  {
    "type": "post",
    "url": "admin/Regions/getCity",
    "title": "获取城市[admin/Regions/getCity]",
    "version": "1.0.0",
    "name": "getCity",
    "group": "Regions",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Regions/getCity"
      }
    ],
    "filename": "application/admin/controller/Regions.php",
    "groupTitle": "Regions"
  },
  {
    "type": "post",
    "url": "admin/Approval/add_Result",
    "title": "获取城区/片区[admin/Regions/getDistrict]",
    "version": "1.0.0",
    "name": "getDistrict",
    "group": "Regions",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Regions/getDistrict"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>地区表id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Regions.php",
    "groupTitle": "Regions"
  },
  {
    "type": "post",
    "url": "admin/Regions/getProvince",
    "title": "获取省接口[admin/Regions/getProvince]",
    "version": "1.0.0",
    "name": "getProvince",
    "group": "Regions",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Regions/getProvince"
      }
    ],
    "filename": "application/admin/controller/Regions.php",
    "groupTitle": "Regions"
  },
  {
    "type": "get",
    "url": "admin/SystemDept/getDowndept",
    "title": "获取下级部门[admin/SystemDept/getDowndept]",
    "version": "1.0.0",
    "name": "getDowndept",
    "group": "SystemDept",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/SystemDept/getDowndept"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>下级部门数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/SystemDept.php",
    "groupTitle": "SystemDept"
  },
  {
    "type": "get",
    "url": "admin/SystemDept/getTopdept",
    "title": "获取顶级部门[admin/SystemDept/getTopdept]",
    "version": "1.0.0",
    "name": "getTopdept",
    "group": "SystemDept",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/SystemDept/getTopdept"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>顶级部门数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/SystemDept.php",
    "groupTitle": "SystemDept"
  },
  {
    "type": "get",
    "url": "admin/SystemDept/getUpdept",
    "title": "获取上级部门[admin/SystemDept/getUpdept]",
    "version": "1.0.0",
    "name": "getUpdept",
    "group": "SystemDept",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/SystemDept/getUpdept"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>上级部门数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/SystemDept.php",
    "groupTitle": "SystemDept"
  },
  {
    "type": "post",
    "url": "admin/SystemDept/index",
    "title": "获取系统所有部门[admin/SystemDept/index]",
    "version": "1.0.0",
    "name": "index",
    "group": "SystemDept",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/SystemDept/index"
      }
    ],
    "filename": "application/admin/controller/SystemDept.php",
    "groupTitle": "SystemDept"
  },
  {
    "type": "get",
    "url": "admin/SystemPosition/getAllposition",
    "title": "模糊匹配岗位[admin/SystemPosition/getAllposition]",
    "version": "1.0.0",
    "name": "getAllposition",
    "group": "SystemPosition",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/SystemPosition/getAllposition"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>匹配岗位数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/SystemPosition.php",
    "groupTitle": "SystemPosition"
  },
  {
    "type": "get",
    "url": "admin/SystemUser/getDowndeptperson",
    "title": "选择上级主管（根据部门获取下级部门以及下级部门下面的所有人）[admin/SystemUser/getDowndeptperson]",
    "version": "1.0.0",
    "name": "getDowndeptperson",
    "group": "SystemUser",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/SystemUser/getDowndeptperson"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>部门id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/SystemUser.php",
    "groupTitle": "SystemUser"
  },
  {
    "type": "get",
    "url": "admin/SystemUser/managerList",
    "title": "模糊获取理财经理[admin/SystemUser/managerList]",
    "version": "1.0.0",
    "name": "managerList",
    "group": "SystemUser",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/SystemUser/managerList"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>客户经理姓名</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>客户经理列表</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/SystemUser.php",
    "groupTitle": "SystemUser"
  },
  {
    "type": "post",
    "url": "admin/System/getAllcompany",
    "title": "获取所有公司[admin/System/getAllcompany]",
    "version": "1.0.0",
    "name": "getAllcompany",
    "group": "System",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/System/getAllcompany"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>系统数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/System.php",
    "groupTitle": "System"
  },
  {
    "type": "get",
    "url": "admin/System/getAllsystem",
    "title": "获取所有后台系统[admin/System/getAllsystem]",
    "version": "1.0.0",
    "name": "getAllsystem",
    "group": "System",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/System/getAllsystem"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>系统数据集</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/System.php",
    "groupTitle": "System"
  },
  {
    "type": "post",
    "url": "admin/User/add",
    "title": "新增用户[admin/User/add]",
    "version": "1.0.0",
    "name": "add",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/add"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "groupid",
            "description": "<p>权限ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>真实姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "num",
            "description": "<p>工号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mail",
            "description": "<p>邮箱</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "ranking",
            "description": "<p>职位</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "gender",
            "description": "<p>性别（0未知 1 男 2女）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "deptpath",
            "description": "<p>部门全路径</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "superid",
            "description": "<p>上级主管id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "companyid",
            "description": "<p>公司id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "superman",
            "description": "<p>上级主管名字</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "admin/User/changeStatus",
    "title": "用户状态编辑[admin/User/changeStatus]",
    "version": "1.0.0",
    "name": "changeStatus",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/changeStatus"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>用户id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "admin/User/del",
    "title": "删除用户[admin/User/del]",
    "version": "1.0.0",
    "name": "del",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/del"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>用户编号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/User/edit",
    "title": "编辑用户[admin/User/edit]",
    "version": "1.0.0",
    "name": "edit",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/edit"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>用户id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "groupid",
            "description": "<p>权限ID</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>真实姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "num",
            "description": "<p>工号</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mail",
            "description": "<p>邮箱</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "ranking",
            "description": "<p>职位</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "gender",
            "description": "<p>性别（1 男 2女）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "deptpath",
            "description": "<p>部门全路径</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "superid",
            "description": "<p>上级主管id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "companyid",
            "description": "<p>上级主管id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "superman",
            "description": "<p>上级主管名字</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/User/getSyncUser",
    "title": "同步部门数据[admin/User/getSyncUser]",
    "version": "1.0.0",
    "name": "getSyncUser",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/getSyncUser"
      }
    ],
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/User/getSyncUser",
    "title": "同步用户数据[admin/User/getSyncUser]",
    "version": "1.0.0",
    "name": "getSyncUser",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/getSyncUser"
      }
    ],
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/User/getUserAuthList",
    "title": "授权获取用户[admin/User/getUserAuthList]",
    "version": "1.0.0",
    "name": "getUserAuthList",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/getUserAuthList"
      }
    ],
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/User/getUserinfo",
    "title": "获取用户信息[admin/User/getUserinfo]",
    "version": "1.0.0",
    "name": "getUserinfo",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/getUserinfo"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>用户id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>用户id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "username",
            "description": "<p>用户账号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "mail",
            "description": "<p>邮箱</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "num",
            "description": "<p>工号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>手机</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "ranking",
            "description": "<p>岗位</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "deptpath",
            "description": "<p>部门路径</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "surpath",
            "description": "<p>上级主管信息</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "groupid",
            "description": "<p>权限组id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "admin/User/getUsers",
    "title": "获取当前组的全部用户[admin/User/getUsers]",
    "version": "1.0.0",
    "name": "getUsers",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/getUsers"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "gid",
            "description": "<p>分组id</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "admin/User/index",
    "title": "获取用户列表[admin/User/index]",
    "version": "1.0.0",
    "name": "index",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/index"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "keywords",
            "description": "<p>关键字搜索</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "size",
            "description": "<p>条数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>用户状态</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>查询类别</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/User/newUserByDeptId",
    "title": "部门获取用户[admin/User/newUserByDeptId]",
    "version": "1.0.0",
    "name": "newUserByDeptId",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/newUserByDeptId"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "deptId",
            "description": "<p>部门id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "name",
            "description": "<p>用户名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/User/own",
    "title": "修改个人信息[admin/User/own]",
    "version": "1.0.0",
    "name": "own",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/own"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "headimg",
            "description": "<p>用户头像</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "oldPassword",
            "description": "<p>旧密码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "password",
            "description": "<p>新密码</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/User/userByDeptId",
    "title": "通过部门获取用户[admin/User/userByDeptId]",
    "version": "1.0.0",
    "name": "userByDeptId",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/userByDeptId"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "deptId",
            "description": "<p>部门id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "isManager",
            "description": "<p>是否部门经理 1代表查理财经理 0代表否</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "name",
            "description": "<p>用户名称</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/User/userSearch",
    "title": "模糊匹配用户获取部门[admin/User/userSearch]",
    "version": "1.0.0",
    "name": "userSearch",
    "group": "User",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/User/userSearch"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>用户编号</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/User.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "admin/Index/allPropertyNames",
    "title": "订单所有的房产名称和业主姓名[admin/Index/allPropertyNames]",
    "version": "1.0.0",
    "name": "allPropertyNames",
    "group": "public",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Index/allPropertyNames"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "order_sn",
            "description": "<p>订单编号</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n  {\n            \"code\": 1,\n            \"msg\": \"操作成功\",\n            \"data\": {\n                \"arr\": [\n                    {\n                    \"estate_name\": \"国际新城\",\n                    \"estate_owner\": \"赵六\"\n                    },\n                    {\n                    \"estate_name\": \"万达广场\",\n                    \"estate_owner\": \"李四\"\n                    }\n                ],\n            \"estateNameStr\": \"国际新城，万达广场\",\n            \"estateOwnerStr\": \"赵六，李四\"\n            }\n        }",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "arr",
            "optional": false,
            "field": "arr",
            "description": "<p>房产的名称和业主姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estateNameStr",
            "description": "<p>所有的房产名称拼接成的字符串</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "estateOwnerStr",
            "description": "<p>所有房产业主姓名拼接成的字符串</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Index.php",
    "groupTitle": "public"
  },
  {
    "type": "post",
    "url": "admin/Index/appFileUploads",
    "title": "APP多文件上传[admin/Index/appFileUploads]",
    "version": "1.0.0",
    "name": "appFileUploads",
    "group": "public",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Index/appFileUploads"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "image",
            "description": "<p>上传文件</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "idstr",
            "description": "<p>附件表主键id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n        \"code\": 1,\n        \"msg\": \"操作成功\",\n        \"data\": {\n        \"idstr\": \"927,928,929\"\n        }\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Appupload.php",
    "groupTitle": "public"
  },
  {
    "type": "post",
    "url": "admin/Index/app_Uploads",
    "title": "base64多文件上传[admin/Index/app_Uploads]",
    "version": "1.0.0",
    "name": "app_Uploads",
    "group": "public",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Index/app_Uploads"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "image",
            "description": "<p>base64文件</p>"
          },
          {
            "group": "Parameter",
            "type": "arr",
            "optional": false,
            "field": "oldImageName",
            "description": "<p>原文件名称</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "idstr",
            "description": "<p>附件表主键id</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n{\n        \"code\": 1,\n        \"msg\": \"操作成功\",\n        \"data\": {\n                 \"idstr\": \"927,928,929\"\n                }\n        }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Appupload.php",
    "groupTitle": "public"
  },
  {
    "type": "post",
    "url": "admin/Index/companyAgency",
    "title": "获取合作中介[admin/Index/companyAgency]",
    "version": "1.0.0",
    "name": "companyAgency",
    "group": "public",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Index/companyAgency"
      }
    ],
    "filename": "application/admin/controller/Index.php",
    "groupTitle": "public"
  },
  {
    "type": "post",
    "url": "admin/Index/fileUpload",
    "title": "图片上传[admin/Index/fileUpload]",
    "version": "1.0.0",
    "name": "fileUpload",
    "group": "public",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Index/fileUpload"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "pic",
            "description": "<p>文件文件</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "url",
            "description": "<p>文件链接地址</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>文件原始名称</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>附件表主键id</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "thumb_url",
            "description": "<p>缩略图的地址(上传图片才返回该字段)</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Index.php",
    "groupTitle": "public"
  },
  {
    "type": "post",
    "url": "admin/Index/getBanks",
    "title": "获取所有银行[admin/Index/getBanks]",
    "version": "1.0.0",
    "name": "getBanks",
    "group": "public",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Index/getBanks"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "返回数据示例:",
          "content": "HTTP/1.1 200 OK\n  {\n    \"code\": 1,\n    \"msg\": \"操作成功\",\n    \"data\": [\n        {\n        \"id\": \"101\",\n        \"bank_name\": \"中国银行\"\n        },\n        {\n        \"id\": \"101001\",\n        \"bank_name\": \"深圳福田支行\"\n        }\n    }",
          "type": "json"
        }
      ]
    },
    "filename": "application/admin/controller/Index.php",
    "groupTitle": "public"
  },
  {
    "type": "get",
    "url": "admin/Index/jumpBuilding",
    "title": "跳转到楼盘字典[admin/Index/jumpBuilding]",
    "version": "1.0.0",
    "name": "jumpBuilding",
    "group": "public",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Index/jumpBuilding"
      }
    ],
    "filename": "application/admin/controller/Index.php",
    "groupTitle": "public"
  },
  {
    "type": "post",
    "url": "admin/Index/logOut",
    "title": "单点登录,从其他系统退出登录接口[admin/Index/logOut]",
    "version": "1.0.0",
    "name": "logOut",
    "group": "public",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Index/logOut"
      }
    ],
    "filename": "application/admin/controller/Index.php",
    "groupTitle": "public"
  },
  {
    "type": "post",
    "url": "admin/Index/verifyTheLogin",
    "title": "验证登录[admin/Index/verifyTheLogin]",
    "version": "1.0.0",
    "name": "verifyTheLogin",
    "group": "public",
    "sampleRequest": [
      {
        "url": "http://www.busys.com/admin/Index/verifyTheLogin"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "ApiAuth",
            "description": "<p>接口秘钥</p>"
          }
        ]
      }
    },
    "filename": "application/admin/controller/Index.php",
    "groupTitle": "public"
  }
] });
