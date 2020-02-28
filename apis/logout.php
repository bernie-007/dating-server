<?php

  require_once "../modules/database.php";

  $userId = $_POST["userId"];
  $sql = "UPDATE users SET `status` = 0 WHERE id = $userId";
  $response = [];

  if ($conn->query($sql) === TRUE) {
    $response["status"] = 200;
  } else {
    $response["status"] = 400;
  }

  echo json_encode($response);

?>