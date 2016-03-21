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

class BaseAction extends Action{

	public function _initialize() {

		// $this->checkInstall();
		if (false === $settings = F('settings')) {
			$settings =  D('Settings')->getSettings();
		}
		C($settings);

		$cookie_uid = authcode(I('cookie.talkpiece_uid'),'DECODE');
		if($cookie_uid >0 ){
			$user = D('User')->where(array('uid'=>$cookie_uid))->find();
			D('User')->autoLogin($user);
		}
		$this->mid = is_login();
		$this->uid = isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : $this->mid; 
		if ($this->mid >0 ) {
			$user  = D('User')->where(array('uid'=>$this->mid))->find();	
			$this->assign('user', $user) ;
		}
		
		$this->assign('mid', $this->mid);
		$this->assign('uid', $this->uid);
		$this->assign('settings', $settings);
	}
	public function _empty(){
		$this->redirect('Topic/index');
	}
	protected function checkLogin(){
		is_login() || $this->error('你还没有登录，请先登录！', U('User/login'));

		$is_active  = D('User')->where(array('uid'=>$this->mid))->getField('is_active');
		if ($is_active == -1) {
			$this->error('用户已被禁用',U('User/logout'));
		}
	}
}
?>
