<?php
  include "../modules/database.php";

  $userId = $_POST['userId'];
  $name = $_POST['name'];
  $birthday = $_POST['birthday'];
  $gender = $_POST['gender'];
  $countryCode = $_POST['countryCode'];
  $locationName = $_POST['address'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $now = date("Y-m-d H:i:s");

  $sql = "SELECT * FROM user_contacts WHERE user_id = $userId";
  $result = $conn->query($sql);
  if( $result->num_rows > 0 ) {
    $message = "exist";
  } else {
    $sql = "INSERT INTO user_contacts (user_id, country_code, location_name, latitude, longitude, name, birthday, gender, created_at, updated_at)
    VALUES ($userId, '$countryCode', '$locationName', $latitude, $longitude, '$name', '$birthday', $gender, '$now', '$now')";

    if( $conn->query($sql) === TRUE ) {
      $message = "success";
    } else {
      $message = 'error';
    }
  }

  echo $message;

 ?>
