<?php 
/**
 * TalkPiece  开源垂直社区
 *
 * @author     thinkphper <service@talkpiece.com>
 * @copyright  2014  talkpiece
 * @license    http://www.talkpiece.com/license
 * @version    1.0
 * @link       http://www.talkpiece.com
 */

class BaseAction extends Action {
	/**
	    * 后台控制器初始化
	    */
	   protected function _initialize(){

	   		if (false ===$settings = F('settings')) {
	   			$settings =  D('Settings')->getSettings();
	   		}
	   		C($settings);
	   		
	       $this->mid =  is_login();
	       if (!session('is_admin')) {
	    		$this->redirect('Public/login');
	       }
	   }
	
}
?>