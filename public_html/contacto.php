<?php  
    require __DIR__."/config.php"; 
?>
<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>
        <meta name="description" content="<?= __("page_description"); ?>" />
        <meta property="og:image" content="http://cloure.com/images/logo_1024.png" />
        <title><?= __("contact.title"); ?></title>
        <link rel="stylesheet" href="/css/animate.css">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/style.css">
        <script src="/js/jquery.min.js"></script>
        <script src="/js/anime.js"></script>
    </head>
    <body>
        <?php include __DIR__."/inc/header/header.php"; ?>
        <article>
            <section class="gm-section">
                <div class="container">
                    <div id="msgSuccess" class="alert alert-success" style='display: none'>
                        <?= __("contact.message_sent"); ?>
                    </div>

                    <div id="frmContacto" class="row">
                        <div class="col-md-6">
                            <label><?= __("contact.name_prompt"); ?></label>
                            <input data-name="Nombre" class="form-control gm-message-control" type="text" value="">
                        </div>
                        <div class="col-md-6">
                            <label><?= __("contact.last_name_prompt"); ?></label>
                            <input data-name="Apellido" class="form-control gm-message-control" type="text" value="">
                        </div>
                        <div class="col-md-6">
                            <label><?= __("contact.phone_prompt"); ?></label>
                            <input data-name="Telefono" class="form-control gm-message-control" type="text" value="">
                        </div>
                        <div class="col-md-6">
                            <label><?= __("contact.email_prompt"); ?></label>
                            <input data-name="Email" class="form-control gm-message-control" type="text" value="">
                        </div>
                        <div class="col-md-12">
                            <label><?= __("contact.message_prompt"); ?></label>
                            <textarea data-name="Mensaje" class="form-control gm-message-control"></textarea>
                        </div>
                    </div>

                    <br/>
                    <br/>
                    <div style="text-align: center">
                        <button id="btnEnviar" type="button" class="btn btn-success"><?= __("contact.send_prompt"); ?></button>
                    </div>
                </div>
            </section>
        </article>

        <?php include __DIR__."/inc/footer/footer.php"; ?>

        <script>
            $("#btnEnviar").click(function(e){
                $("#btnEnviar").html("Enviando...");
                $("#btnEnviar").prop("disabled", true);

                var campos = [];

                try {
                    $(".gm-message-control").each(function(){
                        if($(this).data("name")=="Nombre" && $(this).val()=="") throw "Debes especificar tu nombre";
                        if($(this).data("name")=="Apellido" && $(this).val()=="") throw "Debes especificar tu apellido";
                        if($(this).data("name")=="Telefono" && $(this).val()=="") throw "Debes especificar un teléfono";
                        if($(this).data("name")=="Email" && $(this).val()=="") throw "Debes especificar una dirección de correo electrónico";
                        if($(this).data("name")=="Mensaje" && $(this).val()=="") throw "Debes especificar un mensaje";

                        var campo_tmp = { nombre: $(this).data("name"), valor: $(this).val() };
                        campos.push(campo_tmp);
                    });

                    $.ajax({
                        url: "/ajax/local-xhr.php",
                        data: 
                        {
                            topic: "enviar_mensaje_publico",
                            campos: JSON.stringify(campos)
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function(data)
                        {
                            if(data.Error==""){
                                $("#frmContacto").fadeOut();
                                $("#msgSuccess").fadeIn();
                                $("#btnEnviar").fadeOut();
                            }
                            else{
                                alert(data.Error);
                            }
                        }
                    });
                } catch (error) {
                    alert(error);
                    $("#btnEnviar").html("Enviar");
                    $("#btnEnviar").prop("disabled", false);
                }
            });
        </script>
    </body>
</html>