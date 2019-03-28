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
            <section id="main-section">
                <div class="ml4">
                    <span class="letters letters-1"><?= __("web.ready"); ?></span>
                    <span class="letters letters-2"><?= __("web.next_generation"); ?></span>
                    <span class="letters letters-3"><?= __("web.business_platform"); ?></span>
                    <span class="letters letters-4"><img src="/images/logo.svg" style="width: 80%; max-width: 800px;"/></span>
                    <span class="sub-letters letters-5"><?= __("web.get_it"); ?></span>
                <div>
            </section>
            <section id="features-section" class="gm-section">
                <div class="container">
                    <h2><?= __("web.features"); ?></h2>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <img src="/images/modular.png">
                                <br/><strong><?= __("web.modular_title") ?></strong>
                                <br/><?= __("web.modular_description") ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <img src="/images/world.png">
                                <br/><strong><?= __("web.available_anywhere_title") ?></strong>
                                <br/><?= __("web.available_anywhere_description") ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <img src="/images/customize.png">
                                <br/><strong><?= __("web.customizable_title") ?></strong>
                                <br/><?= __("web.customizable_description") ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <img src="/images/www.png">
                                <br/><strong><?= __("web.own_domain_title") ?></strong>
                                <br/><?= __("web.own_domain_description") ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <img src="/images/cart.png">
                                <br/><strong><?= __("web.shopping_cart_title") ?></strong>
                                <br/><?= __("web.shopping_cart_description") ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <img src="/images/social-networks.png">
                                <br/><strong><?= __("web.social_network_title") ?></strong>
                                <br/><?= __("web.social_network_description") ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <img src="/images/payments.png">
                                <br/><strong><?= __("web.receive_orders_title") ?></strong>
                                <br/><?= __("web.receive_orders_description") ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="own-it-section">
                <div class="row">
                    <div class="col-md-4">
                        <div class="section-description">
                            <?= __("web.own_it_department"); ?>
                        </div>
                    </div>
                    <div class="col-md-8">

                    </div>
                </div>
            </section>
        </article>
        <?php include __DIR__."/inc/footer/footer.php"; ?>
        <script>
            var ml4 = {};
            ml4.opacityIn = [0,1];
            ml4.scaleIn = [0.2, 1];
            ml4.scaleOut = 3;
            ml4.durationIn = 800;
            ml4.durationOut = 600;
            ml4.delay = 500;

            anime.timeline({loop: false})
            .add({
                targets: '.ml4 .letters-1',
                opacity: ml4.opacityIn,
                scale: ml4.scaleIn,
                duration: ml4.durationIn
            }).add({
                targets: '.ml4 .letters-1',
                opacity: 0,
                scale: ml4.scaleOut,
                duration: ml4.durationOut,
                easing: "easeInExpo",
                delay: ml4.delay
            }).add({
                targets: '.ml4 .letters-2',
                opacity: ml4.opacityIn,
                scale: ml4.scaleIn,
                duration: ml4.durationIn
            }).add({
                targets: '.ml4 .letters-2',
                opacity: 0,
                scale: ml4.scaleOut,
                duration: ml4.durationOut,
                easing: "easeInExpo",
                delay: ml4.delay
            }).add({
                targets: '.ml4 .letters-3',
                opacity: ml4.opacityIn,
                scale: ml4.scaleIn,
                duration: ml4.durationIn
            }).add({
                targets: '.ml4 .letters-3',
                opacity: 0,
                scale: ml4.scaleOut,
                duration: ml4.durationOut,
                easing: "easeInExpo",
                delay: ml4.delay
            }).add({
                targets: '.ml4 .letters-4',
                opacity: ml4.opacityIn,
                scale: ml4.scaleIn,
                duration: ml4.durationIn
            }).add({
                targets: '.ml4 .letters-5',
                opacity: ml4.opacityIn,
                scale: ml4.scaleIn,
                duration: ml4.durationIn
            })
        </script>
    </body>
</html>