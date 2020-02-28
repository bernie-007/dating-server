<?php

  require_once "../modules/database.php";
  require __DIR__ . '/vendor/autoload.php';

  // set options of pusher
  $options = array(
    'cluster' => 'ap2',
    'useTLS' => true
  );

  $pusher = new Pusher\Pusher(
    '982fdfab1791cf18a037',
    '0a87a0e1a1415570534a',
    '927050',
    $options
  );

  $phoneNumber = $_POST["phoneNum"];
  $phoneCode = $_POST["phoneCode"];
  $sql = "SELECT * FROM users WHERE phonenum = '$phoneNumber' AND phonecode = '$phoneCode'";
  $result = $conn->query($sql);

  $response = [];
  $user = [];

  if ($result->num_rows > 0) {
    foreach ($result as $row) {
      $user = $row;
    }
    $response['status'] = 200;
    $response['password'] = $password = $user['password'];
    $data['message'] = "Your password is: $password";
    $result = $pusher->trigger('notification-channel', 'new-notification-'.$phoneCode.$phoneNumber, $data);
  } else {
    $response['status'] = 400;
  }

  echo json_encode($response);

?>