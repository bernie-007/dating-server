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
  
  $now = date("Y-m-d H:i:s");

  $from = $_POST['from'];
  $to = $_POST['to'];
  $text = $_POST['text'];
  $toTimeZone = $_POST['toTimeZone'];
  $fromTimeZone = $_POST['fromTimeZone'];

  // convert current time with sender timezone
  $date = new DateTime(null, new DateTimeZone('Europe/London'));
  $date->setTimezone(new DateTimeZone($fromTimeZone));
  $fromTime = $date->format('j \of M Y, h:i a');
  // convert current time with receiver timezone
  $date = new DateTime(null, new DateTimeZone('Europe/London'));
  $date->setTimezone(new DateTimeZone($toTimeZone));
  $toTime = $date->format('j \of M Y, h:i a');

  $response = [];
  if ($text != '') {
    $sql = "INSERT INTO messages (`from`, `to`, `text`, `created_at`, `updated_at`) VALUES ($from, $to, '$text', '$now', '$now')";
    if ($conn->query($sql) === TRUE) {
      $response['status'] = 'success';
      $response['time'] = $fromTime;
      // trigger new-message event on message-channel with pusher
      $data['text'] = $text;
      $data['from'] = $from;
      $data['to'] = $to;
      $data['time'] = $toTime;
      $result = $pusher->trigger('message-channel', 'new-message-'.$to, $data);
    } else {
      $response['status'] = 'failed';
      $response['time'] = $time;
    }
  } else {
    $response['status'] = 'empty';
  }
  
  $conn->close();
  
  // echo $dir;
  echo json_encode($response);

 ?>
