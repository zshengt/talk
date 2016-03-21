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

class TopicModel  extends Model{
	protected $tableName = 'topic';

	/**
	 * 最新话题列表
	 *
	 * @param [type]  $condition [description]
	 * @param [type]  $pagesize  [description]
	 * @return [type]            [description]
	 */
	public  function getRecentTopic( $condition, $pagesize =10) {
		$result['data']  = $this->field( '*' )->where( $condition )->order( 'create_time desc' )->selectPage( $pagesize );
		$result['data'] =  $this->getExtraTopic($result['data']);

		if ( !empty( $this->page ) ) {
			$result['page']  = $this->page;
		}
		return  $result;
	}

	public function  getStickTopic($order='create_time', $limit=5){
		$result = $this->where(array('is_stick'=>1,'status'=>1))->order($order)->limit($limit)->select();
		$result = $this->getExtraTopic($result);
		return $result;
	}
	public  function  getExtraTopic($data){
		if (!empty($data)) {
			foreach ( $data as $key => $v ) {
				$category  = D( 'TopicCategory' )->where( array( 'id'=>$v['cid'] ) )->getField( 'name, des' );
				$data[$key]['username'] = get_username( $v['uid'] );
				if ($category) {
					$data[$key]['cate_list'] = $category;
				}
			}
		}
		return  $data;
	}

	public  function insert( $cid, $uid, $subject, $content ) {

		$tid = $this->add( array(
				'cid'         => $cid,
				'uid'         => $uid,
				'subject'     => $subject,
				'create_time' => time(),
				'update_time' => time()
			) );
		if ( $tid ) {
			D( 'User' )->getCredit( $uid, 'add_topic' );
			D( 'TopicPost' )->insert( $tid, $uid, 1, $content );
			D( 'TopicCategory' )->incTopicNum( $cid );
			return  $tid;
		}
	}

	public  function update( $tid, $cid, $uid, $subject, $content ) {
		$result =   $this->where( array( 'tid'=>$tid, 'uid'=>$uid ) )->save( array( 'cid'=>$cid, 'subject'=>$subject, 'update_time'=>time() ) );
		if ( !$result ) {
			return  false;
		}
		D( 'TopicPost' )->update( $tid, $uid, $content );
		return  true;
	}

	public   function  del($tid, $topic){

		$this->where(array('tid'=>$tid))->delete();
		D('TopicPost')->where(array('tid'=>$tid))->delete();

		return true;
	}

	public  function reply( $tid, $uid, $content ) {

		$content = preg_replace_callback( "/@([\w\x{2e80}-\x{9fff}\-]+)/u", "parse_atname", $content );
		$data    = array (
			'tid'         => $tid,
			'content'     => $content,
			'uid'         => $uid,
			'create_time' => time()
		);
		$pid = D( 'TopicPost' )->add( $data );

		if ( strpos( $content, '@' ) ) {
			preg_match_all( "/@([\w\x{2e80}-\x{9fff}\-]+)/u", $content, $matches );

			if ( !empty( $matches ) ) {
				foreach ( $matches[1] as $key => $name ) {
					$at_uid = D( 'User' )->where( array( 'username'=>$name ) )->getField( 'uid' );
					if ( $at_uid ) {
						$at_uids[] =$at_uid;
					}
				}
				$at_uids = array_unique( $at_uids );
				foreach ( $at_uids as $k => $to_uid ) {
					D( 'Notify' )->addNotify( $uid, $to_uid, 3, $pid );
				}
			}
		}

		$topic = D( 'Topic' )->where( array( 'tid'=>$tid ) )->getField( 'uid, cid' );
		if ($uid != $topic['uid']) {
			D( 'Notify' )->addNotify( $uid, $topic['uid'], 1, $pid );
		}
		D( 'Topic' )->incPostNum( $tid );
		return  $pid;
	}

	public  function getRelationTopic( $tid, $cid ) {
		$map['tid'] = array( 'neq', $tid );
		$map['cid'] = array( 'eq', $cid );
		$relation_topics = $this->field( 'tid, subject' )->where( $map )->order( 'create_time desc' )->limit( 6 )->select();

		if ( $relation_topics ) {
			return  $relation_topics;
		} else {
			return  false;
		}
	}
	/**
	 *  话题详情页
	 *
	 * @param integer $tid 话题ID
	 * @return [type]       [description]
	 */
	public function getTopicList( $tid ) {
		$topic = $this->where( 'tid='.$tid )->find();
		if ( !empty( $topic ) ) {
			$topic['username'] =  D( 'User' )->where( 'uid='.$topic['uid'] )->getField( 'username' );
			$topic['catename'] =  D( 'TopicCategory' )->where( array( 'id'=>$topic['cid'] ) )->getField( 'name' );
			$topic['post_lists'] =D( 'TopicPost' )->getPostLists( array('tid'=>$tid), 10);

			return $topic;
		}

	}
	public function getAuthorTopic( $tid, $uid ) {
		$result = $this->where( array( 'tid'=>$tid, 'uid'=>$uid ) )->find();
		if ( !$result ) {
			return  false;
		}
		$content = D( 'TopicPost' )->where( array( 'tid'=>$tid, 'uid'=>$uid, 'first'=>1 ) )->getField( 'content' );
		$result['content'] = $content;
		return  $result;
	}


	/**
	 * 话题回复数
	 *
	 * @param [type]  $tid [description]
	 * @return [type]      [description]
	 */
	public function incPostNum( $tid ) {
		$result = $this->where( array( 'tid'=>$tid ) )->setInc( 'post_num' );
		return $result;
	}

	public function decPostNum( $tid ) {
		$result = $this->where( array( 'tid'=>$tid ) )->setDec( 'post_num' );
		return $result;
	}


	/**
	 * 话题浏览量
	 *
	 * @param [type]  $tid [description]
	 * @return [type]      [description]
	 */
	public function incViewNum( $tid ) {
		$result = $this->where( array( 'tid'=>$tid ) )->setInc( 'view_num' );
		return $result;
	}
}
?>
