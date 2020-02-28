<?php

  include "../modules/database.php";

  $countryCode = $_POST['countryCode'];
  $phoneCode = '';

  $sql = "SELECT phonecode FROM countries WHERE iso2 = '$countryCode'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while( $row = $result->fetch_assoc() ) {
      $phoneCode = $row['phonecode'];
    }
  }
  $conn->close();

  echo $phoneCode;

 ?>
