<?php
    /**
    * CloureSDK client library
    * Copyright 2017-2018 Marostica.
    *
    * @author Franco Marostica <fdmarostica84@gmail.com>
    * @version 1.51
    *
    */

    class CloureSDK
    {
        /* APP VARIABLES WITH DEFAULT VALUES */
        public $Language                = "es";
        public $TestMode                = true;
        protected $AppToken             = "";
        protected $UserToken            = "";
        protected $SessionId            = "";
        protected $LoguedUser           = null;
        protected $APIUrl               = "https://api.cloure.com/";
        protected $APIUrlTest           = "https://api.cloure-test.com/";

        public function __construct() {

            //Get-set default parameters
            $this->SessionId = session_id();
            $this->UserToken = isset($_SESSION["user_token"]) ? $_SESSION["user_token"] : "";
            $this->AppToken = isset($_SESSION["app_token"]) ? $_SESSION["app_token"] : "";
            $this->ClientURL = $_SERVER["SERVER_NAME"];

            $params=["topic"=>"get_logued_user"];
            $this->LoguedUser = json_decode($this->execute($params));
        }

        /**
         * Calls a request on Cloure API server
         * 
         * @param array $params An array with topic and/or function specific parameters requireds
         * @return string in JSON format
         */
        public function execute($params=array()){
            $url = $this->TestMode ? $this->APIUrlTest : $this->APIUrl; 
            $default_params=[
                "app_token"=>$this->AppToken,
                "user_token"=>$this->UserToken,
                "session_id"=>$this->SessionId,
                "client_url"=>$this->ClientURL,
                "language"=>$this->Language,
            ];

            $post = array_merge($default_params, $params);

            $ch = curl_init( $url );
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt( $ch, CURLOPT_HEADER, 0);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0); //disable after 
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0); //disable after
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if($httpCode == 404) {
                die("Error 404: Cannot connect to ".$url);
            } elseif ($httpCode == 500) {
                die("Internal server error");
            }
            else {
                if($response===false) return json_encode(curl_error($ch));
                else return $response;
            }
            
            curl_close($ch);
        }
        
        public function getAccountInfo(){
            return $this->AccountInfo;
        }

        public function getLoguedUser(){
            return $this->LoguedUser;
        }
    }
?>