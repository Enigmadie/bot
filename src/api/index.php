<?php

include '.env.php';

define('VK_API_TOKEN', $TOKEN);
define('VK_API_VERSION', '5.103');
define('VK_API_ENDPOINT', 'https://api.vk.com/method/');

function vk_api_call($method, $params = array()) {
  $params['access_token'] = VK_API_TOKEN;
  $params['v'] = VK_API_VERSION;
  $query = http_build_query($params);
  $url = VK_API_ENDPOINT . $method . '?' . $query;
  file_get_contents($url);
  /* $curl = curl_init($url); */
  /* curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); */
  /* $json = curl_exec($curl); */
  /* $error = curl_error($curl); */
  /* if ($error) { */
  /*   throw new Exception("Failed {$method} request"); */
  /* } */
  /* curl_close($curl); */
  /* $response = json_decode($json, true); */
  /* if (!$response || !isset($response['response'])) { */
  /*   throw new Exception("Invalid response for {$method} request"); */
  /* } */
  /* return $response['response']; */
}

function vk_api_msgSend($peer_id, $text) {
  return vk_api_call('message.send', array(
    'message' => $text,
    'peer_id' => $peer_id,
    'random_id' => rand(1000, 99999),
  ));
}
