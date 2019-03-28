<?php  require __DIR__."/config.php"; ?>
<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>
        <meta name="description" content="<?= __("page_description"); ?>" />
        <meta property="og:image" content="http://cloure.com/images/logo_1024.png" />
        <title><?= __("web.title"); ?></title>
        <link rel="stylesheet" href="/css/animate.css">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/style.css">
        <script src="/js/jquery.min.js"></script>
        <script src="/js/anime.js"></script>
    </head>
    <body>
        <?php include __DIR__."/inc/header/header.php"; ?>
        <article>
            <?php
                $img_path = "/images/ads/".$lang."/banners_editions.jpg";
                $img_absolute_path = $_SERVER["DOCUMENT_ROOT"].$img_path;
                if(file_exists($img_absolute_path)) echo "<img src=".$img_path." style='width: 100%;'/>";
            ?>
            <section class="gm-section">
                <div class="container">
                    <?php echo "<h1>".__("web.plans")."</h1>"; ?>
                    <div class="row">
                        <?php
                            $params=["topic"=>"get_cloure_plans"];
                            $productos_res = json_decode($CloureAPI->execute($params));
                            //var_dump($productos_res);
                            if($productos_res!=null){
                                if($productos_res->Error==""){
                                    $productos = $productos_res->Response->Registros;
                                    foreach ($productos as $producto) {
                                        echo '<div class="col-md-6" style="margin-top: 10px;">';
                                            echo '<div class="cloure-plan">';
                                                echo '<div class="header">';
                                                    echo '<h3>'.$producto->title.'</h3>';   
                                                echo '</div>';
                                                echo '<div class="body">';
                                                    foreach ($producto->features as $caracteristica) {
                                                        if(isset($caracteristica->title) && $caracteristica->title!=""){
                                                            echo "<div class=\"item\">".$caracteristica->title."</div>";
                                                        }
                                                    }
                                                echo '</div>';
                                                echo '<div class="footer">';
                                                    if(count($producto->prices)==0){
                                                        echo "<div class='price'>".__("web.free")."</div><br/>";
                                                    } else {
                                                        foreach ($producto->prices as $price) {
                                                            echo "<div class='price'>";
                                                                echo $price->currency." $ ".$price->amount;
                                                                echo "<div class='billing-type'>";
                                                                    echo $price->billing_type_str;
                                                                echo "</div>";
                                                                //echo '<br/><a href="subscribe?pid='.$producto->id.'&src=web" class="gm-subscribe-btn">Suscribirse</a>';
                                                            echo "</div>";
                                                        }
                                                        echo "<br/>";
                                                    }
                                                    
                                                    
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo "Error: ".$productos_res->Error;
                                }
                            } else {
                                echo "Error returned null";
                            }
                        ?>
                        
                    </div>
                    <br/>
                    <div class="row">
                        <a href='https://www.microsoft.com/store/apps/9PHMGGHFSGXP?ocid=badge' target="_blank">
                            <img src='<?php echo $locales["ms_logo_resource"]; ?>' alt='Spanish badge' style='height: 100px; padding: 15px;'/>
                        </a>
                        <a href='https://play.google.com/store/apps/details?id=com.grupomarostica.cloure&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1' target="_blank">
                            <img class="get-it-img-google" alt='Disponible en Google Play' src='https://play.google.com/intl/es-419/badges/images/generic/es-419_badge_web_generic.png' style='height: 100px'/>
                        </a>
                    </div>
                </div>
            </section>
        </article>
        <?php include __DIR__."/inc/footer/footer.php"; ?>
    </body>
</html>