<?php

namespace Bot\Selectors\Message_Selector;

use Bot\User;
use Bot\Weather;
use Bot\Mail;
use function Bot\Api\vk_api_msgSend;
use function Bot\Utils\format_city_name;
use function Bot\Utils\mb_lcfirst;

function msg_selector($msg, $chat_id) {
  $user = new User();
  $userId = $user->get_id($chat_id);

  if (!isset($userId)) {
    $user->register($chat_id);
  }

  switch($msg) {
    case (preg_match('/погода\s/', $msg) ? true : false):
      $words = explode(' ', $msg);
      array_shift($words);
      $isSubscribe = $words[0] === 'подписаться';
      $isUnsubscribe = $words[0] === 'отписаться';
      $weather = new Weather();

      if ($isSubscribe || $isUnsubscribe) {
        array_shift($words);
        $reg_location = implode(' ', $words);
        $formated_location = format_city_name(mb_lcfirst($reg_location));
        $sub_message = $isSubscribe
          ? $weather->register_weather_reciept($formated_location, $chat_id)
          : $weather->unregister_weather_reciept($chat_id);
        vk_api_msgSend($chat_id, $sub_message);
        break;
      }

      $city = implode(' ', $words);
      $formated_city = format_city_name(mb_lcfirst($city));
      $weather_msg = $weather->get_weather($formated_city);
      if (isset($weather_msg)) {
        vk_api_msgSend($chat_id, $weather_msg);
      }
      break;

    case (preg_match('/почта\s/', $msg) ? true : false):
      $words = explode(' ', $msg);
      array_shift($words);
      $mail = new Mail();
      $data = $mail->register_mail_track($words[0], $chat_id);
      vk_api_msgSend($chat_id, $data['message']);
      break;

    case 'команды':
      vk_api_msgSend($chat_id, "Доступные команды:
        \n Погода (город)
        \n Погода подписаться (город)
        \n Погода отписаться
        \n Почта (трек-номер)");
      break;
    default:
      break;
  }
};
