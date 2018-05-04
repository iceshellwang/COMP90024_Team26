<?php
  include("assets/php/connection.php");
  $key = 'AIzaSyBafCANRIdz41dwRNf_GnNVCN4Mbeg3uCw';
  $result = array();
  if(isset($_POST['lat'])&&isset($_POST['lng'])){
    $q = "SELECT * FROM CarPark";
    $r = mysqli_query($dbc, $q);
    while($data = mysqli_fetch_assoc($r)) {
      if($data['vacant'] == 0) {
        $dis = distanceSimplify($_POST['lat'], $_POST['lng'], $data['lat'], $data['lng']);
        if($dis <= 000) {
          $value['dis'] = $dis;
          $value['data'] = $data;
          array_push($result, $value);
        }
      }
    }
  }
  $myObj['results'] = $result;
  $myJSON = json_encode($myObj);
  echo $myJSON;

  function distanceSimplify($lat1, $lng1, $lat2, $lng2) {
    $EARTH_RADIUS = 6378137;
    $radLat1 = rad($lat1);
    //echo $radLat1;
    $radLat2 = rad($lat2);
    $a = $radLat1 - $radLat2;
    $b = rad($lng1) - rad($lng2);
    $s = 2 * asin(sqrt(pow(sin($a/2),2) +
    cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
    $s = $s * $EARTH_RADIUS;
    $s = round($s * 10000) / 10000;
    return $s;
  }

  function rad($d)
  {
    return $d * 3.1415926535898 / 180.0;
  }

?>