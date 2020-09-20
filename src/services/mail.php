<?php
$request = '<?xml version="1.0" encoding="UTF-8"?>
                <soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:oper="http://russianpost.org/operationhistory" xmlns:data="http://russianpost.org/operationhistory/data" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                <soap:Header/>
                <soap:Body>
                   <oper:getOperationHistory>
                      <data:OperationHistoryRequest>
                         <data:Barcode>0</data:Barcode>
                         <data:MessageType>0</data:MessageType>
                         <data:Language>RUS</data:Language>
                      </data:OperationHistoryRequest>
                      <data:AuthorizationHeader soapenv:mustUnderstand="1">
                         <data:login></data:login>
                         <data:password></data:password>
                      </data:AuthorizationHeader>
                   </oper:getOperationHistory>
                </soap:Body>
             </soap:Envelope>';

$client = new \SoapClient("https://tracking.russianpost.ru/rtm34?wsdl",  array('trace' => 1, 'soap_version' => SOAP_1_2));

echo '<textarea>'.$client->__doRequest($request, "https://tracking.russianpost.ru/rtm34", "getOperationHistory", SOAP_1_2).'</textarea>';
?>

