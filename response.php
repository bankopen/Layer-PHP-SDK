<?php

require_once 'HttpHandler.php';
require_once 'api_routes.php';

$payment_token_id = $_REQUEST['payment_token_id'];

$http = new HttpHandler();

try {


    $payment_token_data = $http->_do_get(
        sprintf($payment_token['status']['route'],$payment_token_id)
    );

    echo "<pre>"; print_r($payment_token_data);

} catch (Exception $exception){

    echo "<pre>"; print_r($http->get_last_http_error());
    echo "<pre>"; print_r($exception->getMessage());

}

