<?php

namespace Bot\Routes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function Bot\Utils\mb_lcfirst;
use function Bot\Selectors\Message_Selector\msg_selector;

/* use Slim\Http\ServerRequest as Request; */
/* use Slim\Http\Response as Response; */

/* require_once './src/selectors/message_selector.php'; */

$app->get('/', function(Request $req, Response $res) {
  $res->getBody()->write('Hello');
  return $res;
});

$app->post('/', function($req, $res) {
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
        $formatedMsg = mb_lcfirst($message_text);

        msg_selector($formatedMsg, $chat_id);
        echo('ok');
        break;
      default:
        echo('ok');
        break;
    }
  } catch(\Exception $e) {
    echo('ok');
  }
  return $res;
});
