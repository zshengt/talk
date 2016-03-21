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

class UserAction extends BaseAction {

	/**
	 * 用户主页
	 *
	 * @return [type] [description]
	 */
	public function index() {

		$this->getUserData( $this->mid, $this->uid );
		$topic_lists = D( 'Topic' )->getRecentTopic( array( 'uid'=>$this->uid, 'status'=>1 ), 15 );
		$this->assign( 'topic_lists', $topic_lists );
		$this->display();
	}
	/**
	 * 注册
	 */
	public function signup() {

		if ( IS_POST ) {
			$email     = I('post.email','','trim');
			$password  = I('post.password', '', 'safe');
			$username  = I('post.username', '', 'htmlspecialchars');
			$verify    = I('post.verify', '', 'htmlspecialchars');
			empty($email) && $this->error('邮箱不能为空');
			empty($password) && $this->error('密码不能为空');
			empty($username) && $this->error('用户名不能为空');
			empty($verify) && $this->error('验证码不能为空');

			$uid = D('User')->insert($email, $password, $username);
			if ( $uid ) {
				if(C('email_open') ==1){
					$result = D( 'UserToken' )->sendUserToken( $email, $uid, $username, 1);
					if ( $result ) {
						$this->redirect('user/active', array('uid'=>$uid));
					} else {
						$this->error( '注册失败' );
					}
				} else {
					D( 'User' )->where( array( 'uid'=>$uid ) )->setField( 'is_active', 1 );
					$user= D('User')->where(array('uid'=>$uid))->find();
					D('User')->autoLogin($user);
					$this->redirect('topic/index');
				}
			} else {
				$this->error('注册失败');
			}

		} else {
			$this->assign( 'title', '注册' );
			$this->display();
		}
	}

	/**
	 *  登录
	 */
	public  function login() {

		if ( IS_POST ) {
			/*$email     = I('post.email','','trim');
			$password  = I('post.password', '', 'safe');
			$is_remember = I('post.is_remember', 0, 'intval');
			empty($email) && $this->error('邮箱不能为空');
			empty($password) && $this->error('密码不能为空');*/
			$mobile = I('post.mobile', '', 'trim');
			$password = I('post.password', '', 'safe');
			$is_remember = I('post.is_remember', 0, 'intval');
			empty($mobile) && $this->error('手机不能为空');
			empty($password) && $this->error('密码不能为空');
			$data = $this->curl_kjf(array("mobile"=>$mobile, "password"=>$password));
			$User =  D( 'User' );
			$data = json_decode($data, true);
			if(!$data['status']){
				$this->error('用户名或密码错误');
			}
			$user =  $User->login( $data['user']['uid'] );
			if ( $user ) {
				if ($is_remember ==1) {
					cookie( 'talkpiece_uid', authcode($user['uid'], 'ENCODE'), time() + 3600*24*7 );
				}
				$this->success('登录成功', U('topic/index'));
			} else {
				$this->error( $User->getError() );
			}
		} else {
			if ($this->mid >0) {
				$this->redirect('topic/index');
			}
			$this->assign( 'title', '登录' );
			$this->display();
		}

	}

	public function resetPassword() {

		if ( IS_POST ) {
			$token      = I('post.token', '', 'safe');
			$password   = I('post.password', '', 'safe');
			$rePassword = I('post.rePassword', '', 'safe');
			empty($password) && $this->error('密码不能为空');
			if ($password !== $rePassword) {
				$this->error('确认密码不正确');
			}
			$condition = array( 'token'=>$token, 'type'=>2 );
			$result = D( 'UserToken' )->getUserToken($condition);
			if ($result) {
				$status = D('User')->setPassword($result['uid'], $password);
				if ($status) {
					D( 'UserToken' )->where( $condition )->setField( 'status', 1 );
					$this->success('密码修改成功');
				} else {
					$this->error('密码修改失败');
				}
			} else {
				$this->error(D('UserToken')->getError(), U('topic/index'));
			}

		} else {
			$token   = I('get.token', '', 'safe');
			$result = D( 'UserToken' )->getUserToken(array( 'token'=>$token, 'type'=>2 ));
			if ($result) {
				$this->assign( 'token', $token );
				$this->display();
			} else {
				$this->error(D('UserToken')->getError(), U('topic/index'));
			}
		}
	}


