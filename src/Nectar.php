<?php
    namespace Nectar;

    use \Nectar\NectarApi\NectarCurl;

    class NectarApi {

        public static $_assoc_api_key;
        public static $_user_api_key;
        public static $_token;
        public static $_dev_mode        = true;
        public static $_api_prod_url    = 'https://my.nectarmembers.com/api/';
        public static $_api_dev_url     = 'http://nectar.itulstaging.com/api/';

        public function __construct($assoc_api_key, $user_api_key){
            NectarApi::$_assoc_api_key   = $assoc_api_key;
            NectarApi::$_user_api_key    = $user_api_key;
        }

        public function dev_mode($val = true){
            NectarApi::$_dev_mode = $val;
        } 

        public function get_token(){
            
            $res = $this->post('request-token', [
                'assoc_api_key' => NectarApi::$_assoc_api_key,
                'user_api_key'  => NectarApi::$_user_api_key
            ]);

            if($res->status == 'success'){
                NectarApi::$_token = $res->results->token;
            }

            return $res;
        }

        public function get($url){
            $curl = new NectarCurl;
            return $curl->get($url);
        }

        public function post($url, $data = []){
            $curl = new NectarCurl;
            return $curl->post($url, $data);
        }
    }