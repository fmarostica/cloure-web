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
        <title><?= __("faq.title"); ?></title>
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
                <?php
                    echo "<div class='faq-box'>";
                        echo "<h3 class='title'>";
                            echo __("faq.q_free_title");
                        echo "</h3>";
                        echo "<div class='description'>";
                            echo __("faq.q_free_description");
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='faq-box'>";
                        echo "<h3 class='title'>";
                            echo __("faq.q_updates_title");
                        echo "</h3>";
                        echo "<div class='description'>";
                            echo __("faq.q_updates_description");
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='faq-box'>";
                        echo "<h3 class='title'>";
                            echo __("faq.q_integration_title");
                        echo "</h3>";
                        echo "<div class='description'>";
                            echo __("faq.q_integration_description");
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='faq-box'>";
                        echo "<h3 class='title'>";
                            echo __("faq.q_templates_title");
                        echo "</h3>";
                        echo "<div class='description'>";
                            echo __("faq.q_templates_description");
                        echo "</div>";
                    echo "</div>";
                ?>
            </div>
            </section>
        </article>
        <?php include __DIR__."/inc/footer/footer.php"; ?>
    </body>
</html>