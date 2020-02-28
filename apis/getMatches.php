<?php

  require_once '../modules/database.php';

  $response = [];
  $matches = [];

  $userId = $_POST['userId'];

  $matSql = "SELECT 
              IF(left_id=$userId, right_id, left_id) as user_id,
              created_at
            FROM matches
            WHERE left_id = $userId OR right_id = $userId";
  $sql = "SELECT
            tbl.user_id as id,
            uct.name as name,
            uct.photo as photo,
            usr.timeZone as timeZone,
            tbl.created_at as created_at
          FROM ($matSql) AS tbl
            LEFT JOIN user_contacts AS uct ON tbl.user_id = uct.user_id
            LEFT JOIN users AS usr ON uct.user_id = usr.id";
  $result = $conn->query($sql);
  if( $result->num_rows > 0 ) {
    foreach( $result as $row ) {
      $time = date("j \of M Y");
      $row['time'] = $time;
      $matches[] = $row;
    }
    $response['status'] = 200;
  } else {
    $response['status'] = 400;
  }
  $response['data'] = $matches;

  echo json_encode($response);

?>