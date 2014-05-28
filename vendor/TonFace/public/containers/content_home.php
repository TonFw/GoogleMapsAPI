<!--
    <script type="text/javascript" src="public/js/jquery.js"></script>
    <script type="text/javascript" src="public/js/minhas_funcoes_js.js"></script>
-->

    <h1>TonGarcia - FaceApp (Testes API)</h1> 
    <p>
        Olá <strong><i><?php echo $fb_user_corrente['name']; ?></strong></i>, tudo bem?
    </p>
    <h2><strong>Lista randomica de 5 amigos:</strong></h2>
    <p>
        <?php listaAmigos($ton_fb); ?>
    </p>
    <p>
        <div>
            <?php echo "<br><br>Nick: " . $fb_user_corrente['username'] . "<br><br>"; ?>
            <strong>Imagem do perfil: </strong><?php TonLibFB::getFotoPerfil($fb_user_corrente); ?>
        </div>
    </p>
    
    <form action="#" method="post">
        <input id="msg" type="text" name="msg_mural" placeholder="Mensagem a ser postada no mural">
        <input id="link_msg" type="text" name="link_msg_mural" placeholder="Link da mensagem">
        <input id="submit_btn" type="submit" value="Submit">
    </form>