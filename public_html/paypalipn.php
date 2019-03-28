<?php

    /*
    * Read POST data
    * reading posted data directly from $_POST causes serialization
    * issues with array data in POST.
    * Reading raw POST data from input stream instead.
    */  

    $paypalURL = "https://ipnpb.paypal.com/cgi-bin/webscr";
    //$paypalURL = "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr";

    $raw_post_data = file_get_contents('php://input');

    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
        $keyval = explode ('=', $keyval);
        if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]);
    }

    $encoded_data = json_encode($myPost);
    file_put_contents('paypal-raw.txt', print_r($encoded_data."\n", true));

    // Read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    if(function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
        if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
            $value = urlencode(stripslashes($value));
        } else {
            $value = urlencode($value);
        }
        $req .= "&$key=$value";
    }

    /*
    * Post IPN data back to PayPal to validate the IPN data is genuine
    * Without this step anyone can fake IPN data
    */
    $ch = curl_init($paypalURL);
    if ($ch == FALSE) {
        return FALSE;
    }
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

    // Set TCP timeout to 30 seconds
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: PHP-IPN-Verification-Script','Connection: Close'));
    curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . "/cacert.pem");

    if (!($res = curl_exec($ch)) ) {
        file_put_contents('paypal-error.txt', "Got".curl_error($ch)." when processing IPN data\n", FILE_APPEND);
        curl_close($ch);
        exit;
    }

    /*
    * Inspect IPN validation result and act accordingly
    * Split response headers and payload, a better way for strcmp
    */ 
    $tokens = explode("\r\n\r\n", trim($res));
    $res = trim(end($tokens));

    if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) {
        
        file_put_contents('paypal-res.txt', print_r($encoded_data, true)."\n", FILE_APPEND);

        $unitPrice = 25;
        $gm_user_id = 0;
        $account_type = "";
        $fecha = date("Y-m-d");
        $vencimiento = $fecha;
        $nuevo_vencimiento = $fecha;

        //Payment data
        $subscr_id = $_POST['subscr_id'];
        $payer_email = $_POST['payer_email'];
        $item_number = isset($_POST['item_number']) ? $_POST["item_number"] : "";
        $item_name = isset($_POST['item_name']) ? $_POST["item_name"] : "";
        $txn_id = isset($_POST['txn_id']) ? $_POST["txn_id"] : "";
        $payment_gross = isset($_POST['mc_gross']) ? $_POST["mc_gross"] : "";
        $currency_code = $_POST['mc_currency'];
        $payment_status = isset($_POST['payment_status']) ? $_POST["payment_status"] : "";
        $custom = isset($_POST['custom']) ? $_POST["custom"] : "";
        $period = isset($_POST['period3']) ? $_POST["period3"] : "";
        
        $consulta = "SELECT * FROM cloure_accounts WHERE app_token=?";
        $sen=$con->prepare($consulta) or trigger_error($con->error);
        $sen->bind_param("s", $custom);
        if(!$sen->execute()) throw new \Exception($con->error, 1);
        $res=$sen->get_result();
        if($res->num_rows>0){
            while ($reader=$res->fetch_assoc()){
                $gm_user_id=$reader["gm_user_id"];
                $account_type=$reader["account_type"];
                $vencimiento=$reader["vencimiento"];
            }
        }

        $descripcion = "";

        $consulta = "SELECT * FROM cloure_plans WHERE id=?";
        $sen=$con->prepare($consulta) or trigger_error($con->error);
        $sen->bind_param("s", $item_name);
        if(!$sen->execute()) throw new \Exception($con->error, 1);
        $res=$sen->get_result();
        if($res->num_rows>0){
            while ($reader=$res->fetch_assoc()){
                $descripcion = "Pago de ".$reader["title"];
            }
        }

        if(!empty($txn_id)){
            //Check if subscription data exists with the same TXN ID.
            $consulta = "SELECT id FROM movimientos WHERE plataforma_id=1 and plataforma_txn_id = ?";
            $sen=$con->prepare($consulta) or trigger_error($con->error);
            $sen->bind_param("s", $txn_id);
            if(!$sen->execute()) throw new \Exception($con->error, 1);
            $res=$sen->get_result();
            if($res->num_rows>0){
                exit();
            } else {
                //Insert tansaction data into the database
                $fecha = date("Y-m-d H:i:s");
                
                $tipo_movimiento = "ingreso";
                $tipo_operacion = "pago";
                $item_id = 0;
                $canal = "";
                $forma_de_pago = "";
                $forma_de_pago_entidad_id = 0;
                $sucursal = 1;
                $tipo_comprobante_id = 0;
                $comprobante_id = 0;
                $comprobante_sucursal = 0;
                $comprobante_numero = 0;
                $importe = $payment_gross;
                $usuario_id = 0;
                $estado = $payment_status;
                $creador_id = 1;
                $plataforma_id = 1;
                $adicionales = "";

                if($item_number!="1" && $item_number!="2" && $item_number!="3" && $item_number!="4"){
                    $consulta = "UPDATE cloure_accounts set balance=? where app_token=?";
                    $sen=$con->prepare($consulta) or trigger_error($con->error);
                    $sen->bind_param("ds", $importe, $custom);
                    if(!$sen->execute()) throw new \Exception($con->error, 1);
                } else {
                    if($account_type=="free" || $account_type=="test_free"){
                        $nuevo_vencimiento = date("Y-m-d", strtotime("+1 month"));
                        if($item_number=="1") $nuevo_vencimiento = date("Y-m-d", strtotime("+1 month"));
                        if($item_number=="2") $nuevo_vencimiento = date("Y-m-d", strtotime("+1 year"));
                    } else {
                        $nuevo_vencimiento = date("Y-m-d", strtotime("+1 month", strtotime($vencimiento)));
                        if($item_number=="1") $nuevo_vencimiento = date("Y-m-d", strtotime("+1 month", strtotime($vencimiento)));
                        if($item_number=="2") $nuevo_vencimiento = date("Y-m-d", strtotime("+1 year", strtotime($vencimiento)));
                    }
                    $consulta = "UPDATE cloure_accounts set account_type=?, vencimiento=? where app_token=?";
                    $sen=$con->prepare($consulta) or trigger_error($con->error);
                    $sen->bind_param("sss", $item_name, $nuevo_vencimiento, $custom);
                    if(!$sen->execute()) throw new \Exception($con->error, 1);
                }

                $descripcion .= " con PayPal";

                $consulta = "INSERT INTO movimientos (
                    fecha,
                    descripcion,
                    tipo_movimiento,
                    tipo_operacion,
                    item_id,
                    canal,
                    forma_de_pago,
                    forma_de_pago_entidad_id,
                    sucursal,
                    tipo_comprobante_id,
                    comprobante_id,
                    comprobante_sucursal,
                    comprobante_numero,
                    importe,
                    usuario_id,
                    estado,
                    creador_id,
                    plataforma_id,
                    plataforma_txn_id,
                    adicionales
                ) VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )";
                $sen=$con->prepare($consulta) or trigger_error($con->error);
                $sen->bind_param("ssssissiiiiiidisiiss", 
                    $fecha,
                    $descripcion,
                    $tipo_movimiento,
                    $tipo_operacion,
                    $item_id,
                    $canal,
                    $forma_de_pago,
                    $forma_de_pago_entidad_id,
                    $sucursal,
                    $tipo_comprobante_id,
                    $comprobante_id,
                    $comprobante_sucursal,
                    $comprobante_numero,
                    $importe,
                    $usuario_id,
                    $estado,
                    $creador_id,
                    $plataforma_id,
                    $txn_id,
                    $adicionales
                );
                if(!$sen->execute()) throw new \Exception($con->error, 1);
            }
        }
    }

    die;

?>