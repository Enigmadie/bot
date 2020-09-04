<?php

include '.env.php';
define('WEATHER_API_HOST', 'http://dataservice.accuweather.com/');
define('WEATHER_API_TOKEN', $WEATHER_TOKEN);

function register_weather_reciept($params) {
  return;
}

function get_weather($region) {
  $data = get_forecasts($region);
  return $data;
};

function get_forecasts($region) {
  $params = array(
    'apiKey' => WEATHER_API_TOKEN,
    'language' => 'ru-ru',
    'details' => true,
    'metric' => true,
  );
  $query = http_build_query($params);

  $key = get_locationKey($region);
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
  $filtered_date = array_filter($response[0], fn($key) => $key % 3 === 0, ARRAY_FILTER_USE_KEY);
  if (!$response) {
    throw new Exception("Invalid response for {$region} request");
  }
  return $filtered_date;
}

function get_locationKey($region) {
  $params = array(
    'apiKey' => WEATHER_API_TOKEN,
    'q' => $region,
    'language' => 'ru-ru',
  );
  $query = http_build_query($params);

  echo $query;

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
  echo $response;
  if (!$response) {
    throw new Exception("Invalid response for {$region} request");
  }
  print_r($response);
  return $response[0]['key'];
}
