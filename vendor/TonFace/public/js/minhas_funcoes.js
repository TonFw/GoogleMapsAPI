/* BACKUP
function set_pagina_assinatura_app(link_click) {
    $(link_click).click(function(){
       top.location = "<?php echo $loginUrl; ?>";
       alert("Link_a foi clicado!");
    });

    $(link_click).ready(function() {
        $(link_click).html("Link dinamico aqui");
        alert("XPTO pronto!");
        $(link_click).trigger('click');
    });
}
*/