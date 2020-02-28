<?php

  require_once '../modules/database.php';

  $response = [];

  $userId = $_POST['userId'];
  $fullName = $_POST['fullName'];
  $birthday = $_POST['birthday'];
  $locationName = $_POST['locationName'];
  $countryCode = $_POST['countryCode'];
  $lat = $_POST['lat'];
  $lng = $_POST['lng'];

  $sql = "SELECT * FROM user_contacts WHERE user_id = $userId";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $sql = "UPDATE 
              user_contacts 
            SET 
              `name` = '$fullName', 
              `birthday` = '$birthday',
              `location_name` = '$locationName', 
              `country_code` = '$countryCode', 
              `latitude` = $lat, 
              `longitude` = $lng 
            WHERE user_id = $userId";
    if ($conn->query($sql) === TRUE) {
      $response['status'] = 200;
    } else {
      $response['status'] = 400;
    }
  } else {
    $response['status'] = 300;
  }

  echo json_encode($response);

?>