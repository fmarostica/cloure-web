<?php  require __DIR__."/config.php"; ?>
<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>
        <meta name="description" content="<?= __("page_description"); ?>" />
        <meta property="og:image" content="http://cloure.com/images/logo_1024.png" />
        <title><?= __("subscribe.title"); ?></title>
        <link rel="stylesheet" href="/css/animate.css">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/style.css">
        <script src="/js/jquery.min.js"></script>
        <script src="/js/anime.js"></script>
    </head>
    <body>
        <?php include __DIR__."/inc/header/header.php"; ?>
        <article>
            <div id="subscribe-header" class="page-header">
                <h1><?= __("subscribe.title_h1"); ?></h1>
            </div>
            <section class="gm-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <label><?= __("subscribe.name_prompt"); ?></label>
                            <input id="txt-name" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label><?= __("subscribe.last_name_prompt"); ?></label>
                            <input id="txt-last-name" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label><?= __("subscribe.password_prompt"); ?></label>
                            <input id="txt-password" type="password" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label><?= __("subscribe.repeat_password_prompt"); ?></label>
                            <input id="txt-repeat-password" type="password" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label><?= __("subscribe.country_prompt"); ?></label>
                            <?php
                                $params=[
                                    "module"=>"countries", 
                                    "topic"=>"get_list",
                                    "available"=>1
                                ];
                                $paises_res = json_decode($CloureAPI->execute($params));
                                if($paises_res!=null){
                                    if($paises_res->Error==""){
                                        $paises = $paises_res->Response->Registros;
                                        echo "<select id='txt-country' class='form-control'>";
                                        foreach ($paises as $pais) {
                                            echo "<option value='".$pais->Id."' ".($pais->Id==$country_id ? "selected" : "").">".$pais->Nombre."</option>";
                                        }
                                        echo "</select>";
                                    } else {
                                        echo "Error: ".$paises_res->Error;
                                    }
                                } else {
                                    echo "Error returned null";
                                }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <label><?= __("subscribe.business_name_prompt"); ?></label>
                            <input id="txt-company-name" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label><?= __("subscribe.business_type_prompt"); ?></label>
                            <?php
                                $params=[
                                    "module"=>"business_types",
                                    "topic"=>"get_list",
                                ];
                                $actividades_res = json_decode($CloureAPI->execute($params));
                                if($actividades_res!=null){
                                    if($actividades_res->Error==""){
                                        $actividades = $actividades_res->Response->Registros;
                                        echo "<select id='txt-company-type' class='form-control'>";
                                        foreach ($actividades as $actividad) {
                                            echo "<option value='".$actividad->id."' ".($actividad->id=='generic' ? "selected": "").">".$actividad->title."</option>";
                                        }
                                        echo "</select>";
                                    } else {
                                        echo "Error: ".$actividades_res->Error;
                                    }
                                } else {
                                    echo "Error returned null";
                                }
                            ?>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <label><?= __("subscribe.phone_prompt"); ?></label>
                            <input id='txt-phone' class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label><?= __("subscribe.email_prompt"); ?></label>
                            <input id='txt-email' class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label><?= __("subscribe.cloure_url_prompt"); ?></label>
                            <div class="input-group mb-3">
                                <input id='txt-cloure-url' type="text" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">.cloure.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/><br/>
                    <div style="text-align:center;">
                        <button type="button" class="btn btn-primary gm-create-account-btn"><?= __("subscribe.next_btn"); ?></button>
                    </div>
                </div>
            </section>
        </article>
        <?php include __DIR__."/inc/footer/footer.php"; ?>
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
                            if($("#h-product-type").val()=="free" || $("#h-product-type").val()==""){
                                window.location.href = "success?app_token="+data.Response.app_token;
                            } else {
                                window.location.href = "payment?app_token="+data.Response.app_token+"&pid="+$("#h-product-type").val();
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