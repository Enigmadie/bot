<?php

namespace Bot;

use function Bot\Services\Weather\get_forecasts;
use function Bot\Services\Weather\get_locationKey;
use function Bot\Utils\mb_ucfirst;

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
        user_id INT(11),
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
     return "{$formated_date} {$weather} {$temperature['Value']}°C Ветер: {$wind['Speed']['Value']}км/ч";
    }, $data);

    $city = mb_ucfirst($region);

    $msgString = implode("\n\n", $msg);
    return "Погода в городе {$city}:\n\n" . $msgString;
  }

  public function register_weather_reciept($location, $user_id) {
    $key = get_locationKey($location);
    if (isset($key)) {
      $query_user = "SELECT * FROM user where user_id = {$user_id}";
      $result_userId = self::$connect->query($query_user);
      if ($result_userId->num_rows > 0) {
        $id = $result_userId->fetch_assoc()['id'];

        $query_select = "SELECT * FROM weather WHERE city = '{$location}' AND user_id = {$id}";
        $result_select = self::$connect->query($query_select);
        $is_rowEmpty = $result_select->num_rows === 0;

        if ($is_rowEmpty) {
          $date = new \DateTime('Europe/Moscow');
          $query_insert = "INSERT INTO weather (city, created_at, user_id) VALUES ('{$location}', '{$date->format('Y-m-d H:i:s')}', {$id})";
          self::$connect->query($query_insert);
        }
      }
    }
  }
}

