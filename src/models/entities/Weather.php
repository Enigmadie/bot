<?php

namespace Bot;

use function Bot\Services\Weather\get_forecasts;
use function Bot\Services\Weather\get_locationKey;
use function Bot\Utils\mb_ucfirst;
use function Bot\Utils\select_vk_icon;

use Bot\Db_actions;

use Bot\User;

class Weather {
  private static $table = 'weather';

  public function get_weather($region) {
    $data = get_forecasts($region);
    if (!isset($data)) {
      return null;
    }
    $msg = array_map(function($el) {
      [
        'DateTime' => $time,
        'WeatherIcon' => $icon,
        'IconPhrase' => $weather,
        'Temperature' => $temperature,
        'Wind' => $wind,
      ] = $el;

      $Weather_icon = select_vk_icon((int)$icon);
      $date = date_create($time);
      $formated_date = date_format($date, 'H:i');
   return "&#8986;{$formated_date}\n {$Weather_icon}{$weather}\n&#127777;{$temperature['Value']}°C\n&#127788;{$wind['Speed']['Value']}км/ч";
  }, $data);

    $city = mb_ucfirst($region);

    $msgString = implode("\n\n", $msg);
    return "Погода в городе {$city}:\n\n" . $msgString;
  }

  public function register_weather_reciept($location, $user_id) {
    $key = get_locationKey($location);
    if (isset($key)) {
      $user = new User();
      $id = $user->get_id($user_id);
      if (isset($id)) {
        $result_select = DB_actions::select_values(self::$table, ['user_id' => $id]);

        $is_rowEmpty = $result_select->num_rows === 0;
        $date = new \DateTime('Europe/Moscow');
        $formated_date = $date->format('Y-m-d H:i:s');

        $is_rowEmpty
          ? Db_actions::insert_values(
            self::$table,
            [
              'city' => $location,
              'created_at' => $formated_date,
              'updated_at' => $formated_date,
              'user_id' => $id,
            ],
          )
          : Db_actions::update_values(
              self::$table,
              [
                'location' => $location,
                'updated_at' => $formated_date,
              ],
              [
                'user_id' => $id,
              ]
            );
      }
      return "Вы подписаны. Теперь каждое утро вы будете получать уведомление о погоде в указанном городе.";
    }
    return "Не можем найти указанный вами город.";
  }

  public function unregister_weather_reciept($user_id) {
    $user = new User();
    $id = $user->get_id($user_id);
    if (isset($id)) {
      Db_actions::delete_values(self::$table, ['user_id' => $id]);
      return "Вы отписались от уведомлений о погоде";
    }
  }

  public function handle_weather_units() {
    $result = Db_actions::select_values(self::$table);
    $is_rowEmpty = $result->num_rows === 0;

    if (!$is_rowEmpty) {
      $weather_units = [$result->fetch_assoc()];
      $units = [];
      foreach($weather_units as $el) {
        $message = $this->get_weather($el['city']);

        if (isset($message)) {
          $user = new User();
          $user_id = $user->get_user_id($el['user_id']);
          array_push($units, [
            'user_id' => $user_id,
            'message' => $message
          ]);
        }
      }
      return count($units) > 0 ? $units : null;
    }
  }
}

