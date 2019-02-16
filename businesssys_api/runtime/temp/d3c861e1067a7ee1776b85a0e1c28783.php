<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"F:\phpstudy\WWW\businesssys_api\public/../application/wiki\view\index\login.html";i:1523869188;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title><?php echo config('apiBusiness.APP_NAME'); ?> - 在线接口文档</title>
    <link href="https://cdn.bootcss.com/semantic-ui/2.2.11/semantic.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>

    <script src="https://cdn.bootcss.com/semantic-ui/2.2.11/components/form.js"></script>
    <script src="https://cdn.bootcss.com/semantic-ui/2.2.11/components/transition.js"></script>
    <style type="text/css">
        body {
            background-color: #DADADA;
        }
        
        body>.grid {
            height: 100%;
        }
        
        .image {
            margin-top: -100px;
        }
        
        .column {
            max-width: 450px;
        }
    </style>

    <script>
        $(document)
            .ready(function() {
                $('.ui.form')
                    .form({
                        fields: {
                            email: {
                                identifier: 'appId',
                                rules: [{
                                    type: 'empty',
                                    prompt: 'AppId不能为空'
                                }]
                            },
                            password: {
                                identifier: 'appSecret',
                                rules: [{
                                    type: 'empty',
                                    prompt: 'AppSecret不能为空'
                                }]
                            }
                        }
                    });
            });
    </script>
</head>

<body>

    <div class="ui middle aligned center aligned grid">
        <div class="column">
            <h2 class="ui teal image header">
                <div class="content">
                    欢迎使用<?php echo config('apiBusiness.APP_NAME'); ?>在线文档
                </div>
            </h2>
            <form class="ui large form" method="post" action="<?php echo url('/wiki/doLogin'); ?>">
                <div class="ui stacked segment">
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="user icon"></i>
                            <input type="text" name="appId" placeholder="请输入您的AppId">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="lock icon"></i>
                            <input type="password" name="appSecret" placeholder="请输入您的AppSecret">
                        </div>
                    </div>
                    <div class="ui fluid large teal submit button">提 交</div>
                </div>
                <div class="ui error message"></div>
            </form>
            <div class="ui message">
                如果您没有AppId和AppSecret，请联系服务供应商获取！
            </div>
        </div>
    </div>
</body>

</html>