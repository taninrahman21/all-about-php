<?php

function displayKey($key) {
  printf("value='%s'", $key);
}

function scrambleData($data, $key){
  $original_key = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
  $encode_data = "";
  $data_length = strlen($data);
  for($i = 0; $i < $data_length; $i++) {
    $current_char = $data[$i];
    $char_position = strpos($original_key, $current_char);
    if($char_position !== false) {
      $encode_data .= $key[$char_position];
    } else {
      $encode_data .= $current_char;
    }
  } 
  return $encode_data;
}

function decodeData($data, $key){
  $original_key = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
  $decode_data = "";
  $data_length = strlen($data);
  for($i = 0; $i < $data_length; $i++) {
    $current_char = $data[$i];
    $char_position = strpos($key, $current_char);
    if($char_position !== false) {
      $decode_data .= $original_key[$char_position];
    } else {
      $decode_data .= $current_char;
    }
  }
  return $decode_data;
}