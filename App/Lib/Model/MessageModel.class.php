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

class MessageModel extends  Model {

	protected $tableName = 'message';
	public function getMessageByUid($sender_uid, $receiver_uid){

		$result = $this->where('(sender_uid='.$sender_uid.' AND receiver_uid='.$receiver_uid .') OR  (sender_uid='.$receiver_uid.' AND receiver_uid='.$sender_uid.')')
						->find();

		if (!empty($result)) {
			return  $result;
		}else{
			return  false;
		}
	}
	/**
	 * 私信列表
	 * @param  [type] $uid      [description]
	 * @param  [type] $pagesize [description]
	 * @return [type]           [description]
	 */
	public function  getMessageLists($uid, $pagesize){
		$wheresql = array(
			'sender_uid' =>$uid ,
			'receiver_uid'=>$uid,
			'_logic' => 'OR'
		);
		$message_lists['data'] =$this->where($wheresql)->order('update_time desc')->selectPage($pagesize);
		if ($this->page) {
			$message_lists['page'] =$this->page;
		}
		if (!empty($message_lists)) {
			foreach ($message_lists['data'] as $key => $message) {
				if ($message['sender_uid'] == $uid) {
					$message_lists['data'][$key]['uid'] = $message['receiver_uid'];
				}else{
					$message_lists['data'][$key]['uid'] = $message['sender_uid'];
				}
				$uid  = $message_lists['data'][$key]['uid'];
				$message_lists['data'][$key]['username'] = D('User')->where(array('uid'=>$uid))->getField('username');
			}
			return $message_lists;
		}else{
			return  false;
		}
	}

	/**
	 * 私信详情页
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function  getMessageItem($id){
		$result = $this->where(array('id'=>$id))->find();
		if (!empty($result)) {
			return  $result;
		}else{
			return  false;
		}
	}
}

?>