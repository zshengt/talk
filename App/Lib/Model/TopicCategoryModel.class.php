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

class TopicCategoryModel  extends Model{
    protected $tableName = 'topic_category';
    public function getCateList( $field='*', $condition ) {
        $category_list = $this->field($field)->where($condition)->find();
        return $category_list;
    }


    public function getAllCates() {
        $result = $this->where(array('status'=>1))->order('view_sort')->select();
        if ( !empty( $result ) && is_array( $result ) ) {
            return  $result;
        }
    }

    /**
     * 话题回复数
     *
     * @param [type]  $tid [description]
     * @return [type]      [description]
     */
    public function incPostNum( $cid ) {
        $result = $this->where( array( 'id'=>$cid ) )->setInc( 'post_num' );
        return $result;
    }

    /**
     * 话题浏览量
     *
     * @param [type]  $tid [description]
     * @return [type]      [description]
     */
    public function incTopicNum( $cid ) {
        $result = $this->where( array( 'id'=>$cid ) )->setInc( 'topic_num' );
        return $result;
    }

    /**
     * 话题回复数
     *
     * @param [type]  $tid [description]
     * @return [type]      [description]
     */
    public function decPostNum( $cid, $num=1 ) {
        $result = $this->where( array( 'id'=>$cid ) )->setDec( 'post_num', $num );
        return $result;
    }

    /**
     * 话题浏览量
     *
     * @param [type]  $tid [description]
     * @return [type]      [description]
     */
    public function decTopicNum( $cid ) {
        $result = $this->where( array( 'id'=>$cid ) )->setDec( 'topic_num' );
        return $result;
    }




}
?>
