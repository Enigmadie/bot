<?php

namespace Bot\Selectors\Message_Selector;

use function Bot\Services\Weather\get_weather;
use function Bot\Api\vk_api_msgSend;
use function Bot\Utils\format_city_name;
use function Bot\Utils\mb_lcfirst;

function msg_selector($msg, $chat_id) {
  switch($msg) {
    case (preg_match('/погода\s/', $msg) ? true : false):
      $region = strstr($msg, ' ');
      $city = substr($region, 1);
      $formated_city = format_city_name(mb_lcfirst($city));

      $weather = get_weather($formated_city);
      vk_api_msgSend($chat_id, $weather);
      break;
    case 'помощь':
      vk_api_msgSend($chat_id, "Доступные команды:\n Погода (город)");
      break;
  }
};
