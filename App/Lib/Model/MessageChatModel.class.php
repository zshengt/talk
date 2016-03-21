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

class MessageChatModel extends  Model {

	protected $tableName = 'message_chat';
	public function getMessageChat($id, $username){
		$chat_lists['data'] = $this->where(array('ms_id'=>$id))->order('create_time DESC')->selectPage( 10 );
		if ( !empty( $this->page ) ) {
			$chat_lists['page'] = $this->page;
		}
		if (!empty($chat_lists)) {
			foreach ($chat_lists['data'] as $key => $chat) {
				$chat_lists['data'][$key]['username'] = $username;
			}
			return  $chat_lists;
		}
	}

}

?>