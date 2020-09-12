<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

require_once './src/selectors/message_selector.php';

$app->get('/', function(Request $req, Response $res) {
  $res->getBody()->write('Hello');
  return $res;
});

$app->post('/bot', function(Request $req, Response $res) {
  include '.env.php';
  $data = json_decode(file_get_contents('php://input'));
  try {
    switch ($data->type) {
      case 'confirmation':
        echo($CONF_TOKEN);
        break;
      case 'message_new':
        $message_text = $data->object->message->text;
        $chat_id = $data->object->message->from_id;
        $formatedMsg = lcfirst($message_text);

        msg_selector($formatedMsg, $chat_id);
        echo('ok');
        break;
      default:
        echo('ok');
        break;
    }
  } catch(Exception $e) {
    echo('ok');
  }
  return $res;
});
