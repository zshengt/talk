<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<title><?php echo ($title); ?>  Powered by welinked</title>
<meta name="keywords" content="<?php echo C('web_keywords');?>" />
<meta name="description" content="<?php echo C('web_des');?>" />
<link href="__PUBLIC__/css/bootstrap.css" rel="stylesheet">
<link href="__PUBLIC__/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="__PUBLIC__/css/font-awesome/css/font-awesome.min.css">
<!--[if lt IE 9]>
<script src="__PUBLIC__/js/html5.js"></script>
<script src="__PUBLIC__/js/css3.js"></script>
<script src="__PUBLIC__/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div class="navbar navbar-default navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle needsclick" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand logo" href="<?php echo U('topic/index');?>"><img class="logo" src="__PUBLIC__/img/logo.png"/></a>
			<a class="navbar-brand" href="<?php echo U('topic/index');?>">首页</a>
		</div>
	    <div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="<?php echo U('topic/index');?>">话题</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right ">
				<?php if(($mid) > "0"): ?><li>
						<a href="<?php echo U('user/index', array('uid'=>$user['uid']));?>">
							<?php echo ($user["username"]); ?>
						</a>
					</li>
					<li class="notify">
						<a href="<?php echo U('message/notify');?>">
							<i class="fa fa-bell"></i>
							<?php if(($user["at_num"]) > "0"): ?><span class="badge" id="notify"><?php echo ($user["at_num"]); ?></span><?php endif; ?>
						</a>
					</li>
					<li class="inbox">
						<a href="<?php echo U('message/index');?>">
							<i class="fa fa-envelope-o"></i>
							<?php if(($user["inbox_num"]) > "0"): ?><span class="badge" id="inbox"><?php echo ($user["inbox_num"]); ?></span><?php endif; ?>
						</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-cog"></i>
						</a>
						<ul class="dropdown-menu" role="menu">
			                <li><a href="<?php echo U('account/settings');?>"><i class="fa fa-user"></i>账号设置</a></li>
			                <li class="divider"></li>
            				<?php if(($user["is_admin"]) == "1"): ?><li><a href="<?php echo U('admin/public/login');?>"><i class="fa fa-user"></i>后台管理</a></li>
			               	<li class="divider"></li><?php endif; ?>		
			                <li><a href="<?php echo U('user/logout');?>"><i class="fa fa-power-off"></i>退出</a></li>
			            </ul>
					</li>
				<?php else: ?>
					<li><a href="<?php echo U('user/signup');?>">注册</a></li>
					<li><a href="<?php echo U('user/login');?>">登录</a></li><?php endif; ?>
			</ul>
	    </div>
	</div>
</div>



