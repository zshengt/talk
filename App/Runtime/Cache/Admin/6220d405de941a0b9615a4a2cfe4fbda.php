<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>TalkPiece 后台管理</title>
	<link href="__PUBLIC__/css/bootstrap.css" rel="stylesheet">
	<link href="__PUBLIC__/css/admin.css" rel="stylesheet">
	<link rel="stylesheet" href="__PUBLIC__/css/font-awesome/css/font-awesome.min.css">
	<!--[if lt IE 9]>
	  	<script src="assets/js/html5.js"></script>
		<script src="assets/js/css3.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
    <div class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">TalkPiece</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?php echo U('home/topic/index');?>"><i class="fa fa-cloud"></i> 返回社区</a></li>
                    <li><a href="<?php echo U('public/logout');?>">退出</a></li>
                </ul>
            </div>
        </div>
    </div>
   
    <div class="container-fluid">
        <div class="row">
           <div class="sidebar col-sm-1 col-md-2">
    
    <ul class="nav nav-sidebar">
        <li><a href="<?php echo U('index/index');?>"><i class="fa fa-bar-chart-o"></i>控制面板</a></li>
        <li>
            <a href=""><i class="fa fa-bar-chart-o"></i>系统设置</a>
            <ul class="sub-menu ">
                <li><a href="<?php echo U('settings/index');?>">基本设置</a></li>
                <li><a href="<?php echo U('settings/email');?>">邮件设置</a></li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-bar-chart-o"></i>用户</a>
            <ul class="sub-menu ">
                <li>
                    <a href="<?php echo U('user/index');?>">用户列表</a>
                </li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-eye"></i> 话题</a>
            <ul>
                <li>
                    <a href="<?php echo U('topic/index');?>">话题列表</a>
                </li>
                <li>
                    <a href="<?php echo U('topic/category');?>">分类列表</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
            <div class="col-sm-11  col-md-10 main">
                <h3>站点设置</h3>
                <form class="form-horizontal" action="<?php echo U('settings/editEmail');?>" method="post">
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name">邮件注册：</label>
                        <div class="col-md-4">
                            <input type="radio" name="email_open" value="1" 
                                <?php if(($email["email_open"]) == "1"): ?>checked="checked"<?php endif; ?> >开启
                            <input type="radio" name="email_open" value="0" 
                                <?php if(($email["email_open"]) == "0"): ?>checked="checked"<?php endif; ?>>关闭
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name">SMTP服务器地址：</label>
                        <div class="col-md-4">
                            <input type="text" name="smtp_host" class="form-control" value="<?php echo ($email['smtp_host']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name">端口：</label>
                        <div class="col-md-4">
                            <input type="text" name="smtp_port" class="form-control" value="<?php echo ($email['smtp_port']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name">用户名：</label>
                        <div class="col-md-4">
                            <input type="text" name="smtp_user" class="form-control" value="<?php echo ($email['smtp_user']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name">密码：</label>
                        <div class="col-md-4">
                            <input type="text" name="smtp_pwd" class="form-control" value="<?php echo ($email['smtp_pwd']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name">发件人名称：</label>
                        <div class="col-md-4">
                            <input type="text" name="from_name" class="form-control" value="<?php echo ($email['from_name']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="name">发件人邮箱：</label>
                        <div class="col-md-4">
                            <input type="text" name="from_email" class="form-control" value="<?php echo ($email['from_email']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-4">
                          <button type="submit" class="btn btn-primary">更新</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>