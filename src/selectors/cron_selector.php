<?php

namespace Bot\Selectors\Cron_Selector;
use Bot\Weather;
use Bot\mail;
use function Bot\Api\vk_api_msgSend;

function cron_selector($action) {
  switch ($action) {
    case 'weather':
      $weather = new Weather();
      $weather->handle_weather_units();
      /* vk_api_msgSend($chat_id, $sub_message); */
      break;
    case 'mail':
      $mail = new Mail();
      $units = $mail->handle_mail_units();
      if (isset($units)) {
        foreach($units as $unit) {
          vk_api_msgSend($unit['user_id'], $unit['message']);
        }
      }
    default:
      break;
  }
}
