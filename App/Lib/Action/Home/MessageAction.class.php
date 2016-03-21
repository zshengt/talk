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

class MessageAction extends BaseAction{

    public  function _initialize(){
        parent::_initialize();
        $this->checkLogin();
    }
    /**
     *  @你的列表
     * @return [type] [description]
     */
    public function notify() {
        D('User')->where(array('uid'=>$this->mid))->setField('at_num', 0);
        $notify_list = D( 'Notify' )->getNotifyList( $this->mid, 10 );
        $this->assign( 'notify_list', $notify_list );
        $this->display();
    }


    public   function index() {

        D('User')->where('uid='.$this->mid )->setField('inbox_num', 0);
        $message_lists = D( 'Message' )->getMessageLists( $this->mid, 10 );
        $this->assign( 'message_lists', $message_lists );
        $this->display();
    }

    public  function  unread(){
        $user  = D('User')->where(array('uid'=>$this->mid))->getField('at_num, inbox_num');
        $this->ajaxReturn($user);   
    }

    public function send() {
        $touid  = I('post.touid', 0, 'intval');
        $content = I('post.content', '', 'safe');
        if (!$touid) {
            $this->error('参数错误');
        }
        $message_list = D( 'Message' )->getMessageByUid( $this->mid, $touid );
        if ( !$message_list ) {
            $data = array(
                'sender_uid' => $this->mid,
                'receiver_uid'   => $touid,
                'last_uid'      => $this->mid,
                'last_content'  => $content,
                'create_time'=>time(),
                'update_time'=>time()
            );
            $ms_id = D( 'Message' )->add( $data);
        }else {
            $ms_id = $message_list['id'];
            $update_data = array(
                'update_time' =>time(),
                'last_content' => $content,
                'last_uid'  =>$this->mid
            );
            D( 'Message' )->where(array('id'=>$ms_id))->save( $update_data );
        }
        D('User')->where(array('uid'=>$touid))->setInc('inbox_num');

        $ms_data = array(
            'ms_id'=>$ms_id,
            'uid'  =>$this->mid,
            'content'=>$content,
            'create_time'=>time()
        );
        D( 'MessageChat' )->add( $ms_data );

        $this->success('发送成功');
    }

    /**
     * 私信详情页
     * @return [type] [description]
     */
    public function detail() {

        $id  = I('get.id', 0, 'intval');
        $message = D( 'Message' )->getMessageItem( $id );
        if (!$message) {
            $this->error('你访问的页面不存在', U('Message/index'));
        }

        if ( $message['sender_uid'] == $this->mid ) {
            $username = D('User')->where(array('uid'=>$message['receiver_uid']))->getField('username');
        }else {
            $username = D('User')->where(array('uid'=>$message['sender_uid']))->getField('username');
        }
        $chat_lists = D( 'MessageChat' )->getMessageChat( $id, $username );
        $this->assign( 'chat_lists', $chat_lists );
        $this->display();
    }

    /**
     * 私信回复某人
     * @return [type] [description]
     */
    public function   reply(){
        $id      = I('post.id', 0, 'intval');
        $content = I('post.content', '', 'safe');
        
        $message = D( 'Message' )->getMessageItem( $id );
        if (!$message) {
            $this->error('你访问的页面不存在', U('Message/index'));
        }

        if ($message['sender_uid'] == $this->mid) {
            D('User')->where(array('uid'=>$message['receiver_uid']))->setInc('inbox_num');
        } else {
            D('User')->where(array('uid'=>$message['sender_uid']))->setInc('inbox_num');
        }
        $ms_data = array(
            'ms_id'=>$id,
            'uid'  =>$this->mid,
            'content'=>$content,
            'create_time'=>time()
        );
        D( 'MessageChat' )->add( $ms_data );

        $update_data = array(
            'update_time' =>time(),
            'last_content' => $content,
            'last_uid'  =>$this->mid
        );
        D( 'Message' )->where(array('id'=>$id))->save( $update_data);

        $data['avatar'] =  uavatar($this->mid);
        $data['content'] = $content;
        $data['create_time'] = friendly_date(time());

        $this->ajaxReturn($data);
    }
}
?>
