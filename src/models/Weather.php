<?php

namespace Bot;

use function Bot\Services\Weather\get_forecasts;
use function Bot\Services\Weather\get_locationKey;
use function Bot\Utils\mb_ucfirst;

use Bot\User;

class Weather {
  private static $connect;

  public static function up($connect) {
    self::$connect = $connect;
    $query = "SELECT * FROM weather";
    $result = self::$connect->query($query);

    if (empty($result)) {
      $query = "CREATE TABLE weather (
        id int(11) AUTO_INCREMENT PRIMARY KEY,
        city VARCHAR(60) NOT NULL,
        created_at DATETIME,
        updated_at DATETIME,
        user_id INT(11) UNIQUE,
        FOREIGN KEY (user_id) REFERENCES user (id)
        )";
      $result = self::$connect->query($query);
      if (self::$connect->error) {
        die("Model Weather is failed: " . self::$connect->error);
      }
    }
  }

  public function get_weather($region) {
    $data = get_forecasts($region);
    if (!isset($data)) {
      return 'Не найден';
    }
    $msg = array_map(function($el) {
      [
        'DateTime' => $time,
        'IconPhrase' => $weather,
        'Temperature' => $temperature,
        'Wind' => $wind,
      ] = $el;

      $date = date_create($time);
      $formated_date = date_format($date, 'H:m');
   return "&#8986;{$formated_date}\n &#9728;{$weather}\n&#127777;{$temperature['Value']}°C\n&#127788;{$wind['Speed']['Value']}км/ч";
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
        $query_select = "SELECT * FROM weather WHERE user_id = {$id}";
        $result_select = self::$connect->query($query_select);

        $is_rowEmpty = $result_select->num_rows === 0;
        $date = new \DateTime('Europe/Moscow');
        $formated_date = $date->format('Y-m-d H:i:s');

        $query_manipulation = $is_rowEmpty
          ? "INSERT INTO weather (city, created_at, updated_at, user_id) VALUES ('{$location}', '{$formated_date}', '${formated_date}', {$id})"
          : "UPDATE weather SET city = '{$location}', updated_at = '{$formated_date}' WHERE user_id = {$id}";
        self::$connect->query($query_manipulation);
      }
    return "Вы подписаны. Теперь каждое утро вы будете получать уведомление о погоде в указанном городе.";
    }
    return "Не можем найти указанный вами город.";
  }

  public function unregister_weather_reciept($user_id) {
    $user = new User();
    $id = $user->get_id($user_id);
    if (isset($id)) {
      $query = "DELETE FROM weather WHERE user_id = {$id}";
      self::$connect->query($query);
      return "Вы отписались от уведомлений о погоде";
    }
  }

  public function handle_weather_units() {
    $query = "SELECT * FROM weather";
    $result = self::$connect->query($query);

    $weather_units = $result->fetch_assoc();
    /* print_r($result->fetch_assoc()); */
    $is_rowEmpty = $result->num_rows === 0;
    if (!$is_rowEmpty) {
      /* array_map(fn($el) => $this->get_weather($el), $weather_units); */
    }
  }
}

