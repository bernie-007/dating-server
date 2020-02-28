<?php

  require __DIR__ . '/vendor/autoload.php';

  $message = $_POST['message'];

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
  $data['message'] = $message;

  $pusher->trigger('notification-channel', 'new-notification', $data);

  echo json_encode([
    'status' => 200,
    'message' => 'success'
  ]);

?>