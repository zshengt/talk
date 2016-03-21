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

class NotifyModel extends Model {
	protected $tableName = 'notify';
	/**
	 * 通知列表
	 * @param  [type] $uid      [description]
	 * @param  [type] $pagesize [description]
	 * @return [type]           [description]
	 */
	public function getNotifyList( $uid, $pagesize ) {

		$result['data'] = $this->where('to_uid='.$uid)->order('id desc')->selectPage($pagesize);
		foreach ($result['data'] as $key => $value) {
			$result['data'][$key]['from_uname'] = D('User')->where('uid='.$value['from_uid'])->getField('username');
			if ($value['type'] ==1 || $value['type'] ==3) {
				$post_list =  D('TopicPost')->getTopicByPid($value['appid']);
				$result['data'][$key]['subject']    = $post_list['subject'];
				$result['data'][$key]['tid']        = $post_list['tid'];
				$result['data'][$key]['content']    = $post_list['content'];
			}
		}
		$result['page'] =$this->page;
		return $result;
	}

	/**
	 * 发送通知
	 * @param [type] $from_uid [description]
	 * @param [type] $to_uid   [description]
	 * @param [type] $type     [description]
	 * @param [type] $pid      [description]
	 */
	public function  addNotify($from_uid, $to_uid, $type, $pid){
		$data = array(
			'from_uid'    => $from_uid,
			'to_uid'      => $to_uid,
			'type'		  => $type,
			'appid'		  => $pid,
			'create_time' => time()
		);
		$result = D('Notify')->add($data);
		if (!$result) {
			return  false;
		}
		D('User')->incAtNum($to_uid);
		return  true;
	}

}
?>
