<?php

namespace Bot\Selectors\Message_Selector;

use Bot\User;
use Bot\Weather;
use function Bot\Services\Weather\get_weather;
use function Bot\Api\vk_api_msgSend;
use function Bot\Utils\format_city_name;
use function Bot\Utils\mb_lcfirst;

function msg_selector($msg, $chat_id) {
  $user = new User();
  $has_userSignatures = $user->hasUser($chat_id);

  if (!$has_userSignatures) {
    $user->register($chat_id);
  }

  switch($msg) {
    case (preg_match('/погода\s/', $msg) ? true : false):
      $words = explode(' ', $msg);
      array_shift($words);
      $isSubscribe = $words[0] === 'подписаться';
      $weather = new Weather();

      if ($isSubscribe) {
        array_shift($words);
        $reg_location = implode(' ', $words);
        $formated_location = format_city_name(mb_lcfirst($reg_location));
        $weather->register_weather_reciept($formated_location, $chat_id);
        break;
      }

          /* error_log(print_r(self::$connect->error, true)); */
      $city = implode(' ', $words);
      $formated_city = format_city_name(mb_lcfirst($city));
      $weather_msg = $weather->get_weather($formated_city);
      vk_api_msgSend($chat_id, $weather_msg);
      break;
    case 'помощь':
      vk_api_msgSend($chat_id, "Доступные команды:\n Погода (город)");
      break;
    default:
      break;
  }
};
