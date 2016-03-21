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
class SettingsModel  extends  Model{
	protected $tableName = 'settings';


	public  function   getSettings(){
		$result = $this->select();
		foreach ($result as $key => $value) {
			$result[$key]['value'] = unserialize($value['value']);
		}
		$result = array_merge($result[0]['value'], $result[1]['value']);
		F('settings', $result);
		return $result;
	}
}
?>