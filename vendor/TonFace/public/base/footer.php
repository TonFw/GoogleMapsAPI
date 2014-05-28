    </body>
</html>

<?php 
    $msg = $_POST["msg_mural"]; 
    $link_msg = $_POST["link_msg_mural"]; 
    if(isset($msg) && isset($link_msg))$ton_fb->setMsgMural($msg, $link_msg);
    else echo "<script> alert('Um dos campos não foi preenchido!'); </script>";
    
    echo "<strong>Exemplo explodir de cidade:</strong><br>"; print_r($ton_fb->facebook->api('107800539249675'));
?>

<?php 
    
    //Parte do banco de dados
    //include_once "lib/clear_db/testes_bd.php";   
    
    
    
    
    /*
     * array(6) { ["scheme"]=> string(5) "mysql" 
     *            ["host"]=> string(27) "us-cdbr-east-03.cleardb.com" 
     *            ["user"]=> string(14) "b689511cd8bc36" ["pass"]=> string(8) "bd06a621" 
     *            ["path"]=> string(23) "/heroku_ea52e0cde3e240c" ["query"]=> string(14) "reconnect=true" }
     */
    
    
?>