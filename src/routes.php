<?php

use Slim\Http\ServerRequest;
use Slim\Http\Response;

require_once './src/api/index.php';
$app->get('/', function(Response $res) {
  return $res->write('Hi');
});
$app->post('/bot', function(ServerRequest $req, Response $res) {
  $data = json_decode(file_get_contents('php://input'));
  include '.env.php';
  switch ($data->type) {
    case 'confirmation':
      echo $CONF_TOKEN;
      break;
    case 'message_new':
      $message_text = $data->object->message->text;
      $chat_id = $data->object->message->from_id;
      if ($message_text == "привет"){
        vk_api_msgSend($chat_id, "Привет, я бот, который говорит две фразы.");
      }
      if ($message_text == "пока"){
        vk_api_msgSend($chat_id, "Пока. Если захочешь с кем-то поговорить, то у тебя есть бот, который говорит две фразы.");
      }
      echo 'ok';
      break;
  }
  return $res;
});
