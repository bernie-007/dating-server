<?php
  require_once "../modules/database.php";

  $phonecode = $_POST["phonecode"];
  $phonenum = $_POST["phonenum"];
  $timeZone = $_POST["timeZone"];
  $verified = $_POST["verified"];
  $simCardSerialNumber = $_POST["simCardSerialNumber"];
  $password = $_POST["password"];

  $sql = "SELECT * FROM users WHERE phonecode = '$phonecode' AND phonenum = '$phonenum' AND simserial = '$simCardSerialNumber'";
  $result = $conn->query($sql);
  $now = date("Y-m-d H:i:s"); // now time
  $name = '';
  $photo = '';
  $message = '';
  $userId = null;

  // if user is not exist...
  if( $result->num_rows == 0 ) {
    if ($verified == 1) {
      $newSql = "INSERT INTO users (phonenum, phonecode, `password`, simserial, timeZone, active, `status`, created_at, updated_at) VALUES ('$phonenum', '$phonecode', '$password', '$simCardSerialNumber', '$timeZone', 1, 0, '$now', '$now')";
      if( $conn->query($newSql) === TRUE ) {
        $userId = $conn->insert_id;
        $message = "contact";
      } else {
        $message = "error";
      }
      // initialize user setting...
      $distanceMin = 0;
      $distanceMax = 160;
      $ageMin = 18;
      $ageMax = 48;
      $showMe = 0;
      $notification = 1;
      $insSql = "INSERT INTO user_settings (user_id, distance_min, distance_max, age_min, age_max, showMe, notification, created_at, updated_at)
      VALUES ($userId, $distanceMin, $distanceMax, $ageMin, $ageMax, $showMe, $notification, '$now', '$now')";
      if( $conn->query($insSql) === TRUE ) {
        $message = "contact";
      } else {
        $message = "error";
      }
    } else {
      $message = "verify";
    }
  } else {
    $user = array();
    foreach( $result as $row ) {
      $user = $row;
    }
    if ($password == $user['password']) {
      $sql = "UPDATE users SET timeZone = '$timeZone' WHERE id = ".$user['id'];
      $conn->query($sql);
      if ($user['active'] == 1) {
        $userId = $user['id'];
        $sql = "SELECT * FROM user_contacts WHERE user_id = $userId";
        $result = $conn->query($sql);
        // if user has already editted their profiles...
        if( $result->num_rows > 0 ) {
          $contact = null;
          foreach($result as $row) {
            $contact = $row;
          }
          // fetch user's information...
          $name = $contact['name'];
          if($row['photo'] == '' || is_null($row['photo'])) {
            $message = "photo";
          } else {
            $sql = "UPDATE users SET status = 1 WHERE id = $userId";
            $conn->query($sql);
            $message = "home";
            $photo = $contact['photo'];
          }
        } else {
          $message = "contact";
          $name = '';
          $photo = '';
        }
      } else {
        $message = "blocked";
        $name = '';
        $photo = '';
      }
    } else {
      $message = 'password';
      $name = '';
      $photo = '';
    }
  }

  $result = [
    'message' => $message,
    'userId' => $userId,
    'name' => $name,
    'photo' => $photo,
    'timeZone' => $timeZone,
  ];

  echo json_encode($result);

 ?>
