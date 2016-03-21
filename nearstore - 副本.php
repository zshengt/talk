<?php

    $dsn = "sqlite:poi.db";
    $sql = "SELECT id,(3959 * acos (cos (radians(37)) * cos (radians(lat)) * cos (radians(lng) - radians(- 122)) + sin (radians(37)) * sin (radians(lat)))) AS distance FROM shop ORDER BY distance LIMIT 0,20";
    try{
        $db = new PDO($dsn);
        $stmt = $db->prepare($sql);
        $stmt->execute();
        var_dump($stmt->fetchAll());
    }catch(PDOException $e){
        print_r(json_encode(array("status"=>1,"message"=>"12000")));
        exit;
    }
    //Todo
    /*$start = ($page-1)*$pagecount;
    $end = $page*$pagecount;
    $db->createFunction('getDistance', 'getDistance');
    $ret = $db->query("SELECT count(id) AS total FROM shop");
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $totalcount = $row['total'];
    $ret = $db->query("SELECT *,getDistance(lat,lng,$lat,$lng) AS distance FROM shop ORDER BY distance LIMIT $start,$end");
    $arr = array();
    while($row = $ret->fetchArray(SQLITE3_ASSOC)){
        array_push($arr, $row);
    }
    $data['data'] = $arr;
    $data['message'] = '11000';
    $data['status'] = 1;
    $data['totalcount'] = $totalcount;
    $data['page'] = $page;
    $data['pagecount'] = $pagecount;
    print_r(json_encode($data));*/
?>