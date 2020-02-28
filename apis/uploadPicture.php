<?php
  error_reporting(E_ERROR | E_PARSE);
  include "../modules/database.php";

  $response = [];

  $userId = $_POST["userId"];
  $file = base64_decode($_POST["image"]);
  $name = $_POST["name"];
  // uploading file...
  $destination_dir = '/var/www/html/images/';
  $target_file = $destination_dir.$name;

  file_put_contents($target_file, $file);

  $sql = "UPDATE user_contacts SET photo = '$name' WHERE user_id = $userId";
  if( $conn->query($sql) == TRUE ) {
    $sql = "UPDATE users SET status = 1 WHERE id = $userId";
    $conn->query($sql);
    $response['status'] = 200;
    $response['message'] = 'success';
  } else {
    $response['status'] = 400;
    $response['message'] = 'failed';
  }
  $conn->close();

  echo json_encode($response);
 ?>
