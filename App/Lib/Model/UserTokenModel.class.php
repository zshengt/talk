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

class UserTokenModel extends Model {

	public function  sendUserToken($email, $uid, $username, $type){
		$token = sha1( uniqid( mt_rand(), true ) );
		if ($type ==1) {
			$url  = TALK_HOST . U( 'user/active', array( 'token'=>$token ));
			$subject = '欢迎加入'.C('web_name');
			$body    = '<strong>欢迎加入'.C('web_name').'</strong><a href="' .$url. '">确认链接</a>';
		} elseif ($type ==2) {

			$url = TALK_HOST . U( 'user/resetPassword', array( 'token'=>$token ));
			$subject = C('web_name') . '找回密码';
			$body    = '<strong>Hi, ' . $username . '</strong>点击这里重新设置你的密码：<a href="' .$url. '">重置密码</a>';
		}

		$wheresql   = array('uid'=>$uid, 'type'=>$type);
		$token_count = $this->where($wheresql)->count();
		if ($token_count >0 ) {
			$this->where($wheresql)->save(array('token'=>$token, 'status'=>0));
		} else {
			$this->add( array(
			        'uid'  => $uid,
			        'token'=> $token,
			        'type' => $type,
			        'create_time' =>time()
			) );
		}
		$result = send_email($email, $username, $subject, $body);
		return  $result;
	}

	public  function  getUserToken($condition){
		$result = $this->where($condition)->find();
		if (!$result) {
			$this->error = '你想访问的连接不存在';
			return false;
		} elseif ($result['status'] ==1){
			$this->error = '该链接已经失效';
			return false;
		}
	    return  $result;
	}

}
?>