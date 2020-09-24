<?php

namespace Bot\Selectors\Cron_Selector;
use Bot\Weather;
use Bot\mail;

function cron_selector($action) {
  switch ($action) {
    case 'weather':
      $weather = new Weather();
      $weather->handle_weather_units();
      /* vk_api_msgSend($chat_id, $sub_message); */
      break;
    case 'mail':
      $mail = new Mail();
      /* $mail->handle_weather_units(); */
    default:
      break;
  }
}
