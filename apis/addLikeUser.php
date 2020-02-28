<?php

  require_once '../modules/database.php';

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

  $data['message'] = 'Someone likes you. He/She is waiting for your response';
  $pusher->trigger('notification-channel', 'new-notification-'.$favoriteId, $data);

  $now = date("Y-m-d H:i:s");
  $response = [];

  $userId = $_POST['userId'];
  $favoriteId = $_POST['favoriteId'];
  $flag = $_POST['flag'];

  $sql = "SELECT * FROM user_likes WHERE user_id = $userId AND favorite_id = $favoriteId";
  $result = $conn->query($sql);
  if ($result->num_rows == 0) {
    $sql = "INSERT INTO user_likes (`user_id`, `favorite_id`, `flag`, `created_at`, `updated_at`) 
    VALUES ($userId, $favoriteId, $flag, '$now', '$now')";

    if( $conn->query($sql) === TRUE ) {
      $response['status'] = 'success';
      $response['code'] = 200;
    } else {
      $response['status'] = 'failed';
      $response['code'] = 500;
    }
  }
  if ($flag != 0) {
    $sql = "SELECT * FROM user_likes WHERE user_id = $favoriteId AND favorite_id = $userId AND flag <> 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $sql = "INSERT INTO matches (left_id, right_id, created_at, updated_at) VALUES ($userId, $favoriteId, '$now', '$now')";
      if ($conn->query($sql) === TRUE) {
        $data['message'] = 'Congratuations! You get match. You can start chatting with your likes.';
        $pusher->trigger('notification-channel', 'new-notification-'.$favoriteId, $data);
        $pusher->trigger('notification-channel', 'new-notification-'.$userId, $data);
      }
    }
  }

  echo json_encode($response);

?>