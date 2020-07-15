<?php

require_once 'vendor/autoload.php';



class HttpHandler
{
    private $http_error;

    private function _config(){

        return parse_ini_file('conf.ini');
    }

    public function get_last_http_error(){

        return $this->http_error;
    }

    private function _headers($method,$data = null){

        $time_stamp = time();

        if($method == "POST"){

            unset($data['udf']);
            $request = json_encode($data);
            $token_string = $time_stamp . $method . $request;

        } else if($method == "GET"){

            $token_string = $time_stamp . $method;

        }

        return [
            'Authorization' => 'Bearer '. $this->_config()['ACCESS_KEY'] . ":" .$this->_config()['SECRET_KEY'],
            'Content-Type' => 'application/json'
        ];
    }

    function _do_post($route,$data){

        $client = new \GuzzleHttp\Client(['http_errors' => false]);

        try {
            $response = $client->request(
                'post',
                $this->_config()['LAYER_HOST'].$route,
                [
                    'json' => $data,
                    'headers' => $this->_headers('POST', $data)
                ]
            );

            $http_status_code = $response->getStatusCode();

            if($http_status_code >= 200 &&  $http_status_code <= 299 ){

                return json_decode($response->getBody()->getContents());

            }

            $this->http_error = json_decode($response->getBody()->getContents());

            throw  new Exception("an error occurred when contacting layer");

        } catch (Exception $exception){

            throw  $exception;

        }
    }

    function _do_get($route){

        $client = new \GuzzleHttp\Client(['http_errors' => false]);


        $response = $client->request(
            'get',
            $this->_config()['LAYER_HOST'].$route,
            [
                'headers' => $this->_headers('GET')
            ]
        );

        $http_status_code = $response->getStatusCode();

        if($http_status_code >= 200 &&  $http_status_code <= 299 ){

            return json_decode($response->getBody()->getContents());

        }

        $this->http_error = json_decode($response->getBody()->getContents());

        throw  new Exception("an error occurred when contacting layer");
    }
}
