<?php

  require_once '../modules/database.php';

  $response = [];

  $distance = $_POST['distance'];
  $age = $_POST['age'];
  $gender = $_POST['gender'];
  $userId = $_POST['userId'];
  $showMe = 0;

  switch($gender) {
    case 'Both':
      $showMe = 0;
      break;
    case 'Man':
      $showMe = 1;
      break;
    case 'Woman':
      $showMe = 2;
      break;
  }

  $sql = "UPDATE user_settings SET distance_max = $distance, age_max = $age, showMe = $showMe WHERE user_id = $userId";
  if( $conn->query($sql) === TRUE ) {
    $response['status'] = 200;
  } else {
    $response['status'] = 400;
  }

  echo json_encode($response);

?>