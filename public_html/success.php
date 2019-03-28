<?php
    //Include DB configuration file
    require __DIR__."/config.php";

    $params=[
        "topic"=>"get_account_info",
        "app_token"=>$_GET["app_token"]
    ];
    $account_info_res = json_decode($CloureAPI->execute($params));
    if($account_info_res==null) echo "Error returned null";

    if(!empty($_GET['item_number']) && !empty($_GET['tx']) && !empty($_GET['amt']) && $_GET['st'] == 'Completed'){
        //get transaction information from query string
        $item_number = $_GET['item_number'];
        $txn_id = $_GET['tx'];
        $payment_gross = $_GET['amt'];
        $currency_code = $_GET['cc'];
        $payment_status = $_GET['st'];
        $custom = $_GET['cm'];
        
        //Check if subscription data exists with the TXN ID
        $prevPaymentResult = $db->query("SELECT * FROM user_subscriptions WHERE txn_id = '".$txn_id."'");
        
        if($prevPaymentResult->num_rows > 0){
            //get subscription info from database
            $paymentRow = $prevPaymentResult->fetch_assoc();
            
            //prepare subscription html to display
            $phtml  = '<h5 class="success">Thanks for payment, your payment was successful. Payment details are given below.</h5>';
            $phtml .= '<div class="paymentInfo">';
            $phtml .= '<p>Payment Reference Number: <span>MS'.$paymentRow['id'].'</span></p>';
            $phtml .= '<p>Transaction ID: <span>'.$paymentRow['txn_id'].'</span></p>';
            $phtml .= '<p>Paid Amount: <span>'.$paymentRow['payment_gross'].' '.$paymentRow['currency_code'].'</span></p>';
            $phtml .= '<p>Validity: <span>'.$paymentRow['valid_from'].' to '.$paymentRow['valid_to'].'</span></p>';
            $phtml .= '</div>';
        }else{
            $phtml = '<h5 class="error">Your payment was unsuccessful, please try again.</h5>';
        }
    }elseif(!empty($_GET['item_number']) && !empty($_GET['tx']) && !empty($_GET['amt']) && $_GET['st'] != 'Completed'){
        $phtml = '<h5 class="error">Your payment was unsuccessful, please try again.</h5>';
    }

    $page_file_name = pathinfo(basename(__FILE__), PATHINFO_FILENAME);
    $page_config_file = __DIR__.DIRECTORY_SEPARATOR."theme_cfg".DIRECTORY_SEPARATOR.$page_file_name.".json";
    $page_config = null;
    $page_title = "";
    $default_lang = "es";

    if(file_exists($page_config_file)){
        $page_config = json_decode(file_get_contents($page_config_file));
        $page_title = isset($page_config->title) ? $page_config->title : "Sin título";
    }

    $locales = $CloureAPI->getLocales("success");
?>
<!DOCTYPE html>
<html lang=<?php echo $lang;?>>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>
        <title><?php echo $locales["page_title"]; ?></title>
        <?php
            foreach (glob("css/*.css") as $styleFile) echo "<link rel='stylesheet' href='/".$styleFile."' />";
            $css_page_file = __DIR__.DIRECTORY_SEPARATOR."css_pages".DIRECTORY_SEPARATOR.pathinfo(basename(__FILE__), PATHINFO_FILENAME).".css";
            if(file_exists($css_page_file)) echo "<link rel='stylesheet' href='/css_pages/".basename($css_page_file)."' />";
            foreach (glob("inc/head/*.php") as $php_head_file) include $php_head_file;
            foreach (glob("inc/head/*.js") as $js_head_file) echo "<script src='/".$js_head_file."'></script>";
            $plugins = scandir(__DIR__."/plugins");
            if($plugins!==false && count($plugins)>0){
                foreach ($plugins as $plugin) {
                    if(is_dir(__DIR__."/plugins/".$plugin) && $plugin!="." && $plugin!=".."){
                        $dir_arr = explode("-", $plugin);
                        $plugin_index = $dir_arr[0];
                        $plugin_name = $dir_arr[1];
                        $plugin_version = $dir_arr[2];

                        $plugin_folders = scandir(__DIR__."/plugins/".$plugin);
                        foreach ($plugin_folders as $plugin_type) {
                            if($plugin_type=="css") foreach (glob("plugins/".$plugin."/css/*.css") as $plugin_css_file) echo "<link rel='stylesheet' href='/".$plugin_css_file."' />";
                            if($plugin_type=="js") foreach (glob("plugins/".$plugin."/js/*.js") as $plugin_js_file) echo "<script src='/".$plugin_js_file."'></script>";
                        }
                    }
                }
            }
        ?>
        
    </head>
    <body>
        <?php 
            foreach (glob("gm-plugins/*.php") as $gm_plugin_file) include $gm_plugin_file;
            foreach (glob("inc/header/*.php") as $php_header_file) include $php_header_file;
            foreach (glob("inc/header/*.js") as $js_header_file) echo "<script src='/".$js_header_file."'></script>";
        ?>
        <article>
            <section class="container">
                <div class="gm-section">
                    <p>
                        <?php echo $locales["content"]; ?>
                    </p>
                    <div class="">
                        <a href='https://www.microsoft.com/store/apps/9PHMGGHFSGXP?ocid=badge' target="_blank">
                            <img src='<?php echo $locales["ms_logo_resource"]; ?>' alt='Spanish badge' style='height: 100px; padding: 15px;'/>
                        </a>
                        <a href='https://play.google.com/store/apps/details?id=com.grupomarostica.cloure&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1' target="_blank">
                            <img class="get-it-img-google" alt='Get it on Google Play' src='<?php echo $locales["google_play"]; ?>' style='height: 100px'/>
                        </a>
                    </div>
                </div>    
            </section>
        </article>
        <?php 
            foreach (glob("inc/footer/*.php") as $php_footer_file) include $php_footer_file;
            foreach (glob("inc/footer/*.js") as $js_footer_file) echo "<script src='/".$js_footer_file."'></script>";
            foreach (glob("js/*.js") as $js_file) echo "<script src='/".$js_file."'></script>";
            $js_page_file = __DIR__.DIRECTORY_SEPARATOR."js_pages".DIRECTORY_SEPARATOR.pathinfo(basename(__FILE__), PATHINFO_FILENAME).".js";
            if(file_exists($js_page_file)) echo "<script src='/js_pages/".basename($js_page_file)."'></script>";
            foreach (glob("modals/*.php") as $modal_php_file) include $modal_php_file;
        ?>
    </body>
</html>
<!--
<!DOCTYPE html>
<html>
<head>
    <title>PayPal Subscriptions Payment Payment Status</title>
    <meta charset="utf-8">
</head>
<body>
<div class="container">
    <h1>PayPal Subscriptions Payment Status</h1>
    <?php //echo !empty($phtml)?$phtml:''; ?>
</body>
</html>
-->