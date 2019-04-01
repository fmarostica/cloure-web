<?php
    /**
    * GruupoMarostica Config File
	* Copyright 2017-2018 Marostica.
	*
	* @author Franco MarosticaAPI
    *
    */
    session_start();
    
    chdir(dirname(__DIR__));

    define("CORE_PATH", "Core/");
    define("RES_PATH", "resources/");
    define("APP_PATH", "app/");
    define("LIB_PATH", "libs/");

    $lang = "en";
    $country_code = "us";
    $country_id = 0;
    $request_uri = $_SERVER['REQUEST_URI'];
    $active_page = "";

    require_once LIB_PATH."geoip.php";

    if(!isset($_GET["country"])){
        $gi = geoip_open(LIB_PATH."GeoIP.dat", GEOIP_STANDARD);
        $ip = $_SERVER['REMOTE_ADDR'];
        $country_code = strtolower(geoip_country_code_by_addr($gi, $ip));
        geoip_close($gi);
    } else {
        $country_code = $_GET["country"];
    }
    
    if(!isset($_GET["lang"])){
        $lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : "en";
    } else {
        $lang = $_GET["lang"];
    }

    require __DIR__."/marostica_sdk.php";
    require __DIR__."/cloure_sdk.php";
   
    $_SESSION["lang"] = $lang;
    $_SESSION["country"] = $country_code;

    $CloureAPI = new CloureSDK();
    $CloureAPI->Language = $lang;

    $params=[
        "module"=>"countries",
        "topic"=>"get_by_code",
        "code"=>$country_code
    ];
    $country_res = json_decode($CloureAPI->execute($params));
    if($country_res!=null){
        $country = $country_res->Response;
        if($country!=null){
            $country_name = $country->name_en;
            $country_id = $country->Id;
        }
    } else {
        echo "Error returned null";
    }

    if(!isset($_GET["lang"])){
        if($lang!="en"){
            if($country_code!=""){
                header("location: /".$lang."-".$country_code.$request_uri);
            } else {
                header("location: /".$lang.$request_uri);
            }
        }
    } 
    
    /**
     * Get the resource language for specified key
     *
     * @param string $resourceString
     * @return string
     */
    function __(string $resourceString):string {
        global $lang;
        $resourceArr = explode('.', $resourceString);
        $resourceFile = $resourceArr[0].".php";
        $resourceKey = $resourceArr[1];
        $path = RES_PATH."lang/".$lang."/".$resourceFile;

        //define default resource val = {file.arraykey}
        $resourceVal = "{".$resourceString."}";

        if(file_exists($path)){
            $resource = include($path);

            if(array_key_exists($resourceKey, $resource)) $resourceVal = $resource[$resourceKey];
        }
        

        return $resourceVal;
    }
?>