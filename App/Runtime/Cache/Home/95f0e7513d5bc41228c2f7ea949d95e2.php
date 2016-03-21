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
            <li><a href="<?php echo U('topic/index');?>">话题</a></li>
            <li><a href="<?php echo U('topic/category',array('cid'=>$topic['cid']));?>"><?php echo ($topic['catename']); ?></a></li>
            <li class="active"><?php echo ($topic['subject']); ?></li>
        </ol>
        <div class="col-md-9">
            <div class="panel">
                <div class="panel-heading">
                    <h4><?php echo ($topic['subject']); ?></h4>
                </div>
                <div class="panel-body">
                    <ul class="media-list talk-posts" id="talk-posts">
                        <?php if(is_array($topic["post_lists"]["data"])): $i = 0; $__LIST__ = $topic["post_lists"]["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="media" id="post-<?php echo ($vo["pid"]); ?>">
                            <a href="<?php echo U('user/index', array('uid'=>$vo['uid']));?>" class="pull-left">
                               <img src="<?php echo uavatar($vo['uid']);?>" class="talk-avatar middle">
                            </a>
                            <div class="media-body">
                                <?php if(($mid) > "0"): ?><div class="pull-right extra">
                                    <?php if($vo['uid'] != $mid): ?><a href="javascript:;"  id="post-quote"  onclick="replyPost('<?php echo ($vo["username"]); ?>')" ><i class="fa fa-reply"></i></a><?php endif; ?>
                                    <?php if(($vo['uid'] == $mid) AND ($vo['first'] == 0)): ?><a href="javascript:;" id="control-delete" onclick="deletePost('<?php echo ($vo["pid"]); ?>')" ><i class="fa fa-trash-o"></i></a><?php endif; ?>
                                </div><?php endif; ?>
                                <h4 class="media-heading">
                                    <span class="username"><a href="<?php echo U('user/index',array('uid'=>$vo['uid']));?>"><?php echo ($vo['username']); ?></a></span>
                                    <small class="timeago" original-title="<?php echo date('Y年m月d日 H:i:s', $vo['create_time']);?>"><?php echo friendly_date($vo['create_time']);?></small>
                                </h4>
                                <div class="content">
                                    <?php echo ($vo['content']); ?>
                                </div>
                            </div>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        <div class="text-center">
                            <?php echo ($topic['post_lists']['page']); ?>
                        </div>
                    </ul>
                    <script id="posts" type="text/x-jquery-tmpl">
                        <li class="media">
                            <a href="${space}" class="pull-left">
                               <img src="${avatar}" class="talk-avatar" width="50" height="50">
                            </a>
                            <div class="media-body">
                                <div class="pull-right extra">
                                    <a href="javascript:;" id="post-delete" onclick="delete()" title="删除"><i class="fa fa-trash-o"></i></a>
                                </div>
                                <h5 class="media-heading">
                                    <span class="username"><a href="${space}">${username}</a></span>
                                    <small class="timeago">刚刚</small>
                                </h5>
                                <div class="content">
                                    ${content}                        
                                </div>
                            </div>
                        </li>
                    </script>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <?php if(($mid) > "0"): ?><h3>说点什么</h3>
                    <form action="" method="post" id="reply">
                        <div class="form-group">
                            <textarea id="comment"  class="form-control" name="content" rows="5" ></textarea>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="tid" value="<?php echo ($topic['tid']); ?>" id="tid">
                            <button type="submit" class="btn btn-primary pull-right">回复</button>
                        </div>
                    </form>
                    <?php else: ?>
                         <p class="text-center"><a href="<?php echo U('user/login');?>">登录</a>或者<a href="<?php echo U('user/signup');?>">注册</a></p><?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <?php if($topic['uid'] == $mid): ?><div class="panel">
                <div class="panel-body text-center">
                    <a href="<?php echo U('topic/edit',array('tid'=>$topic['tid']));?>" class="btn btn-success btn-block">编辑话题</a>
                    <a  href="javascript:;" onclick="del(<?php echo ($topic['tid']); ?>)" class="btn btn-danger btn-block">删除话题</a>
                </div>
            </div><?php endif; ?>
            <?php if(!empty($relation_topics)): ?><div class="panel">
                <div class="panel-heading">
                    相关话题
                </div>
                <ul class="list-group">
                    <?php if(is_array($relation_topics)): $i = 0; $__LIST__ = $relation_topics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="list-group-item"><a href="<?php echo U('topic/detail',array('tid'=>$vo['tid']));?>"><?php echo ($vo["subject"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div><?php endif; ?>
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
        $("#comment").keydown(function(e){
            if(e.ctrlKey && e.which == 13 || e.which == 10) {
                $("#reply").submit();
            }
        });

        $("#reply").on('submit', function(e) {
            e.preventDefault();
            var tid  = $('#tid').val();
            var content = $.trim($('#comment').val());
            if (content == '') {
                bootbox.alert('内容不能为空');
                return false;
            }

            $.ajax({
                url: "<?php echo U('topic/reply');?>",
                type: 'POST',
                dataType: 'json',
                data: {tid: tid, content: content},
                success :function(data) {
                    if (data.status ==0) {
                        bootbox.alert(data.info);
                        return false;
                    } else {
                        var html = $( "#posts" ).tmpl( data ).appendTo( "#talk-posts" );
                        $('#comment').val('');
                    }
                }
            });


        });

    });
    function  del(tid){
        bootbox.confirm("确定要删除主题吗?", function(result) {
            if (result) {
                $.ajax({
                      type: "POST",
                      url: "<?php echo U('topic/del');?>",
                      data: {tid: tid},
                      success:function(data){
                          if (data.status == 0) {
                              bootbox.alert(data.info);
                              return  false;
                          } else {
                              window.location.href = data.url;
                          }
                      }
                });
            }
        });
    }

    function  deletePost(pid){
        bootbox.confirm("确定要删除回复吗?", function(result) {
            if (result) {
                $.ajax({
                      type: "POST",
                      url: "<?php echo U('topic/delPost');?>",
                      data: {pid: pid},
                      success:function(msg){
                          $("#post-"+pid).slideUp(200);
                      }
                });
            }
        });
    }

    function  replyPost(name){
        var name  = '@' + name +  ' ';
        $('#comment').insertAtCaret(name);
    }

</script>
</body>
</html>