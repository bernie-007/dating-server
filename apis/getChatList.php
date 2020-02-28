<?php

  require_once '../modules/database.php';

  $response = [];
  $users = [];
  $lasts = [];
  $unReads = [];

  $userId = $_POST['userId'];
  // $userId = 37;

  $usersSql = "SELECT
                  tbl.chater_id as chater_id
                FROM (
                  SELECT 
                    IF(m.from=$userId, m.to, m.from) AS chater_id
                  FROM messages as m WHERE m.from = $userId OR m.to = $userId ) AS tbl
                GROUP BY tbl.chater_id";
  $consSql = "SELECT
                tbl.chater_id as id,
                uct.name as name,
                uct.photo as photo,
                usr.timeZone as timeZone
              FROM ($usersSql) AS tbl
                LEFT JOIN user_contacts AS uct ON tbl.chater_id = uct.user_id
                LEFT JOIN users AS usr ON uct.user_id = usr.id";
  
  $lastSql = "SELECT
                tbl.chater_id AS sender_id,
                msg.text
              FROM ($usersSql) AS tbl
                LEFT JOIN messages AS msg ON tbl.chater_id = msg.from
              WHERE msg.to = $userId
              ORDER BY msg.from ASC, msg.created_at ASC";
  
  $unReadSql = "SELECT
                  msg.from AS sender_id,
                  COUNT(unread) AS pending
                FROM ($usersSql) AS tbl
                  LEFT JOIN messages AS msg ON tbl.chater_id = msg.from
                WHERE msg.to = $userId AND msg.unread = 1
                GROUP BY msg.unread, msg.from";

  $usersRes = $conn->query($consSql);
  $lastsRes = $conn->query($lastSql);
  $unReadsRes = $conn->query($unReadSql);

  if( $lastsRes->num_rows > 0 ) {
    foreach( $lastsRes as $row ) {
      $lasts[$row['sender_id']] = $row['text'];
    }
  }
  if( $unReadsRes->num_rows > 0 ) {
    foreach( $unReadsRes as $row ) {
      $unReads[$row['sender_id']] = $row['pending'];
    }
  }

  if( $usersRes->num_rows > 0 ) {
    foreach( $usersRes as $row ) {
      // checking last message
      if( $lasts[$row['id']] ) {
        $row['text'] = $lasts[$row['id']];
      } else {
        $row['text'] = null;
      }
      // checking unread messages count
      if( $unReads[$row['id']] ) {
        $row['pending'] = $unReads[$row['id']];
      } else {
        $row['pending'] = null;
      }
      $users[] = $row;
    }
    $response['status'] = 200;
  } else {
    $response['status'] = 500;
  }
  $response['data'] = $users;

  echo json_encode($response);
?>