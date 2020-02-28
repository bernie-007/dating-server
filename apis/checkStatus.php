<?php

  require_once "../modules/database.php";

  $serialNumber = $_POST["simCardSerialNumber"];

  $sql = "SELECT * FROM users WHERE simserial = '$serialNumber' AND `status` = 1 AND active = 1";
  $result = $conn->query($sql);
  $user = [];
  $response = [];

  if ($result->num_rows > 0) {
    $sql = "SELECT 
              usr.id as userId,
              usr.timeZone as timeZone,
              uct.name as name,
              uct.photo as photo 
            FROM users AS usr
              LEFT JOIN user_contacts AS uct ON usr.id = uct.user_id
            WHERE usr.simserial = '$serialNumber' AND status = 1 AND active = 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      foreach ($result as $row) {
        $user = $row;
      }
      $response['status'] = 'online';
      $response['userId'] = $user['userId'];
      $response['timeZone'] = $user['timeZone'];
      $response['name'] = $user['name'];
      $response['photo'] = $user['photo'];
    } else {
      $response['status'] = 'offline';
    }
  } else {
    $response['status'] = 'offline';
  }

  echo json_encode($response);

?>