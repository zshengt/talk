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
		<div class="col-md-12">
			<div class="panel">
				<div class="panel-body">
	<div class="col-md-2">
		<img src="<?php echo uavatar($user_list['uid'], 'big');?>" class="talk-avatar big">
	</div>
	<div class="col-md-5">
		<div class="talk-user">
			<h3><?php echo ($user_list['username']); ?></h3>
			<p>
				<?php if(!empty($$user_list["area"])): ?><i class="fa fa-location-arrow"></i> <?php echo ($user_list["area"]); endif; ?> 
				<?php if(!empty($$user_list["intro"])): ?><i class="fa fa-stack-exchange"></i> <?php echo ($user_list["intro"]); else: ?>还没有个人介绍<?php endif; ?>
			</p>
			<?php if(($mid > 0) AND ( $uid != $mid)): ?><div class="profile-action">
					<?php if(($is_follow) == "1"): ?><a href="javascript:;" class="btn btn-success" id="unfollow" data-uid="<?php echo ($user_list["uid"]); ?>">取消关注</a>
					<?php else: ?>
						<a href="javascript:;" class="btn btn-default" id="follow" data-uid="<?php echo ($user_list["uid"]); ?>">关注</a><?php endif; ?>
					<button class="btn btn-primary" data-toggle="modal">私信</button>
				</div><?php endif; ?>
		</div>
	</div>
	<div class="col-md-3">
		<ul class="user-fans pull-right">
			<li>
				<a href="<?php echo U('user/followers');?>">
					<strong><?php echo ($fans_num); ?></strong>
					<span class="word">关注</span>
				</a>
			</li>
			<li>
				<a href="<?php echo U('user/fans');?>">
					<strong><?php echo ($follow_num); ?></strong>
					<span class="word">粉丝</span>
				</a>
			</li>
		</ul>
	</div>
</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="user-action panel">
				<ul class="nav nav-tabs">
					<li><a href="<?php echo U('user/index', array('uid'=>$user_list['uid']));?>">话题</a></li>
					<li><a href="<?php echo U('user/followers', array('uid'=>$user_list['uid']));?>">关注</a></li>
					<li class="active"><a href="<?php echo U('user/fans',array('uid'=>$user_list['uid']));?>">粉丝</a></li>
				</ul>
				<div class="tab-content">
					<?php if(!empty($fans)): ?><ul class="media-list">
						<?php if(is_array($fans)): $i = 0; $__LIST__ = $fans;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fan): $mod = ($i % 2 );++$i;?><li class="media">
								<a class="pull-left" href="<?php echo U('user/index',array('uid'=>$fan['from_uid']));?>">
									<img src="<?php echo uavatar($fan['from_uid']);?>" class="talk-avatar middle">
								</a>
								<div class="media-body">
									<h4 class="media-heading"><a href="<?php echo U('user/index',array('uid'=>$fan['from_uid']));?>"><?php echo ($fan['username']); ?></a></h4>
									<p><?php echo ($fan['intro']); ?></p>
								</div>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
					<?php else: ?>
					还没有粉丝<?php endif; ?>
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
<script>
	$(function(){
		$('[data-toggle="modal"]').on('click', function(e) {
				$('#message').modal();
			});
		$('#send-message').on('submit',function(e) {
			e.preventDefault();
			var touid = $('#touid').val(),
			    content =$.trim($('#content').val());
			if (content == '') {
				$('#error').html('内容不能为空!');
				return  false;
			}
			$.ajax({
				url: '<?php echo U('message/send');?>',
				type: 'post',
				dataType: 'json',
				data: { touid:touid, content:content},
				success: function(data) {
					$('#content').val('');
					$('#message').modal('hide');
				}
			});

		});

		$('#follow').on('click', function(e) {
			var touid = $(this).attr('data-uid');
			$.ajax({
				url: '<?php echo U("user/follow");?>',
				type: 'POST',
				dataType: 'json',
				data: { touid: touid},
				success: function(data) {
					if (data.status ==0) {
						bootbox.alert(data.info);
					} else {
						location.reload();
					}
				}
			});
			
		});

		$('#unfollow').on('click', function(e) {
			var touid = $(this).attr('data-uid');
			$.ajax({
				url: '<?php echo U("user/unfollow");?>',
				type: 'POST',
				dataType: 'json',
				data: { touid: touid},
				success: function(data) {
					if (data.status ==0) {
						bootbox.alert(data.info);
					} else {
						location.reload();
					}
				}
			});
		});
	});
</script>
</body>
</html>