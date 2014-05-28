<?php
/**
 * Description of UserController
 *
 * @author ilton_garcia
 */
require_once "config/lib-fb-config.php";
require_once "TONLIB_INTERFACE.php";
require_once "lib/facebook/facebook.php";

/**
 * Classe responsável por abstrair as funcionalidades (deixar o código mais claro do que as chamadas do facebook)
 */
class TonLibFB {
    /** Armazena a instância da aplicação no facebook **/
    public $facebook = NULL;
    /** Armazena o ID do usuário, útil para pegar imagem do perfil, por exemplo **/
    public $user = NULL;
    /** Array com todos os dados do usuário que foram liberados pelas permissões **/
    public $userData = NULL;
    /** String com a lista de permissões do aplicativo **/
    private $escopo_permissoes = "";
    /** Variável de auxílio na criação do post **/
    private $postURL = "";
    /** String de Token de acesso da aplicação **/
    public $accessToken = NULL;
    /** String com a URL do App no facebook **/
    public $urlApp = "";
    /** String com a URL do App no facebook **/
    public $amigos = array();
    
    //Atributos privados de auxílio
    public $sent = false, $ligado = 1, $desligado = 0;

    /** Método que retorna a instância do FBMain, já que o construtor é privado para economizar memória (singleton) **/
    public static function getInstance($ton_app_id, $ton_secret, $ton_app_escopo_permissoes, $ton_app_url, $ton_app_domain) {
        return new TonLibFB($ton_app_id, $ton_secret, $ton_app_escopo_permissoes, $ton_app_url, $ton_app_domain);
    }//fim getInstance
    
    private function __construct($ton_app_id, $ton_secret, $ton_app_escopo_permissoes, $ton_app_url, $ton_app_domain) {
        //Create facebook application instance.
        $this->facebook = new Facebook(array(
          'appId'  => $ton_app_id,
          'secret' => $ton_secret,
          'cookie' => true,
          'domain' => $ton_app_domain
        ));
        
        $this->urlApp = $ton_app_url;
        $this->user = $this->facebook->getUser();
        $this->postURL = "https://graph.facebook.com/$ton_app_id/feed";
        $this->escopo_permissoes = $ton_app_escopo_permissoes;
        $this->accessToken = $this->facebook->getAccessToken();
        
        //Varifica se veio usuário, isto é, se o usuário está logado no face e assina a aplicação
        if ($this->user && $this->permissoesChecadas()) $this->logar();
        else die('<script type="text/javascript"> top.location ="' . $this->getURLAssinarApp() . '" </script>');
    }//fim construtor
    
    /**
     * Método que faz o login no facebook e retorna a sessão com o login do face
     * @return SESSION login_face
     */
    private function logar(){
        try {
            $this->userData = $this->facebook->api('/me');
        } catch (FacebookApiException $e) {
            echo 'Erro face-api: <br>'; var_dump($e); exit;
        }//fim catch
    }//fim getUserData
    
    /** Método responsável por criar o array de configuração das permissões do app **/
    public function getURLAssinarApp(){
        //Cai aqui se o usuário não for assinante da aplicação
	 return $this->facebook->getLoginUrl(array(
            'canvas' => $this->ligado,
            'fbconnect' => $this->desligado,
            'scope' => $this->escopo_permissoes,
            'redirect_uri' => $this->urlApp
	));
    }//fim do redirecAssinarApp
    
    /**
     * Checa se todas permissões do usuário estão coerentes ao que o App requer... 
     * Se não estiverem o App manda o usuário re assinar o App
     */
    function permissoesChecadas(){
        // Torna o escopo em uma array
        $scope = array_map('trim', explode(",", $this->escopo_permissoes));

        // Pega as permissões do usuário em uma array
        $permissoes_usuario = $this->facebook->api('/me/permissions');
        $permissoes_usuario = array_keys($permissoes_usuario['data'][0]);

        // As permissões não garantidas pelo usuário são a diferença entre as arrays
        $permissoes_dif = array_diff($scope, $permissoes_usuario);
        
        // Executa o if se houve diferença
        if (!empty($permissoes_dif) && !isset($_REQUEST['usuario_externo'])) die('<script type="text/javascript"> top.location ="' . $this->getURLAssinarApp() . '" </script>');
        else return true;
    }


