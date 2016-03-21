<?php
    /**
    * 
    */
    class  SortUtils
    {
        
        function __construct()
        {

        }

        /*杂交排序
        *(我也不清楚什么排序)
        */
        public static function bubble_sort_1($arr = array()){
            $sum = count($arr);
            for($i=0; $i<$sum-1; $i++){
                for ($j=$i+1; $j<$sum; $j++) { 
                    if($arr[$i]>$arr[$j]){
                        $middle = $arr[$i];
                        $arr[$i] = $arr[$j];
                        $arr[$j] = $middle;
                    }
                }
            }
            return $arr;
        }

        /*冒泡排序(三)
        *大的往上浮
        */
        public static function bubble_sort_2($arr = array()){
            $sum = count($arr);
            for($i=0; $i<$sum; $i++){
                for ($j=0; $j<$sum-$i-1; $j++) { 
                    if($arr[$j]>$arr[$j+1]){
                        $middle = $arr[$j];
                        $arr[$j] = $arr[$j+1];
                        $arr[$j+1] = $middle;
                    }
                }
            }
            return $arr;
        }

        /*选择排序
        *
        */
        public static function select_sort($arr = array()){
            $sum = count($arr);
            for ($i=0; $i < $sum; $i++) { 
                $index = $i;
                for($j=$i+1; $j <$sum-1; $j++){
                    if($arr[$i] > $arr[$j]){
                        $index = $j;
                    }
                }
                if($index != $i){
                    $temp = $arr[$i];
                    $arr[$i] = $arr[$index];
                    $arr[$index] = $temp;
                }
            }
            return $arr;
        }

        /*快速排序
        *
        */
        public static function quick_sort($arr = array()){
            $sum = count($arr);
            $left = array();
            $right = array();
            if($sum<=1){
                return $arr;
            }
            $key = $arr[0];
            for ($i=0; $i < $sum; $i++) { 
                if($key > $arr[$i]){
                    $left[] = $arr[$i];
                }elseif($key < $arr[$i]){
                    $right[] = $arr[$i];
                }
            }
            $left = self::quick_sort($left);
            $right = self::quick_sort($right);
            return array_merge($left, array($key), $right);
        } 

        /*插入排序
        *
        */
        public static function insert_sort($arr = array()){
            
        }
    }
    $arr = array(11,4,5,3,2,8,22,10,1,0);
    $arr = SortUtils::bubble_sort_1($arr);
    print "杂交排序:";
    var_dump($arr);
    echo "</br>"; 
    $arr = SortUtils::bubble_sort_2($arr);
    print "冒泡排序:";
    var_dump($arr);
    echo "</br>"; 
    $arr = SortUtils::select_sort($arr);
    print "选择排序:";
    var_dump($arr);
    echo "</br>"; 
    $arr = SortUtils::quick_sort($arr);
    print "快速排序:";
    var_dump($arr);
    echo "</br>";
?>