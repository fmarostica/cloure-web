<?php
    // this code MUST be generated automatically
    require __DIR__."/config.php";
    if(!isset($_GET["lang"])){
        header("location: /".$lang."/terms");
    } else {
        if($_GET["lang"]!=$lang){
            header("location: /".$lang."/terms");
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

?>

<!DOCTYPE html>
<html lang=<?php echo $lang;?>>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>
        <title>Cloure is available for presale</title>
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
            <section class="gm-section">
                <div class="container">
                    <div>
                        <?php
                            include $MarosticaAPI->get_locales("terms.php");
                        ?>
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