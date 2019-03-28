<?php
    $result = array();
    $response = "";
    $error = "";

    $topic = isset($_POST["topic"]) ? $_POST["topic"] : "";

    if($topic=="enviar_mensaje_publico"){
        $campos = isset($_POST["campos"]) ? json_decode($_POST["campos"]) : array();
        $mensaje = "";
        $from = "";

        foreach ($campos as $campo) {
            $mensaje.=$campo->nombre.": ".$campo->valor."\r\n";

            if($campo->nombre=="E-mail") $from = $campo->valor;
            if(mb_strtolower($campo->nombre=="nombre")) $usuario_nombre = $campo->valor;
            if(isset($campo->requerido)){
                if($campo->requerido="true" && $campo->valor=="") throw new \Exception("El campo ".$campo->Nombre." es requerido.", 1);
            }
        }

        $header  = "From: Cloure <info@cloure.com>\r\n";
        $header .= "Reply-To: ".$from. "\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail("Cloure <info@cloure.com>", "Consulta desde el sitio web", $mensaje, $header);
    }
    if($topic=="register_account"){
        $name = isset($_POST["name"]) ? $_POST["name"] : "";
        $last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
        $country_id = isset($_POST["country_id"]) ? $_POST["country_id"] : "";
        $company_name = isset($_POST["company_name"]) ? $_POST["company_name"] : "";
        $company_type = isset($_POST["company_type"]) ? $_POST["company_type"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
        $cloure_url = isset($_POST["cloure_url"]) ? $_POST["cloure_url"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";
    }

    $result["Response"] = $response;
    $result["Error"] = $error;

    echo json_encode($result);
?>