<?php

  require_once '../modules/database.php';

  $userId = $_POST['userId'];
  $flag = $_POST['flag'];

  $response = [];
  $likes = [];

  $sql = "SELECT
            uls.favorite_id as id,
            uct.name as name,
            uct.photo as photo,
            usr.timeZone as timeZone,
            mat.match_id as mat_id
          FROM user_likes AS uls
            LEFT JOIN (
              SELECT 
                id as match_id,
                IF(left_id=$userId, left_id, right_id) as mat_user_id 
              FROM matches WHERE left_id = $userId OR right_id = $userId
            ) AS mat on uls.user_id = mat.mat_user_id
            LEFT JOIN user_contacts AS uct ON uls.favorite_id = uct.user_id
            LEFT JOIN users AS usr ON uct.user_id = usr.id
          WHERE uls.user_id = $userId AND uls.flag = $flag";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    foreach ($result as $row) {
      if (!is_null($row['mat_id'])) {
        $likes[] = $row;
      }
    }
    $response['code'] = 200;
  } else {
    $response['code'] = 300;
  }
  $response['data'] = $likes;

  echo json_encode($response);

?>