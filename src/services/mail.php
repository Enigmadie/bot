<?php

namespace Bot\Services\mail;

use function Bot\Utils\isAssoc;

function get_mail_data($track) {
  $mail_login = $_ENV['MAIL_LOGIN'];
  $mail_password = $_ENV['MAIL_PASSWORD'];

  $request = "<?xml version='1.0' encoding='UTF-8'?>
                  <soap:Envelope xmlns:soap='http://www.w3.org/2003/05/soap-envelope' xmlns:oper='http://russianpost.org/operationhistory' xmlns:data='http://russianpost.org/operationhistory/data' xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/'>
                  <soap:Header/>
                  <soap:Body>
                     <oper:getOperationHistory>
                        <data:OperationHistoryRequest>
                           <data:Barcode>{$track}</data:Barcode>
                           <data:MessageType>0</data:MessageType>
                           <data:Language>RUS</data:Language>
                        </data:OperationHistoryRequest>
                        <data:AuthorizationHeader soapenv:mustUnderstand='1'>
                           <data:login>{$mail_login}</data:login>
                           <data:password>{$mail_password}</data:password>
                        </data:AuthorizationHeader>
                     </oper:getOperationHistory>
                  </soap:Body>
               </soap:Envelope>";

  $client = new \SoapClient("https://tracking.russianpost.ru/rtm34?wsdl",  array('trace' => 1, 'soap_version' => SOAP_1_2));

  $response = $client->__doRequest($request,"https://tracking.russianpost.ru/rtm34", "getOperationHistory", SOAP_1_2);

  $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
  $xml = simplexml_load_string($response);
  $body = $xml->xpath('//SBody')[0];
  $array = json_decode(json_encode((array)$body), TRUE);

  $data = parse_mail_data($array);
  return $data;
}

function parse_mail_data($data) {
  $is_emptyData = empty($data['ns7getOperationHistoryResponse']['ns3OperationHistoryData']);
  $is_errorData = array_key_exists('SFault', $data);

  if (!$is_emptyData && !$is_errorData) {
    $historyData = $data['ns7getOperationHistoryResponse']['ns3OperationHistoryData']['ns3historyRecord'];

    $lastAction = !isAssoc($historyData)
      ? $historyData[count($historyData) - 1]
      : $historyData;

    [
      'ns3OperationParameters' => $operation,
      'ns3AddressParameters' => $address,
      'ns3ItemParameters' => $item,
    ] = $lastAction;

    $status = $operation['ns3OperType']['ns3Name'];
    $date = new \Datetime($operation['ns3OperDate']);

    $formatedDate = $date->format('d-m-Y H:i');

    $index = $item['ns3Barcode'];
    $location = $address['ns3OperationAddress']['ns3Description'];

    return [
      'status' => $status,
      'message' => "&#9993; ${index}\n\n&#9889;{$status}\n&#128197;{$formatedDate}\n&#9654;${location}",
    ];
  }
  return [
    'status' => null,
    'message' => 'Почта не найдена &#128373;',
  ];
}

