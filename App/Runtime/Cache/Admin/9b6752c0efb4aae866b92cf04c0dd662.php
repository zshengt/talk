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
                <h1 class="page-header">话题列表</h1>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>标题</th>
                            <th>作者</th>
                            <th>创建时间</th>
                            <td>回复列表</td>
                            <td>推荐</td>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($topics["data"])): $i = 0; $__LIST__ = $topics["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$topic): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($topic["tid"]); ?></td>
                            <td><a href="<?php echo U('home/topic/detail', array('tid'=>$topic['tid']));?>" target="_blank"><?php echo ($topic["subject"]); ?></a></td>
                            <td><?php echo ($topic["username"]); ?></td>
                            <td><?php echo friendly_date($topic['create_time']);?></td>
                            <td><a href="<?php echo U('topic/posts',array('tid'=>$topic['tid']));?>">查看回复</a></td>
                            <td>
                                <?php if(($topic["is_stick"]) == "1"): ?><a href="<?php echo U('topic/unstick',array('tid'=>$topic['tid']));?>">取消推荐</a>
                                <?php else: ?>
                                    <a href="<?php echo U('topic/stick',array('tid'=>$topic['tid']));?>">推荐</a><?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo U('topic/del',array('tid'=>$topic['tid']));?>"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <?php echo ($topics["page"]); ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>