    /** Retorna a lista de objs facebook dos amigos da pessoa
     *  @param int $qtdFriends quantidade de amigos a serem capturados para a array **/
    public function getFriends($qtdFriends = 5){
        try {
            $friendsTmp = $this->facebook->api('/' . $this->userData['id'] . '/friends');
            shuffle($friendsTmp['data']);
            array_splice($friendsTmp['data'], $qtdFriends);
            if($qtdFriends == "all") array_slice($friendsTmp['data'], 1);
            return $friendsTmp['data'];
	} catch (FacebookApiException $e) {
            print_r($e); exit;
	}//fim catch
    }//fim do getFriends
    
    /** Método responsável por enviar mensável ao mural
     *  @param String $msgMural => mensagem a ser enviada ao mural do usuário
     *  @param String $linkPoster => link de quem está postando a mensagem (dono do app/item que está postando).
     *  @param String $usuarioDonoMural  "me" = meu mural,  "friend" = mural do meu amigo
     *  @return id referente ao post gerado
     */
    public function setMsgMural($msgMural, $linkPoster, $usuarioDonoMural="me", $pic=''){
        //Postar a mensagem no mural
        try {
            $post = $this->facebook->api('/me/feed', 'POST', array(
                                                            'link' => $linkPoster,
                                                            'message' => $msgMural,
                                                            'picture'=>$pic
                                                        ));
            
             return $post['id'];
        } catch (FacebookApiException $e) {
            print_r($e); exit;
        }//fim catch
    }//fim setMsgMural
    
    /**
     * Função estática que retorna a foto do perfil do usuário
     * @param type $ton_user = objeto retornado pelo facebook api(). 
     * Exemplo ton user evento: $eventos = $ton_fb->facebook->api('/'. $amigos_ordenados[4]['uid'] .'/events'); $eventos['data'][n]
     * @param type $tipo = saber se é o usuário assinante ou se é o amigo do usuário (RECOMENDÁVEL USAR AS CONSTANTES DE TONLIB_INTERFACE)
     * @param boolean $eh_grande? Se true imprime uma imagem do tamanho da postagem se não imprime um thumbail */
    public static function getFotoPerfil($ton_user, $tipo=0, $img_grande=false){
        $img_append = '';
        if($img_grande) $img_append = '?type=large';
        if($tipo==TonLibInterface::FB_TIPO_USUARIO_ASSINANTE) echo '<img src="https://graph.facebook.com/' . $ton_user[username] . '/picture'.$img_grande.'" />';
        else if($tipo==TonLibInterface::FB_TIPO_USUARIO_ASSINANTE_FRIEND) echo '<img src="https://graph.facebook.com/' . $ton_user[id] . '/picture'.$img_grande.'" />';
        else if($tipo==TonLibInterface::FB_TIPO_EVENTO)  die('<img src="https://graph.facebook.com/'. $ton_user['id'] .'/picture'.$img_append.'">');
    }
    
    /**
     * Método para executar queries do face
     * @param String $query */
    public function fb_query($fql){
        return $this->facebook->api(array(
                                            'method' => 'fql.query',
                                            'query' => $fql
                                          ));
    }
    
    /*
     * Retorna uma array com os eventos em que os amigos vão
     * @param $amigos = array ou objeto face de amigos */
    function getEventosAmigos($amigos=NULL){
        if($amigos==NULL) return; // fazer depois para pegar os amigos de outra função aqui dentro
        $eventos = array();

        $count = 0;
        foreach ($amigos as $amigo) {
            //$evento_corrente = $this->facebook->api('/'. $amigo['uid'] .'/events');
            $fql_eventos_amigo_corrente = 'SELECT eid FROM event_member WHERE uid = ' . $amigo['uid'];
            $evento_corrente = $this->facebook->api('/'. $amigo['uid'] .'/events');
            $eventos_amigo_corrente = $this->fb_query($fql_eventos_amigo_corrente);
            
            print_r($eventos_amigo_corrente);echo '<br><br><br>';
            print_r($evento_corrente);die;
            if(empty($evento_corrente['data'])) continue;
            
            array_push($eventos, $eventos_amigo_corrente['data']);
        }
        
        return $eventos;
    }
    
    /**
     * Confirma participação do usuário no Evento de ID especificado
     * @param int $eid => Event id do evento
     * @return boolean $ok => se foi possível fazer ou não confirmar a participação
     */
    function participarEvento($eid){
        return $this->facebook->api("/$eid/attending", "post", array("access_token" => $this->accessToken));
    }
}//fim da classe FBMain


$ton_fb = TonLibFB::getInstance($ton_app_id, $ton_secret, $ton_app_escopo_permissoes, $ton_app_url, $ton_app_domain);
$_SESSION['user_fb'] = $ton_fb;

?>