<div class="container">
    <div class="row">
        <ol class="breadcrumb">
            <li class="active">话题</li>
        </ol>
        <div class="col-md-9">
            <div class="panel panel-default">
                <?php if(!empty($stick_topics)): ?><div class="panel-heading">推荐话题</div>
                <div class="panel-body">
                    <ul class="media-list talk-topic">
                        <?php if(is_array($stick_topics)): $i = 0; $__LIST__ = $stick_topics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$stick): $mod = ($i % 2 );++$i;?><li class="media">
                                <a href="<?php echo U('user/index', array('uid'=>$stick['uid']));?>" class="pull-left">
                                   <img src="<?php echo uavatar($stick['uid']);?>" class="talk-avatar middle">
                                </a>
                                <div class="media-body">
                                    <?php if(($stick["post_num"]) > "0"): ?><div class="badge pull-right"><?php echo ($stick['post_num']); ?></div><?php endif; ?>
                                    <h4 class="media-heading">
                                        <a href="<?php echo U('topic/detail', array('tid'=>$stick['tid']));?>"> <?php echo ($stick['subject']); ?></a>
                                    </h4>
                                    <div class="extra">
                                        <span class="category">
                                           <a href="<?php echo U('topic/category', array('cid'=>$stick['cid']));?>"><?php echo ($stick['cate_list']['name']); ?></a>
                                        </span>
                                        <span class="slant">•</span>
                                        <span class="username">
                                           <a href="<?php echo U('user/index', array('uid'=>$stick['uid']));?>"><?php echo ($stick['username']); ?></a>
                                        </span>
                                        <span class="slant">•</span>
                                        <small class="timeago" original-title="">发布于 <?php echo friendly_date($stick['create_time']);?></small>
                                    </div>
                                </div>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div><?php endif; ?>
                <div class="panel-heading">最新话题</div>
                <div class="panel-body">
                    <?php if(!empty($topics["data"])): ?><ul class="media-list talk-topic">
                        <?php if(is_array($topics["data"])): $i = 0; $__LIST__ = $topics["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="media">
                            <a href="<?php echo U('user/index', array('uid'=>$vo['uid']));?>" class="pull-left">
                               <img src="<?php echo uavatar($vo['uid']);?>" class="talk-avatar middle">
                            </a>
                            <div class="media-body">
                                <?php if(($vo["post_num"]) > "0"): ?><div class="badge pull-right"><?php echo ($vo['post_num']); ?></div><?php endif; ?>
                                <h4 class="media-heading"><a href="<?php echo U('topic/detail', array('tid'=>$vo['tid']));?>"> <?php echo ($vo['subject']); ?></a></h4>
                                <div class="extra">
                                    <span class="category">
                                       <a href="<?php echo U('topic/category', array('cid'=>$vo['cid']));?>"><?php echo ($vo['cate_list']['name']); ?></a>
                                    </span>
                                    <span class="slant">•</span>
                                    <span class="username">
                                       <a href="<?php echo U('user/index', array('uid'=>$vo['uid']));?>"><?php echo ($vo['username']); ?></a>
                                    </span>
                                    <span class="slant">•</span>
                                    <small class="timeago" original-title="">发布于 <?php echo friendly_date($vo['create_time']);?></small>
                                </div>
                            </div>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <div class="text-center">
                        <?php echo ($topics['page']); ?>
                    </div>
                    <?php else: ?>
                        <span>还没有话题</span><?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <?php if(($mid) > "0"): ?><div class="panel">
                <div class="panel-body text-center">
                    <a href="<?php echo U('topic/add');?>" class="btn btn-success btn-block">发表新话题</a>
                </div>
            </div><?php endif; ?>
            <div class="panel">
                <div class="panel-heading">
                    全部分类
                </div>
                <div class="panel-body">
                    <?php if(!empty($cate_lists)): ?><ul class="nav nav-pills nav-stacked">
                        <?php if(is_array($cate_lists)): $i = 0; $__LIST__ = $cate_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                               <a href="<?php echo U('topic/category',array('cid'=>$vo['id']));?>"><?php echo ($vo['name']); ?></a>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <?php else: ?>
                    <span>还没有分类</span><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="#" class="scroll-up hidden-xs">回到顶部</a>
<footer>
	<div class="container">
		<div class="row">
			<p><?php echo C('web_copyright');?></p>
		</div>
	</div>
</footer>
<script  src="__PUBLIC__/js/jquery.js"></script>
<script  src="__PUBLIC__/js/jquery-migrate.js"></script>
<script  src="__PUBLIC__/js/jquery.validate.js"></script>
<script  src="__PUBLIC__/js/bootstrap.js"></script>
<script  src="__PUBLIC__/js/bootbox.js"></script>
<script  src="__PUBLIC__/js/jquery.tmpl.js"></script>
<script  src="__PUBLIC__/js/main.js"></script>
<?php if(($mid) > "0"): ?><script>
	$(function(){
		var getUnread =  function(){
			$.get("<?php echo U('message/unread');?>", function(data){
			 	if (data.at_num >0) {
			 		$('#notify').show().text(data.at_num);
			 	} 
			 	if (data.inbox_num >0) {
			 		$('#inbox').show().text(data.inbox_num);
			 	}
			 	
			},'json');
		};
		setInterval(getUnread, 50000);
	});
</script><?php endif; ?>
</body>
</html>