<?php
  $lat = 31.24916;
  $lng = 121.4878983;
  $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
  $json = file_get_contents($url);
  $data=json_decode($json);
  
  print_r($data);
  echo $data->results[0]->formatted_address;

?>