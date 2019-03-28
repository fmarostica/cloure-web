<?php
    /**
    * GrupoMarostica SDK
    * Copyright 2017-2018 Marostica.
    *
    * @author Franco Marostica
    * @version 1.51
    *
    */
    namespace Marostica;

    class MarosticaSDK
    {
        /* APP VARIABLES WITH DEFAULT VALUES */
        protected $UserToken            = "";
        protected $SessionId            = "";
        protected $LoguedUser           = null;
        protected $Protocol             = "https";
        protected $APIFolder            = "api";
        protected $Host                 = "grupomarostica.com";
        protected $APIVersion           = 1;
        protected $APIUrl               = "";
        protected $Language             = "en";
        protected $Country              = "us";

        public function __construct($lang="en", $country="us"){
            $this->SessionId = session_id();
            $this->UserToken = isset($_SESSION["user_token"]) ? $_SESSION["user_token"] : "";
            $this->Language=$lang;
            $this->Country=$country;
            
            $params=[
                "topic"=>"get_logued_user"
            ];
            $this->LoguedUser = json_decode($this->execute($params));
        }

        public function execute($params=array()){
            $url = $this->Protocol."://".$this->Host."/".$this->APIFolder."/v".$this->APIVersion."/";
            $default_params=[
                "user_token"=>$this->UserToken,
                "session_id"=>$this->SessionId,
                "language"=>$this->Language,
                "country"=>$this->Country
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
                if($response===false){
                    //die(json_encode(curl_error($ch)));
                    return json_encode(curl_error($ch));
                }
                else {
                    return $response;
                }
            }
            
            curl_close($ch);
        }
        
        public function setProtocol($protocol){
            $this->Protocol = $protocol;
        }
        public function setHost($host){
            $this->Host = $host;
        }
        public function setLang($lang){
            $this->Language = $lang;
        }
        public function getLang(){
            return $this->Language;
        }

        public function getAPIVersion(){
            return $this->APIVersion;
        }

        public function getLoguedUser(){
            return $this->LoguedUser;
        }

        public function getApiURL(){
            return $this->APIUrl;
        }
    }
?>