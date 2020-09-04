<?php

use Slim\Http\ServerRequest;
use Slim\Http\Response;

require_once './src/api/index.php';
require_once './src/services/wether.php';

$app->get('/', function(ServerRequest $req, Response $res) {
  return $res->write('Hi');
});

$app->post('/bot', function(ServerRequest $req, Response $res) {
  $data = json_decode(file_get_contents('php://input'));
  include '.env.php';
  try {
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
        if (preg_match('/погода\s/', $message_text)) {
          $region = strstr($message_text, ' ');
          $city = substr($region, 1);
          $weather = get_weather($city);
          vk_api_msgSend($chat_id, $weather);
        }
        /* echo('ok'); */
        break;
      default:
        echo('ok');
    }
  } catch(Exception $e) {
    echo('ok');
  }
  return $res;
});
