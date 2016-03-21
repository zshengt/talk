<?php
    /*var_dump(PHP_OS);
    echo "\n";
    echo 100.200;
    echo "\n";
    echo 100 . 200;
    if('ABC'==0)
      echo 'yes';
    else
      echo 'no'; 
    echo 100 ."ABC";*/
    ob_start(); //打开缓冲区
    echo "Hellon"; //输出
    //header("location:index.php"); //把浏览器重定向到index.php
    ob_end_flush();//输出全部内容到浏览器
?>