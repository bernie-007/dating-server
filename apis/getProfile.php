<?php

  require_once '../modules/database.php';

  $response = [];

  $userId = $_POST['userId'];

  $sql = "SELECT * FROM user_contacts WHERE user_id = $userId";
  $result = $conn->query($sql);

  $profile = [];

  if ($result->num_rows > 0) {
    foreach ($result as $row) {
      $profile = $row;
    }
    $data = [];
    $data['fullName'] = $profile['name'];
    $data['parseBirthday'] = $profile['birthday'];
    $data['birthday'] = date("j/n/Y", strtotime($profile['birthday']));
    $data['gender'] = $profile['gender'];
    $response['status'] = 200;
    $response['data'] = $data;
  } else {
    $response['status'] = 400;
    $response['data'] = [];
  }

  echo json_encode($response);

?>