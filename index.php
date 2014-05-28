<?php
    require_once 'vendor/Slim/Slim.php';
    
    \Slim\Slim::registerAutoloader();
    
    //Instância do Slim com o Debug desligado para que a mensagem personalizada apareça
    $app = new \Slim\Slim(array(
        'debug'  => false
    ));
    
    //Método de retorno do JSON com erro, caso algum ocorra, um teste é chamar uma classe que não existe
    $app->error(function(Exception $e) use ($app){
        $erroObj = new stdClass();
        $erroObj->message = $e->getMessage();
        $erroObj->file = $e->getFile();
        $erroObj->line = $e->getLine();

        echo "{'erro':".json_encode($erroObj)."}";
    });
    
    // Landing com idioma padrão
    $app->get('/', function() {
        die('<script type="text/javascript">location.href = document.URL + navigator.language.substring(0,2) + "?v=2";</script>');
    });
    
    $app->get('/:function(/:param+)', function($function, $param) {
        $latitude = $param[0];
        $longitude = $param[1];
        $mtype = $param[2];

        //print_r($param);
        //echo "<br>Latitude: $latitude, Longitude: $longitude, MType: $mtype";
        
        require_once("$function.php");
    });

    $app->run();

?>