<?php

use Slim\Http\ServerRequest;
use Slim\Http\Response;


$app->get('/', function(ServerRequest $req, Response $res) {
  $res->write('Hello');
  include '.env.php';
  echo $CONF_TOKEN;
  return $res;
});

$app->post('/bot', function(ServerRequest $req, Response $res) {
  $data = json_decode(file_get_contents('php://input'));
  include '.env.php';

  function vk_msg_send($peer_id, $text) {
    include '.env.php';
    $request_params = array(
      'message' => $text,
      'peer_id' => $peer_id,
      'access_token' => $TOKEN,
      'v' => '5.103',
      'random_id' => rand(1000, 99999),
    );

    $get_params = http_build_query($request_params);
    file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
    echo 'https://api.vk.com/method/messages.send?'. $get_params;
  };

  switch ($data->type) {
    case 'confirmation':
      echo $CONF_TOKEN;
      break;
    case 'message_new':
      $message_text = $data->object->message->text;
      $chat_id = $data->object->message->from_id;
      if ($message_text == "privet"){
        vk_msg_send($chat_id, "Привет, я бот, который говорит две фразы.");
      }
      if ($message_text == "пока"){
        vk_msg_send($chat_id, "Пока. Если захочешь с кем-то поговорить, то у тебя есть бот, который говорит две фразы.");
      }
      echo 'ok';
      break;
  }
  return $res;
});
