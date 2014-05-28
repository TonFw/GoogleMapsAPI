<?php
    /**
     * Description of UserController
     *
     * @author ilton_garcia
     */
    /** Interface com a definição das constantes utilizadas no TonLibFB **/ 
    interface TonLibInterface {
        /** Tipo de usuário que assina a aplicação **/
        const FB_TIPO_USUARIO_ASSINANTE = 0;
        /** Tipo de usuário que é amigo de quem assina a aplicação **/
        const FB_TIPO_USUARIO_ASSINANTE_FRIEND = 1;
        /** Tipo de objeto como evento **/
        const FB_TIPO_EVENTO = 2;
        /** Tipo quando usuário for uma página **/
        const FB_TIPO_PAGINA = 3;
    }//fim interface
    
?>
