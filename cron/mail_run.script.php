<?php

define('SERVER_ENDPOINT', 'https://bot.godos.ru');

$params = array(
  'type' => 'cron_script',
  'object' => [
    'action' => "mail",
  ]
);

$payload = json_encode($params);

$curl = curl_init(SERVER_ENDPOINT);
curl_setopt( $curl, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

curl_exec($curl);

$error = curl_error($curl);

if ($error) {
  throw new \Exception("Failed {$method} request");
}

curl_close($curl);


