<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <title>TalkPiece开源垂直社区 安装程序- Powered by TalkPiece</title>
    <link href="__PUBLIC__/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="__PUBLIC__/css/font-awesome/css/font-awesome.min.css">
    <style type="text/css">
        html, body {
            color: #333;
            background-color: #eee;
        }
        .container{
            max-width: 970px;
            margin-top: 50px;
        }
        .talk-install{
            margin-bottom: 50px;
        }
    </style>
</head>


<body>
    <div class="container">
        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                    <h3>TalkPiece垂直社区安装向导</h3>
                </div>
                <div class="panel-body">
					<ul class="nav nav-pills nav-justified talk-install">
					    <li class="disabled"><a>1. 环境检测</a>
					    </li>
					    <li class="disabled active"><a>2. 创建数据库</a>
					    </li>
					    <li class="disabled "><a>3. 完成安装</a>
					    </li>
					</ul>
					<form class="form-horizontal" action="<?php echo U('install/step2');?>"  method="post">
					    <fieldset> 
					        <div class="form-group">
					            <label class="col-md-2 control-label" for="dbhost">数据库服务器</label>
					            <div class="col-md-4">
					              <input type="text" class="form-control"  name="dbhost"   value="127.0.0.1">
					            </div>
					        </div>
					       <div class="form-group">
					           <label class="col-md-2 control-label" for="dbname">数据库名：</label>
					           <div class="col-md-4">
					               <input type="text" class="form-control" name="dbname" value=""  >
					           </div>
					       </div>
					        <div class="form-group">
					            <label class="col-md-2 control-label" for="dbuser">数据库用户名：</label>
					            <div class="col-md-4">
					                <input type="text" class="form-control" name="dbuser" value="" >
					            </div>
					        </div>
					        <div class="form-group">
					            <label class="col-md-2 control-label" for="dbpwd">数据库密码：</label>
					            <div class="col-md-4">
					                <input type="text" class="form-control" name="dbpwd" value="" >
					            </div>
					        </div>
					        <div class="form-group">
					            <label class="col-md-2 control-label" for="dbport">数据库端口：</label>
					            <div class="col-md-4">
					                <input type="text" class="form-control" name="dbport" value="3306" >
					            </div>
					        </div>
                            <hr>

                            <div class="form-group">
                                <label class="col-md-2 control-label">创始人Email：</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="email" value="" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">用户名：</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="username" value=""  >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">密码：</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="password" value="">
                                </div>
                            </div>
					        <div class="form-group">
					            <div class="col-md-offset-2 col-md-6">
					                <button type="submit" class="btn btn-primary">创建数据库</button>
					            </div>
					        </div>
					    </fieldset>
					</form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>