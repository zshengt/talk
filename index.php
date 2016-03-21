<?php
/**
 * TalkPiece  开源社区
 *
 * @author     thinkphper <service@talkpiece.com>
 * @copyright  2014  talkpiece
 * @license    http://www.talkpiece.com/license
 * @version    1.0
 * @link       http://www.talkpiece.com
 */

/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define('APP_DEBUG', true);
define('APP_NAME', 'App');
define('APP_PATH', './App/');
if (!file_exists(APP_PATH .'Runtime/Data/install/install.lock')) {
	$_GET['m'] = 'install';
}
echo phpinfo();
require './ThinkPHP/bootstrap.php';