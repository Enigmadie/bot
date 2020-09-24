<?php

namespace Bot\Services\Weather;

define('WEATHER_API_HOST', 'http://dataservice.accuweather.com/');

function get_forecasts($region) {
  $key = get_locationKey($region);
  if (!isset($key)) {
    return null;
  }
  $params = array(
    'apikey' => $_ENV['WEATHER_TOKEN'],
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
    throw new \Exception("Failed {$region} request");
  }
  curl_close($curl);
  $response = json_decode($json, true);
  if (!$response) {
    throw new \Exception("Invalid response for {$region} request");
  }
  $filtered_date = array_filter($response, fn($key) => $key % 3 === 0, ARRAY_FILTER_USE_KEY);
  return $filtered_date;
}

function get_locationKey($region) {
  $params = array(
    'apikey' => $_ENV['WEATHER_TOKEN'],
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
    throw new \Exception("Failed {$region} request");
  }
  curl_close($curl);
  $response = json_decode($json, true);
  return $response[0]['Key'] ?? null;
}
