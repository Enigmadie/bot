<?php

require_once './src/services/weather.php';
require_once './src/api/index.php';

function msg_selector($msg, $chat_id) {
  switch($msg) {
    case (preg_match('/погода\s/', $msg) ? true : false):
      $region = strstr($msg, ' ');
      $city = substr($region, 1);
      $weather = get_weather($city);

      vk_api_msgSend($chat_id, $weather);
    case 'помощь':
      vk_api_msgSend($chat_id, "Доступные команды:\n Погода (город)");
  }
};
