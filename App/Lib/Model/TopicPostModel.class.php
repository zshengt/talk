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

class TopicPostModel extends Model{

	protected $tableName = 'topic_post';


	public  function  insert($tid, $uid, $first, $content){
		$data = array(
			'tid'         => $tid,
			'first'       => $first,
			'content'     => $content,
			'uid'         => $uid,
			'create_time' => time()
		);
		$result = $this->add($data);
		if (!$result) {
			return  false;
		} else {
			D( 'User' )->getCredit( $uid, 'add_post' );
			return  true;
		}
	}
	public  function  update($tid, $uid, $content){

		$result =   $this->where(array('tid'=>$tid, 'uid'=>$uid, 'first'=>1))
						 ->save(array('content'=>$content, 'update_time'=>time()));
		if (!$result) {
			return  false;
		} else {
			return  true;
		}
	}

	public  function  del($post){
		$cid  = D('Topic')->where(array('tid'=>$post['tid']))->getField('cid');
		D( 'TopicPost' )->where(array('pid'=>$post['pid']))->delete();
		D('Topic')->decPostNum($post['tid']);
		D('TopicCategory')->decPostNum($cid);
	}

	/**
	 * 话题回复列表
	 * @param  [type] $tid [description]
	 * @return [type]      [description]
	 */
	public function  getPostLists($condition, $pagesize =10){
		$result['data'] = $this->where($condition)->order('create_time')->selectPage($pagesize);
		if ( !empty( $this->page ) ) {
			$result['page']  = $this->page;
		}
		if (!empty($result) && is_array($result)) {
			foreach ($result['data'] as $key => $post) {
				$result['data'][$key]['username'] = D('User')->where(array('uid'=>$post['uid']))->getField('username');
			}
		}
		return $result;
	}

	public  function  getPostsNum($tid){
		$postsNum =  $this->where(array('tid'=>$tid,'first'=>0))->count();
		return $postsNum;
	}

	public function  getPostItem($pid){
		$post_list =$this->find('*', 'pid=' .$pid);
		if (!empty($post_list) && is_array($post_list)) {
			$post_list['username'] = D('User')->getUserName($post_list['uid']);
			return $post_list;
		}
	}
	public function  getTopicByPid($pid){
		$post_list = $this->where('pid=' .$pid)->find();
		if (!empty($post_list) && is_array($post_list)) {
			$post_list['subject'] = D('Topic')->where('tid='.$post_list['tid'])->getField('subject');
			return $post_list;
		}
	}

	public function  delPostItem($where){
		$result  =$this->delete($where);
		if ($result) {
			return $result;
		} else{
			return  false;
		}

	}
}
?>
