<header class="gm-object gm-header" data-object-type="Header">
    <div class="gm-header-basic">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <a href="<?php echo "/".$lang."-".$country_code."/"; ?>" class="gm-header-logo">
                        <img src="/images/logo.png" alt="cloure">
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="header-buttons">
                        <a href="<?php echo "https://panel.".$_SERVER["SERVER_NAME"]; ?>" class="btn"><?= __("web.login_btn");?></a>
                        <!--<a href="subscribe" class="btn btn-success"><?= __("web.register_btn");?></a>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gm-menu-container no-mobile">
        <div class="container">
            <nav class='gm-menu'>
                <ul>
                    <li class='gm-menu-item-container'>
                        <a class='gm-menu-item' href='/'><?= __("menu.home") ?></a>
                    </li>
                    <li class='gm-menu-item-container'>
                        <a class='gm-menu-item' href='/<?= $lang ?>/plans'><?= __("menu.plans") ?></a>
                    </li>
                    <!--
                    <li class='gm-menu-item-container'>
                        <a class='gm-menu-item' href='/<?= $lang ?>/support'><?= __("menu.support") ?></a>
                    </li>
                    -->
                    <li class='gm-menu-item-container'>
                        <a class='gm-menu-item' href='/<?= $lang ?>/faq'><?= __("menu.faq") ?></a>
                    </li>
                    <li class='gm-menu-item-container'>
                        <a class='gm-menu-item' href='/<?= $lang ?>/contact'><?= __("menu.contact") ?></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<div class="gm-hero"></div>