	public function active() {
		$token     = I('get.token', '', '');
		if ( !empty( $token ) ) {
			$wheresql = array(
				'token'=>$token,
				'type'=>1
			);
			$result= D( 'UserToken' )->where( $wheresql )->find();
			if ( !empty( $result ) ) {
				if ( $result['status'] ==1 ) {
					$this->error( '该链接已经失效', U( 'user/signup' ) );
				} else {
					D( 'UserToken' )->where( $wheresql )->setField( 'status', 1 );
					D( 'User' )->where( array( 'uid'=>$result['uid'] ) )->setField( 'is_active', 1 );
					$user= D('User')->where(array('uid'=>$result['uid']))->find();
					D('User')->autoLogin($user);
					$this->success( '注册激活成功', U( 'topic/index' ) );
				}
			} else {
				$this->error( '你想访问的页面不存在', U( 'user/signup' ) );
			}
		} else {
			$email = D('User')->where(array('uid'=>$this->uid))->getField('email');
			if (!$email) {
				$this->error( '你想访问的页面不存在', U( 'user/signup' ) );
			}
			$email =  explode( '@', $email );
			if ($email[1] =='gmail.com') {
				$this->assign( 'login_email',  'http://mail.google.com' );
			} else {
				$this->assign( 'login_email',  'http://mail.' . $email[1] );
			}
			$this->display();
		}
	}

	/**
	 * 忘记密码
	 *
	 * @return [type] [description]
	 */
	public function password() {
		if ( IS_POST ) {
			$email  = I('post.email', '', 'safe');
			$user   = D( 'User')->where( array( 'email'=>$email ) )->find();
			if (!empty($user) ) {
				$result =D( 'UserToken' )->sendUserToken( $user['email'], $user['uid'], $user['username'], 2 );
				if ( $result ) {
					$this->success( '邮件发送成功' );
				} else {
					$this->error( '邮件发送失败' );
				}
			} else {
				$this->error( '用户不存在' );
			}
		} else {
			$this->display();
		}

	}

	public function checkEmail() {
		$email  = I('post.email', '', 'safe');
		if ( $email ) {
			$user = D( 'User' )->where( array( 'email'=>$email ) )->find();
			if ( $user ) {
				echo  true;
			}else {
				echo  false;
			}
		}
	}

	/**
	 * 退出
	 *
	 * @return [type] [description]
	 */
	public function logout() {
		if(is_login()){
			session( 'user_auth', null );
			session( 'user_auth_sign', null );
			session('[destroy]');
			cookie('talkpiece_uid', null);
            $this->redirect('topic/index');
        } else {
            $this->redirect('login');
        }
	}


	public function verify() {
		import( "ORG.Util.Captcha" );
		$catpcha = new Captcha();
		$catpcha->entry();
	}

	public function checkVerify() {
		$captcha = I('post.captcha', '', 'safe');
		if ( strtolower($captcha) == $_SESSION['code'] ) {
			echo  true;
		}else {
			echo  false;
		}
	}

	public  function  checkUsername(){
		$username = I('post.username', '', 'safe');
		$user = D('User')->where(array('username'=>$username))->find();
		if ($user) {
			echo true;
		} else{
			echo false;
		}
	}

	/**
	 * 关注某人
	 *
	 * @return [type] [description]
	 */
	public function follow() {
		$this->checkLogin();
		$touid  = I('post.touid', 0, 'intval');
		D( 'Notify' )->addNotify( $this->mid, $touid, 2, 0 );
		$result = D( 'UserFollow' )->addFollow( $this->mid, $touid );
		if ($result) {
			$this->ajaxReturn(array('id'=>$result));
		} else {
			$this->error('操作失败');
		}
	}

	/**
	 * 取消关注
	 *
	 * @return [type] [description]
	 */
	public function unfollow() {
		$this->checkLogin();

		$touid  = I('post.touid', 0, 'intval');
		$result = D( 'UserFollow' )->unFollow( $this->mid, $touid );
		if ($result) {
			$this->ajaxReturn(array('id'=>$touid));
		} else {
			$this->error('操作失败');
		}
	}


	/**
	 *  关注者列表
	 *
	 * @return [type] [description]
	 */
	public function followers() {
		$this->getUserData( $this->mid, $this->uid );
		$this->display();
	}

	/**
	 * 粉丝列表
	 *
	 * @return [type] [description]
	 */
	public function fans() {
		$this->getUserData($this->mid, $this->uid);
		$this->display();
	}

	public function getUserData($mid, $uid ) {

		$user_list = D( 'User' )->getUserList( 'uid='.$uid );
		$this->assign( 'user_list', $user_list );

		if ( $mid !== $uid ) {
			$is_follow = D( 'UserFollow' )->checkFollow( $this->mid, $uid );
		}
		$this->assign( 'is_follow', $is_follow );

		$fans      = D( 'UserFollow' )->getUserFan( $uid );
		$followers = D( 'UserFollow' )->getUserFollow( $uid );

		$this->assign( 'fans_num', count( $fans ) );
		$this->assign( 'follow_num', count( $followers ) );
		$this->assign( 'fans', $fans );
		$this->assign( 'followers', $followers );
		$this->assign('title', $user_list['username'] .'个人主页');
	}

	function curl_kjf( $data=array() ){
		$uri = "http://web.kjf.com/user/login";
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $uri );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		return $result;
	}
}
?>
