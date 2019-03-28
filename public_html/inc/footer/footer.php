<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
            </div>
        </div>
        <?php
            $params=[
                "module"=>"countries",
                "topic"=>"get_cloure_list",
                "available"=>"1"
            ];
            $countries_res = json_decode($CloureAPI->execute($params));
            if($countries_res!=null){
                $countries = $countries_res->Response;
                foreach ($countries->Registros as $country) {
                    if($country->Id=="en"){
                        echo "<a class='lang-link' href='/'>".$country->Nombre."</a>";
                    } else {
                        echo "<a class='lang-link' href='/".$country->IdiomaId."-".$country->Dominio."/".$active_page."'>".$country->Nombre."</a>";
                    }
                }
            } else {
                echo "Error returned null";
            }
        ?>
    </div>
</footer>