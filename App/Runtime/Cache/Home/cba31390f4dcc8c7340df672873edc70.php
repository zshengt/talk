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
                	    <li class="disabled"><a>1. 环境检测</a></li>
                	    <li class="disabled"><a>2. 创建数据库</a></li>
                	    <li class="disabled active"><a>4. 完成安装</a></li>
                	</ul>
                	<div class="alert alert-success">
                	    <p>恭喜，系统已安装成功！</p>
                        <p><a  href="<?php echo U('topic/index');?>">访问前台</p>
                        <p><a  href="<?php echo U('admin/public/login');?>">访问后台</p>
                	</div>
                	<a class="btn btn-primary pull-right" href="<?php echo U('topic/index');?>">完成创建</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>