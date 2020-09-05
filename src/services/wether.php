<?php

include '.env.php';
define('WEATHER_API_HOST', 'http://dataservice.accuweather.com/');
define('WEATHER_API_TOKEN', $WEATHER_TOKEN);

function register_weather_reciept($params) {
  return;
}

function get_weather($region) {
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

   return "{$formated_date} {$weather} {$temperature['Value']}°C {$wind['Speed']['Value']}км/ч";
  }, $data);

  $msgString = implode("\n", $msg);
  return $msgString;
};

function get_forecasts($region) {
  $key = get_locationKey($region);
  /* return $key; */
  if (!isset($key)) {
    return null;
  }
  $params = array(
    'apikey' => WEATHER_API_TOKEN,
    'language' => 'ru-ru',
    'details' => 'true',
    'metric' => 'true',
  );
  $query = http_build_query($params);

  $path = WEATHER_API_HOST . "forecasts/v1/hourly/12hour/{$key}";
  $url = $path . '?' . $query;

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $json = curl_exec($curl);
  $error = curl_error($curl);
  if ($error) {
    throw new Exception("Failed {$region} request");
  }
  curl_close($curl);
  $response = json_decode($json, true);
  if (!$response) {
    throw new Exception("Invalid response for {$region} request");
  }
  $filtered_date = array_filter($response, fn($key) => $key % 3 === 0, ARRAY_FILTER_USE_KEY);
  return $filtered_date;
}

function get_locationKey($region) {
  $params = array(
    'apikey' => WEATHER_API_TOKEN,
    'q' => $region,
    'language' => 'ru-ru',
  );
  $query = http_build_query($params);
  $path = WEATHER_API_HOST . 'locations/v1/cities/search';
  $url = $path . '?' . $query;

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $json = curl_exec($curl);
  $error = curl_error($curl);
  if ($error) {
    throw new Exception("Failed {$region} request");
  }
  curl_close($curl);
  $response = json_decode($json, true);
  if (!$response) {
    throw new Exception("Invalid response for {$region} request");
  }
  return $response[0]['Key'] ?? null;
}
