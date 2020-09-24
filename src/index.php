<?php

namespace Bot\Routes;

use function Bot\Utils\mb_lcfirst;
use function Bot\Selectors\Message_Selector\msg_selector;
use function Bot\Selectors\Cron_Selector\cron_selector;

if (!isset($_REQUEST)) {
  exit;
}

callback_handleEvent();

function callback_handleEvent() {
  $data = json_decode(file_get_contents('php://input'));
  switch ($data->type) {
    case 'confirmation':
      callback_response($_ENV['CONF_TOKEN']);
      break;
    case 'message_new':
      $message_text = $data->object->message->text;
      $chat_id = $data->object->message->from_id;
      $formatedMsg = mb_lcfirst($message_text);

      msg_selector($formatedMsg, $chat_id);
      callback_okResponse();
      break;
    case 'cron_script':
      $action = $data->object->action;
      cron_selector($action);
      callback_okResponse();
    default:
      callback_okResponse();
      /* callback_response('Unsupported event'); */
      break;
  }
  callback_okResponse();
}

function callback_okResponse() {
  callback_response('ok');
}

function callback_response($data) {
  echo($data);
  exit;
}
