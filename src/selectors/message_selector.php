<?php

/* require_once './src/services/weather.php'; */
namespace Bot\Selectors\Message_Selector;

use function Bot\Services\Weather\get_weather;
use function Bot\Api\vk_api_msgSend;

require_once './src/api/index.php';

function msg_selector($msg, $chat_id) {
  switch($msg) {
    case (preg_match('/погода\s/', $msg) ? true : false):
      $region = strstr($msg, ' ');
      $city = substr($region, 1);
      $weather = get_weather($city);

      vk_api_msgSend($chat_id, $weather);
      break;
    case 'помощь':
      vk_api_msgSend($chat_id, "Доступные команды:\n Погода (город)");
      break;
  }
};
