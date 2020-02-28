<?php

  require_once "../modules/database.php";

  $userId = $_POST["userId"];
  $oldPassword = $_POST["oldPassword"];
  $newPassword = $_POST["newPassword"];
  $response = [];

  $sql = "SELECT * FROM users WHERE id = $userId AND `password` = '$oldPassword'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $sql = "UPDATE users SET `password` = '$newPassword' WHERE id = $userId";
    if ($conn->query($sql) === TRUE) {
      $response["status"] = 200;
      $response["newPassword"] = $password;
    } else {
      $response["status"] = 400;
    }
  } else {
    $response["status"] = 300;
  }

  echo json_encode($response);

?>