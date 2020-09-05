<?php

use Slim\Http\ServerRequest;
use Slim\Http\Response;

require_once './src/selectors/message_selector.php';

$app->post('/bot', function(ServerRequest $req, Response $res) {
  include '.env.php';
  $data = json_decode(file_get_contents('php://input'));
  ['type' => $type, 'object' => $object] = $data;
  try {
    switch ($type) {
      case 'confirmation':
        echo($CONF_TOKEN);
        break;
      case 'message_new':
        $message_text = $object['message']['text'];
        $chat_id = $object['message']['from_id'];
        $formated_msg = lcfirst($message_text);
        msg_selector($formated_msg, $chat_id);
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
