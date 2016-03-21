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

class AccountAction extends BaseAction {


	public  function _initialize(){
	    parent::_initialize();
	    $this->checkLogin();
	}

	/**
	 * 基本信息
	 * @return [type] [description]
	 */
	public function settings() {
		if ( IS_POST ) {
			$gender   = I('post.gender', 1, 'intval');
			$area     = I('post.area', '', 'safe');
			$intro    = I('post.intro', '', 'safe');
			empty($intro) && $this->error('个人介绍不能为空');

			$data = array(
				'gender'   => $gender,
				'area'     => $area,
				'intro'    => $intro,
				'update_time'=>time()
			);
			$result = D( 'user' )->where(array('uid'=>$this->mid))->save( $data);
			if ( $result ) {
				$this->success('修改成功');
			} else {
				$this->error('修改失败');
			}
		}else{
			$this->assign('title', '账号设置-基本资料');
			$this->display();
		}
	}
	/**
	 *  修改密码
	 * @return [type] [description]
	 */
	public function password() {
		if ( IS_POST ) {
			$password      = I('post.password', '', 'safe');
			$newPassword   = I('post.newPassword', '', 'safe');
			$reNewPassword = I('post.reNewPassword', '', 'safe');
			empty($password) && $this->error('当前密码不能为空');
			if ($newPassword !==$reNewPassword ) {
				$this->error('确认密码不正确');
			}
			$user  = D('User')->where(array('uid'=>$this->mid))->getField('password, salt');

			$curPassword =  sha1( md5( $password ) . $user['salt'] );
			if ($user['password'] !== $curPassword) {
				$this->error('当前密码不正确');
			}
			$result = D('User')->setPassword($this->mid, $newPassword);
			if ($result) {
				$this->success('密码修改成功');
			} else {
				$this->error('密码修改失败');
			}
		}else{
			$this->display();
		}

	}

	public function avatar() {
		$this->display();
	}


	public  function upload() {

		import("ORG.Util.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize = 3292200;
		$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
		$upload->savePath = 'Public/upload/avatar/';
		$upload->thumbRemoveOrigin =true;

		if ( !$upload->upload() ) {
			$this->error($upload->getErrorMsg());
		}else {
			$info =  $upload->getUploadFileInfo();
			$data['avatar'] = $info[0]['savepath'] . $info[0]['savename'];

			$big_face    = convert_uid( $this->mid, 'big');
			$middle_face = convert_uid( $this->mid, 'middle');
			$small_face  = convert_uid( $this->mid, 'small');
			vendor( 'phpthumb.Thumb' );
			$thumb = PhpThumbFactory::create( $data['avatar']);

			$thumb->resize( 128, 128 );
			$thumb->save( $big_face );
			$thumb->resize( 80, 80 );
			$thumb->save( $middle_face );
			$thumb->resize( 50, 50 );
			$thumb->save( $small_face );
			$return_avatar['avatar'] =  uavatar($this->mid, 'big');
			$this->ajaxReturn( $return_avatar);
		}
	}

}
?>
