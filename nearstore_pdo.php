<?php
    function getDistance($lat1, $lng1, $lat2, $lng2)  
    {  
        $earthRadius = 6367000; //approximate radius of earth in meters  

        /* 
        Convert these degrees to radians 
        to work with the formula 
        */  

        $lat1 = ($lat1 * pi() ) / 180;  
        $lng1 = ($lng1 * pi() ) / 180;  

        $lat2 = ($lat2 * pi() ) / 180;  
        $lng2 = ($lng2 * pi() ) / 180;  

        /* 
        Using the 
        Haversine formula 

        http://en.wikipedia.org/wiki/Haversine_formula 

        calculate the distance 
        */  

        $calcLongitude = $lng2 - $lng1;  
        $calcLatitude = $lat2 - $lat1;  
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);    
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));  
        $calculatedDistance = $earthRadius * $stepTwo;  

        return round($calculatedDistance);  
    }
    $dsn = "sqlite:poi.db";
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $page = @$_POST['page']?$_POST['page']:1;
    $pagecount = @$_POST['pagecount']?$_POST['pagecount']:20;
    $start = ($page-1)*$pagecount;
    $end = $page*$pagecount;
    $sql = "SELECT *,getDistance(lat,lng,$lat,$lng) AS distance FROM shop ORDER BY distance LIMIT $start,$end";
    try{
        $db = new PDO($dsn);
        $db->sqliteCreateFunction('getDistance', 'getDistance');
        $ret = $db->query("SELECT count(id) AS total FROM shop");
        $res = $ret->fetchAll();
        $totalcount = $res[0]['total'];
        $stmt = $db->query($sql);
        $query = $stmt->fetchAll();
        $data['data'] = $query;
        $data['message'] = '11000';
        $data['status'] = 1;
        $data['totalcount'] = $totalcount;
        $data['page'] = $page;
        $data['pagecount'] = $pagecount;
        print_r(json_encode($data));
    }catch(PDOException $e){
        print_r(json_encode(array("status"=>1,"message"=>"12000")));
        exit;
    }
?>