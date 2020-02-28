<?php
  
  require_once "../modules/database.php";

  $response = [];

  $now = date("Y-m-d");
  $year = date("Y");
  $userId = $_POST['userId'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  // $userId = 37;
  // $latitude = -4.2368199;
  // $longitude = 15.27289;

  $sql = "SELECT * FROM user_settings WHERE user_id = $userId";
  $result = $conn->query($sql);
  
  $setting = [];
  if( $result->num_rows > 0 ) {
    foreach( $result as $row ) {
      $setting = $row;
    }
  }
  
  $offset = rand(1, 10);
  $minDays = 365 * $setting['age_min'];
  $maxDays = 365 * $setting['age_max'];
  $dateCreateMin = date_create(date("Y-m-d"));
  $dateCreateMax = date_create(date("Y-m-d"));
  date_add($dateCreateMin, date_interval_create_from_date_string("-$minDays days"));
  date_add($dateCreateMax, date_interval_create_from_date_string("-$maxDays days"));
  $birthMin = date_format($dateCreateMin, "Y-01-01");
  $birthMax = date_format($dateCreateMax, "Y-12-31");
  
  if( $setting['showMe'] == 0 ) $genderWhere = '';
  else $genderWhere = " AND gender = ".$setting['showMe'];

  $rows = [];
  $sql = "SELECT
            usr.id,
            usr.timeZone,
            uct.name,
            uct.birthday,
            uct.country_code,
            uct.location_name,
            uct.latitude,
            uct.longitude,
            uct.photo,
            ulk.flag
          FROM users AS usr
            LEFT JOIN user_contacts AS uct ON usr.id = uct.user_id
            LEFT JOIN (SELECT * FROM user_likes WHERE user_id = $userId) AS ulk ON uct.user_id = ulk.favorite_id
          WHERE usr.id <> $userId AND uct.birthday <= '$birthMin' AND uct.birthday >= '$birthMax'$genderWhere";
  $result = $conn->query($sql);
  
  // $count = 0;
  if( $result->num_rows > 0 ) {
    foreach( $result as $row ) {
      // if( $count > 20 ) {
      //   break;
      // }
      if( is_null($row['flag']) && !is_null($row['photo']) && !is_null($row['name']) ) {
        $birthYear = date("Y", strtotime($row['birthday']));
        $age = $year - $birthYear;
        
        // calculate distance with latitude and longitude
        $lat1 = $row['latitude'];
        $lat2 = $latitude;
        $lon1 = $row['longitude'];
        $lon2 = $longitude;
        $r = 6371; // Radius of the earth in km
        $dLat = deg2rad($lat2-$lat1);  // deg2rad below
        $dLon = deg2rad($lon2-$lon1); 
        $a = sin($dLat/2) * sin($dLat/2) +
          cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
          sin($dLon/2) * sin($dLon/2); 
        $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
        $d = $r * $c; // Distance in km
        if ($d < 0) $d = $d * (-1);
        // if( $d <= $setting['distance_max'] ) {
          $row['age'] = $age;
          $row['distance'] = ceil($d);
          $rows[] = $row;
          // $count++;
        // }
      }
    }
    $response['status'] = 200;
  } else {
    $response['status'] = 400;
  }
  $conn->close();

  $response['data'] = $rows;
  $response['offset'] = $offset;

  echo json_encode($response);

 ?>
