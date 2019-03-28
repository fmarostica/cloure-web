<?php
    // this code MUST be generated automatically
    require __DIR__."/config.php";
    
    $app_token = "";
    $producto = null;
    if(isset($_GET["app_token"])){
        $app_token = $_GET["app_token"];
    }

    $paypalURL  = 'https://www.paypal.com/cgi-bin/webscr';
    
    //Paypal sandbox
    //$paypalURL  = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    $paypalID   = 'info@grupomarostica.com';
    
    //PayPal test account 
    //$paypalID   = 'info-facilitator@grupomarostica.com';
    
    $successURL = 'https://cloure.com/'.$lang."-".$country_code.'/success?app_token='.$app_token;
    $cancelURL  = 'https://cloure.com/';
    $notifyURL  = 'https://cloure.com/paypalipn.php';

    $itemName = '';
    $itemNumber = '';
    $itemPrice = 0.00;
    $pid = isset($_GET["pid"]) ? $_GET["pid"] : "";

    if($pid){
        $pid = $_GET["pid"];
        $itemName = $pid;
        if($pid!="free"){
            $params=["topic"=>"get_cloure_plans", "pid"=>$pid];
            $productos_res = json_decode($CloureAPI->execute($params));
            if($productos_res!=null){
                if($productos_res->Error==""){
                    $producto = $productos_res->Response->Registros[0];
                } else {
                    echo "Error: ".$productos_res->Error;
                }
            } else {
                echo "Error returned null";
            }

            $itemNumber = $producto->id;
        }
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

    switch ($lang) {
        case 'es':
            $page_title="Suscribase hoy a la plataforma para negocios cloure";
            break;
        
        default:
            $page_title="Subscribe today to the cloure business platform";
            break;
    }

?>

<!DOCTYPE html>
<html lang=<?php echo $lang;?>>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>
        <title><?php echo $page_title; ?></title>
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
        <input id="h-product-type" type="hidden" value="<?php echo $pid; ?>" />
        <?php 
            foreach (glob("gm-plugins/*.php") as $gm_plugin_file) include $gm_plugin_file;
            foreach (glob("inc/header/*.php") as $php_header_file) include $php_header_file;
            foreach (glob("inc/header/*.js") as $js_header_file) echo "<script src='/".$js_header_file."'></script>";
        ?>
        <div id="gm-main-loader" class="gm-full-loader">
            <div class="loader"> 
                <svg class="circular" viewBox="25 25 50 50">
                  <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                </svg> 
            </div>
            <label class="gm-loader-text"><!--Cargando...--></label>
        </div>
        <article>
            <section class="gm-section">
                <div class="container">
                    Estas a un paso de suscribirte a cloure <?php echo $pid; ?>
                    <br/><br/>
                    <?php
                        foreach ($producto->prices as $price_obj) {
                            $priceStr = $price_obj->currency." $ ".$price_obj->amount;
                            $period_id = "1";
                            if($price_obj->billing_type=="M") $period_id="1";
                            if($price_obj->billing_type=="Y") $period_id="2";
                            ?>
                                <form action="<?php echo $paypalURL; ?>" method="post" class="payment-box">
                                    <?php echo "<div style='text-align: center;'>".$priceStr."</div>"; ?>
                                    <input type="hidden" name="business" value="<?php echo $paypalID; ?>">
                                    <input type="hidden" name="cmd" value="_xclick-subscriptions">
                                    <input type="hidden" name="item_name" value="<?php echo $itemName; ?>">
                                    <input type="hidden" name="item_number" value="<?php echo $period_id; ?>">
                                    <input type="hidden" name="currency_code" value="USD">
                                    <input type="hidden" name="a3" id="paypalAmt" value="<?php echo $price_obj->amount; ?>">
                                    <input type="hidden" name="p3" id="paypalValid" value="1">
                                    <input type="hidden" name="t3" value=<?php echo $price_obj->billing_type; ?>>
                                    <input type="hidden" name="custom" value="<?php echo $_GET["app_token"]; ?>">
                                    <input type="hidden" name="cancel_return" value="<?php echo $cancelURL; ?>">
                                    <input type="hidden" name="return" value="<?php echo $successURL; ?>">
                                    <input type="hidden" name="notify_url" value="<?php echo $notifyURL; ?>">
                                    <!--<input class="paypal_button" type="submit" value="Suscribirme">-->
                                    <br/>
                                    <input class="paypal_button" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                </form>
                            <?php
                        }
                    ?>
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
        <script>
            $(".gm-create-account-btn").click(function(){
                $("#gm-main-loader").addClass("active");
                
                $.ajax({
                    url: "/ajax/xhr.php",
                    data: 
                    {
                        topic: "register_account",
                        name: $("#txt-name").val(),
                        last_name: $("#txt-last-name").val(),
                        country_id: $("#txt-country").val(),
                        company_name: $("#txt-company-name").val(),
                        company_type: $("#txt-company-type").val(),
                        email: $("#txt-email").val(),
                        phone: $("#txt-phone").val(),
                        cloure_url: $("#txt-cloure-url").val(),
                        password: $("#txt-password").val(),
                        repeat_password: $("#txt-repeat-password").val()
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(data)
                    {
                        $("#gm-main-loader").removeClass("active");
                        if(data.Error==""){
                            if($("#h-product-type").val()=="free"){
                                window.location.href = "success?app_token="+data.Response.app_token;
                            } else {
                                window.location.href = "payment?app_token="+data.Response.app_token;
                            }
                        }
                        else{
                            alert(data.Error);
                        }
                    }
                });
                
            });
        </script>
    </body>
</html>