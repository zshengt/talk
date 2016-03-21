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
            <li class="active">编辑话题</li>
        </ol>
        <div class="col-md-9">
            <div class="panel">
                <div class="panel-heading">
                    <h3>编辑话题</h3>
                </div>
                <div class="panel-body">
                    <form id="talk-edit" action="" method="post" class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <select name="cid" id="cid" class="form-control">
                                        <option value="">选择分类</option>
                                        <?php if(is_array($cate_lists)): $i = 0; $__LIST__ = $cate_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $topic['cid']): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="text" id="subject" name="subject" class="form-control input-lg" value="<?php echo ($topic["subject"]); ?>" placeholder="标题">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 hidden-xs">
                                    <script type="text/plain" id="editor" style="width:660px;height:350px;"></script>
                                </div>
                                <!-- <div class="col-md-8">
                                    <textarea class="form-control" rows="15" cols="20" name="content"></textarea>
                                </div> -->
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <input  type="hidden" name="tid"  value="<?php echo ($topic["tid"]); ?>">
                                    <button type="submit" class="btn btn-primary" id="create">更新</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
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
<link href="__PUBLIC__/js/editor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script src="__PUBLIC__/js/editor/umeditor.config.js"></script>
<script src="__PUBLIC__/js/editor/umeditor.min.js"></script>
<script src="__PUBLIC__/js/editor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
    $(function() {
        var um = UM.getEditor('editor', {
            toolbar: ['bold italic underline insertorderedlist insertunorderedlist  image '],
            UEDITOR_HOME_URL: "__PUBLIC__/js/editor/",
            imageUrl: "<?php echo U('topic/upload');?>",
            imagePath: "__PUBLIC__/upload/attach/",

            autoClearinitialContent: true,
            wordCount: false,
            elementPathEnabled: false,
            autoFloatEnabled: false,

            textarea: 'content'
        });
        um.setContent('<?php echo ($topic["content"]); ?>');

        $('#talk-edit').on('submit', function(e) {
            e.preventDefault();
            var cid     =  $('#cid').val();
            var subject =  $.trim($('#subject').val());
            if (cid =='') {
                bootbox.alert('分类不能为空');
                return false;
            }
            if (subject == '') {
                bootbox.alert('标题不能为空');
                return false;
            }
            if (um.hasContents() == false) {
                bootbox.alert('内容不能为空');
                return false;
            }

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo U('topic/update');?>",
                data: $("#talk-edit").serialize(),
                success: function(data){
                    if (data.status == 0) {
                        bootbox.alert(data.info);
                        return  false;
                    } else {
                        window.location.href = data.url;
                    }

                }
            });
        })
    });
</script>
</body>
</html>