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
class SettingsAction extends BaseAction{

	public  function  index(){
		$settings = D('Settings')->getSettings('site');
		$this->assign('settings', $settings);
		$this->display();
	}


	public  function email(){
		$email = D('Settings')->getSettings('email');		
		$this->assign('email', $email);
		$this->display();
	}

	public  function  edit(){	

		$name     = I('post.name', '', 'safe');
		$keywords = I('post.keywords', '', 'safe');
		$des      = I('post.des', '', 'safe');
		$copyright = I('post.copyright', '', 'safe');
		$statis    = I('post.statis', '', '');

		empty($name) && $this->error('名称不能为空');

		$data =  array(
			'web_name' => $name,
			'web_keywords'=> $keywords,
			'web_des'  => $des,
			'web_copyright' =>$copyright,
			'web_statis' =>$statis
		);

		$result = D('Settings')->where(array('type'=>'site'))->setField('value', serialize($data));
		if ($result) {
			F('settings', NULL);
			$this->success('更新成功');
		} else {
			$this->error('更新失败');
		}
	}

	public  function  editEmail(){	

		$email_open= I('post.email_open', '', 'intval');
		$smtp_host = I('post.smtp_host', '', 'safe');
		$smtp_port = I('post.smtp_port', '', '');
		$smtp_user = I('post.smtp_user', '', 'safe');
		$smtp_pwd  = I('post.smtp_pwd', '', 'safe');
		$from_name = I('post.from_name', '', 'safe');
		$from_email= I('post.from_email', '', 'safe');

		$data =  array(
			'email_open'=> $email_open,
			'smtp_host' => $smtp_host,
			'smtp_port' => $smtp_port,
			'smtp_user' => $smtp_user,
			'smtp_pwd'  =>$smtp_pwd,
			'from_name' => $from_name,
			'from_email'=>$from_email
		);

		$result = D('Settings')->where(array('type'=>'email'))->setField('value', serialize($data));
		if ($result) {
			F('settings', NULL);
			$this->success('更新成功');
		} else {
			$this->error('更新失败');
		}
	}
}
?>