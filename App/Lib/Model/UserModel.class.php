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

class UserModel  extends Model{

	protected $tableName = 'user';

	//注册
	public function insert($kjf_uid){

/*		$salt     = rand_string( 10 );
		$password = sha1( md5( $password ) .  $salt );*/
		$username = "[用户$kjf_uid]";
		$data  = array(
			'username'  => $username,
/*			'password'  => $password,
			'email'     => $email,
			'salt'      => $salt,*/
			'kjf_uid'   => $kjf_uid,
			'create_time'=>time(),
			'update_time'=>time()
		);
		$result = $this->add($data);
		if ($result) {
			$this->getCredit($result, 'signup');
			return $result;
		}
	}
	public function isEmailExists($email) {
		$user = $this->where( array( 'email'=>$email ) )->find();
		if ( $user ) {
			return  false;
		}else {
			return  true;
		}
	}

	public  function  getAllUsers(){
		$result['data']  = $this->field( '*' )->order( 'create_time desc' )->selectPage(10);
		if ( !empty( $this->page ) ) {
			$result['page']  = $this->page;
		}
		return $result;
	}
	public function getUserField( $field, $wheresql) {
		$data = $this->where($wheresql)->getField($field);
		if (!empty($data)) {
			return  $data[$field];
		}
	}

	public  function  getUserList($condition, $field='*'){
		$data = $this->field($field)->where($condition)->find();
		if (!empty($data)) {
			return  $data;
		}
	}

	public function getUserName( $uid ) {
		$user_list = $this->getUserField( array( 'uid'=>$uid ), 'username' );
		return $user_list['username'];
	}

    public function getUserUid( $username ) {
    	$user_list = $this->getUserField(array( 'username'=>$username ), 'uid' );
    	return $user_list['uid'];
    }
	public function incAtNum($uid){
		$result  = $this->where('uid='.$uid)->setInc('at_num');
		return $result;
	}

	public  function setPassword($uid, $password){
		$salt     =  rand_string( 10 );
		$password =  sha1( md5( $password ) . $salt );
		$result = $this->where(array('uid'=>$uid))->save(array('password'=>$password, 'salt'=>$salt));
		if ($result) {
			return  true;
		} else {
			return  false;
		}
	}
	public  function checkPassword($user, $password){
		$curPassword =  sha1( md5( $password ) . $user['salt'] );
		if ($user['password'] !== $curPassword) {
			$this->error ='当前密码不正确';
			return  false;
		}
		return  true;
	}

	public  function checkNameExists($username){
		$result = $this->where(array('username'=>$username))->find();
		return  $result;
	}

	public  function login($kjf_uid, $admin=false) {
		$user = $this->where( array( 'kjf_uid'=>$kjf_uid) )->getField( 'uid, username, password, salt, is_active, last_login_time, is_admin' );
		//$password  = sha1( md5( $password ) . $user['salt'] );
		if (!$user) {
			//此处去执行注册逻辑
			$result = $this->insert($kjf_uid);
			$user = $this->where( array( 'kjf_uid'=>$kjf_uid) )->getField( 'uid, username, password, salt, is_active, last_login_time, is_admin' );
		} elseif ($user['is_active'] == -1){
			$this->error = '用户已被禁用！';
			return  false ;
		}
		
		if ($admin) {
			if ($user['is_admin'] == 0) {
				$this->error = '用户没有管理权限！';
				return  false ;
			}
		}
		$this->autoLogin($user, $admin ? true : false);
		return  $user;
	}

	public  function adminLogin($email, $password, $admin=false) {
		$user = $this->where( array( 'email'=>$email ) )->getField( 'uid, username, password, salt, is_active, last_login_time, is_admin' );
		$password  = sha1( md5( $password ) . $user['salt'] );
		if (!$user) {
			$this->error = '用户不存在或被禁用！';
			return false;
		} elseif($user['password'] !== $password ){
			$this->error = '用户名或者密码错误';
			return false;
		} elseif ($user['is_active'] == 0 ) {
			$this->error = '用户未激活！';
			return  false ;
		} elseif ($user['is_active'] == -1){
			$this->error = '用户已被禁用！';
			return  false ;
		}
		
		if ($admin) {
			if ($user['is_admin'] == 0) {
				$this->error = '用户没有管理权限！';
				return  false ;
			}
		}
		$this->autoLogin($user, $admin ? true : false);
		return  $user;
	}

	public  function  autoLogin($user, $admin=false) {

		$this->getCredit($user['uid'], 'login');
		$data = array(
			'last_login_time' => time()
		);
		$this->where(array('uid'=>$user['uid']))->save($data);

		$auth = array(
			'uid'             => $user['uid'],
			'username'        => $user['username'],
			'last_login_time' => $user['last_login_time'],
		);
		session('user_auth', $auth);
		session('user_auth_sign', data_auth_sign($auth));
		if ($admin) {
			session('is_admin', true);
		}
	}

	public function  getCredit($uid, $type){
		$condition = array('uid'=>$uid);
		if ($type) {
			switch ($type) {
				case 'signup':
					$result = $this->where($condition)->setInc('credit', 10);
					break;
				case 'login':
					$result = $this->where($condition)->setInc('credit', 10);
					break;
				case 'upload_avatar':
					$result = $this->where($condition)->setInc('credit', 10);
					break;
				case 'add_topic':
					$result = $this->where($condition)->setInc('credit', 10);
					break;
				case 'delete_topic':
					$result = $this->where($condition)->setDec('credit', 10);
					break;
				case 'add_post':
					$result = $this->where($condition)->setInc('credit', 10);
				    break;
				case 'delete_post':
					$result = $this->where($condition)->setDec('credit', 10);
					break;
			}
		}
		if ($result) {
			return  true;
		} else {
			return  false;
		}


	}
}
?>
