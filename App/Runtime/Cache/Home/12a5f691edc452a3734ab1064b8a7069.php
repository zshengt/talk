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
                        <li class="disabled active"><a>1. 环境检测</a>
                        </li>
                        <li class="disabled "><a>2. 创建数据库</a>
                        </li>
                        <li class="disabled "><a>4. 完成安装</a>
                        </li>
                    </ul>
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="40%">环境检测</th>
                                <th width="20%">所需配置</th>
                                <th width="20%">当前配置</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($env)): $i = 0; $__LIST__ = $env;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                                     <td><?php echo ($item[0]); ?></td>
                                     <td><?php echo ($item[1]); ?></td>
                                     <td>
                                        <?php echo ($item[3]); ?>
                                        <?php if($item[4] == 'success'){ ?>
                                            <span style="color:green">（OK）</span>
                                        <?php }else{ ?>
                                            <span style="color:red">（Failed）</span>
                                        <?php } ?>
                                     </td>       
                                 </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="40%">目录权限检查</th>
                                <th width="20%">所需配置</th>
                                <th width="20%">当前配置</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($dir)): $i = 0; $__LIST__ = $dir;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                                     <td><?php echo ($item[3]); ?></td>
                                     <td>可写</td>
                                     <td>
                                        <?php echo ($item[1]); ?>
                                        <?php if($item[2] == 'success'){ ?>
                                            <span style="color:green">（OK）</span>
                                        <?php }else{ ?>
                                            <span style="color:red">（Failed）</span>
                                        <?php } ?>
                                     </td>       
                                 </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <a class="btn btn-primary pull-right" href="<?php echo U('install/step2');?>">下一步</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>