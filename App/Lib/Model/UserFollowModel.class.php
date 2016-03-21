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

class UserFollowModel  extends  Model {
	
	protected $tableName = 'user_follow';
	/**
	 * 关注某人
	 * @param [type] $from_uid [description]
	 * @param [type] $to_uid   [description]
	 */
	public  function addFollow($from_uid, $to_uid){
		$is_follow = $this->checkFollow($from_uid, $to_uid);
		if (!$is_follow) {
			$data = array(
				'from_uid'    => $from_uid,
				'to_uid'      => $to_uid,
				'create_time' => time()
			);
			$result = $this->add($data); 
			return  $result;
		} else {
			return  false;
		}
		
	}


	public function  checkFollow($from_uid, $to_uid){
		$result = $this->where(array('from_uid'=>$from_uid, 'to_uid'=>$to_uid,'status'=>1))->count();
		if ($result) {
			return  true;
		}else {
			return  false;
		}
	}

	/**
	 * 取消关注
	 * @param  [type] $from_uid [description]
	 * @param  [type] $to_uid   [description]
	 * @return [type]           [description]
	 */
	public function  unFollow($from_uid, $to_uid){
		$result = $this->where(array('from_uid'=>$from_uid, 'to_uid'=>$to_uid, 'status'=>1))->delete();
		if ($result) {
			return  true;
		}else {
			return  false;
		}
	}

	/**
	 * 粉丝列表
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	public  function   getUserFan($uid){
		$result['data'] = $this->where(array('to_uid'=>$uid, 'status'=>1))->order('create_time')->selectPage(10);
		
		$result = $this->getRelUser($result['data'], 'fan');
		if ( !empty( $this->page ) ) {
			$result['page']  = $this->page;
		}
		return $result;

	}

	/**
	 * 关注者列表
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	public  function  getUserFollow($uid){
		$result['data'] = $this->where(array('from_uid'=>$uid, 'status'=>1))->order('create_time')->selectPage(10);
		$result = $this->getRelUser($result['data'], 'follow');
		if ( !empty( $this->page ) ) {
			$result['page']  = $this->page;
		}
		return $result;
	}


	public  function  getRelUser($data, $type){
		if (!empty($data)) {
			foreach ($data as $key => $v) {
				if ($type =='fan') {
					$uid =  $v['from_uid'];
				} elseif ($type =='follow') {
					$uid =  $v['to_uid'];
				}
				$user =  D('User')->field('username, intro')->where(array('uid'=>$uid))->find();
				$data[$key]['username'] = $user['username'];
				$data[$key]['intro']    = !empty($user['intro']) ? $user['intro']: '还没有个人介绍';
			}

			return $data;
			
		}
	}
}
?>