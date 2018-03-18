<?php
	namespace Nectar\NectarApi;
    
    use \Nectar\NectarApi;

    class NectarCurl extends NectarApi{

        public $_ch;

        public function __construct(){
            $this->_ch = curl_init();
            curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        }

        private function url($url){
            
            if(!is_null(NectarApi::$_token)) url_setopt($this->_ch, CURLOPT_HTTPHEADER, ['X-Nectar-Token: '.NectarApi::$_token]);
            
            $api_url = NectarApi::$_dev_mode ? NectarApi::$_api_dev_url : NectarApi::$_api_prod_url;

            curl_setopt($this->_ch, CURLOPT_URL, $api_url.$url);
            return $this;
        }

        public function get($url){
            $this->url($url);
            curl_setopt($this->_ch, CURLOPT_POST, 0);
            return $this->run();
        }

        public function post($url, $data = []){
            $this->url($url);
            curl_setopt($this->_ch, CURLOPT_POST, 1);
            if(!empty($data)){
                curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $data);
            }
            return $this->run();
        }

        public function run(){
            
            $result = curl_exec($this->_ch);

            curl_close ($this->_ch);

            $parsed = json_decode($result);

            if(is_null($parsed)){

                $parsed = (object)[
                    'status'        => 'error',
                    'errors'        => [
                        'general'   => ["An unknown error occured", $result],
                        'fields'    => [],
                    ],
                    'results'       => []
                ];
            }

            return $parsed;
        }
    }