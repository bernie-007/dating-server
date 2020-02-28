<?php

  require_once '../modules/database.php';

  $response = [];
  $rows = [];

  $userId = $_POST['userId'];

  $sql = "SELECT distance_max as distance, age_max as age, showMe FROM user_settings WHERE user_id = $userId";
  $result = $conn->query($sql);

  if( $result->num_rows > 0 ) {
    foreach( $result as $row ) {
      $rows = $row;
    }
    $response['status'] = 200;
  } else {
    $response['status'] = 400;
  }
  $response['data'] = $rows;

  echo json_encode($response);

?>