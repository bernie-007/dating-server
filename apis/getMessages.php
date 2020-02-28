<?php

  include "../modules/database.php";

  $from = $_POST['from'];
  $to = $_POST['to'];
  $timeZone = $_POST['fromTimeZone'];
  $now = date("Y-m-d H:i:s");

  // $sql = "SELECT * FROM matches WHERE (left_id = $from AND right_id = $to) OR (left_id = $to AND right_id = $from)";
  // $result = $conn->query($sql);
  // if( $result->num_rows == 0 ) {
  //   $sql = "INSERT INTO matches (left_id, right_id, created_at, updated_at) VALUES ($from, $to, '$now', '$now')";
  //   $conn->query($sql);
  // }

  $sql = "UPDATE `messages` SET unread = 0 WHERE `from` = $to AND `to` = $from";
  $conn->query($sql);

  $sql = "SELECT * FROM messages as msg WHERE (msg.from = $from AND msg.to = $to) OR (msg.from = $to AND msg.to = $from) ORDER BY msg.created_at ASC";
  $result = $conn->query($sql);

  $rows = [];
  $response = [];
  $tmpTime = null;
  $order = 1;
  if( $result->num_rows > 0 ) {
    foreach( $result as $row ) {
      $date = new DateTime($row['created_at'], new DateTimeZone('Europe/London'));
      $date->setTimezone(new DateTimeZone($timeZone));
      $chatTime = $date->format('h:i a');
      $time = $date->format('Y-m-d');
      $timeline = $date->format('j \of M Y');
      if (is_null($tmpTime)) {
        $tmpTime = $time;
      } else if ($tmpTime != $time) {
        $order++;
        $tmpTime = $time;
      }
      $row['order'] = $order;
      $row['created_at'] = $chatTime;
      $row['timeline'] = $timeline;
      $row['date'] = $time;
      $rows[] = $row;
    }
    $response['status'] = 'success';
  } else {
    $response['status'] = 'failed';
  }
  $response['message'] = $rows;

  $conn->close();

  echo json_encode($response);

 ?>
