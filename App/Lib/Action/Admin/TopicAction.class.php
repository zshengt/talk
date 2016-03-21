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

class TopicAction extends BaseAction {

	public  function  index(){
		$topics = D( 'Topic' )->getRecentTopic( array( 'status'=>1 ), 10, true );
		$this->assign('topics',$topics);
		$this->display();
	}

	public  function  category(){
		$category = D( 'TopicCategory' )->getAllCates();
		$this->assign( 'category', $category );
		$this->display();
	}

	public  function posts(){
		$tid   = I('get.tid', 0, 'intval');
		$posts = D( 'TopicPost' )->getPostLists(array('tid'=>$tid, 'first'=>0), 10);
		$this->assign('posts',$posts);
		$this->assign('posts', $posts);
		$this->display();
	}

	public  function  stick(){
		$tid   = I('get.tid', 0, 'intval');
		$result = D('Topic')->where(array('tid'=>$tid))->setField('is_stick', 1);
		if ($result) {
			$this->success('推荐成功');
		} else {
			$this->error('推荐失败');
		}
	}

	public  function  unstick(){
		$tid   = I('get.tid', 0, 'intval');
		$result = D('Topic')->where(array('tid'=>$tid))->setField('is_stick', 0);
		if ($result) {
			$this->success('取消推荐成功');
		} else {
			$this->error('取消推荐失败');
		}
	}

	public  function  del(){
		$tid   = I('get.tid', 0, 'intval');
		$topic  =  D('Topic')->where(array('tid'=>$tid))->getField('cid, post_num');
		if (!$topic) {
			$this->error('你想访问的页面不存在');
		} else {
			D('Topic')->del($tid, $topic);
			$this->success('删除成功');
		}
		
	}


	public  function  delPost(){

		$pid = I('get.pid', 0, 'intval');
		$post  = D('TopicPost')->where(array('pid'=>$pid))->getField('pid, tid');
		if (!$post) {
			$this->error('回复不存在');
		} 

		D('TopicPost')->del($post);
		$this->success('删除成功');
	}

	public  function  addCate(){
		$category = D( 'TopicCategory' )->getAllCates();
		if (count($category) >=10) {
			$this->error('最多创建10个分类');
		}
		$this->display();
	}

	public function doAddCate(){
		$name      = I('post.name', '', 'safe');
		$view_sort = I('post.view_sort', 0, 'intval');
		$des       = I('post.des', 0, 'safe');
		empty($name) && $this->error('名字不能为空');
		empty($des)  &&  $this->error('描述不能为空');
		$data = array(
			'name'        => $name,   
			'view_sort'   => $view_sort,
			'des'         => $des,
			'create_time' => time()
		);
		$result = D('TopicCategory')->add($data);
		if ($result) {
			$this->success('添加成功', U('admin/topic/category'));
		}
	}

	public  function editCate(){
		$id = I('get.id', 0, 'intval');
		$cate = D('TopicCategory')->where(array('id'=>$id))->find();
		$this->assign('cate',$cate);
		$this->display();
	}

	public  function  doEditCate(){
		$id        = I('post.id', 0, 'intval');
		$name      = I('post.name', '', 'safe');
		$view_sort = I('post.view_sort', 0, 'intval');
		$des       = I('post.des', 0, 'safe');
		empty($name) && $this->error('名字不能为空');
		empty($des) &&  $this->error('描述不能为空');
		$data = array(
			'name'        => $name,   
			'view_sort'   => $view_sort,
			'des'         => $des
		);
		$result = D('TopicCategory')->where(array('id'=>$id))->save($data);
		if ($result) {
			$this->success('更新成功', U('admin/topic/category'));
		}
	}

	public  function  delCate(){
		$id = I('get.id', 0, 'intval');

		$result = D('TopicCategory')->where(array('id'=>$id))->delete();

		if ($result) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}
}
?>