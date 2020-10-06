<?php

namespace Bot\Selectors\Cron_Selector;
use Bot\Weather;
use Bot\Mail;
use function Bot\Api\vk_api_msgSend;

function cron_selector($action) {
  switch ($action) {
    case 'weather':
      $weather = new Weather();
      $units = $weather->handle_weather_units();
      if (isset($units)) {
        foreach($units as $unit) {
          vk_api_msgSend($unit['user_id'], $unit['message']);
        }
      }
      break;
    case 'mail':
      $mail = new Mail();
      $units = $mail->handle_mail_units();
      if (isset($units)) {
        foreach($units as $unit) {
          vk_api_msgSend($unit['user_id'], $unit['message']);
        }
      }
      break;
    default:
      break;
  }
}
