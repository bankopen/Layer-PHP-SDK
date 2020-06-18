<?php

require_once 'api_routes.php';
require_once 'HttpHandler.php';

$conf = parse_ini_file('conf.ini');


$http = new HttpHandler();

try {


    $payment_token = $http->_do_post(
        $payment_token['create']['route'],
        [
            'amount' => '66.34',
            'currency'  => 'INR',
            'mtx' => date('ymdhis'), // merchant reference number
            'email_id' => 'test@test.com',
            'contact_number' => '9876543210'
        ]
    );

    ?>

        <html lang="en">

            <head>
                <script type="application/javascript" src="<?php echo $conf['LAYER_JS']; ?>"></script>
            </head>
            <body>
                <button onclick ="trigger_layer()" id="pay">Pay</button>
            </body>
            <script>

                function trigger_layer() {

                    Layer.checkout(
                        {
                            token: "<?php echo $payment_token->id; ?>",
                            accesskey: "<?php echo $conf['ACCESS_KEY']; ?>"
                        },
                        function (response) {

                            window.location = "<?php echo $conf["APP_HOST"]; ?>" + "/response.php?payment_token_id=" + "<?php echo $payment_token->id; ?>"
                        },
                        function (err) {
                            alert(err.message);
                        }
                    );
                }

            </script>
        </html>

    <?php

} catch (Exception $exception){

    echo "<pre>"; print_r($http->get_last_http_error());
    echo "<pre>"; print_r($exception->getMessage());
}

