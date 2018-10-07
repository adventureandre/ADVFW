<?php
// CONFIGRAÇÕES DO BANCO ####################
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DBSA', 'advFw');

// DEFINE SERVIDOR DE E-MAIL ################
define('MAILUSER', 'admin@funilariaepintura.com.br');
define('MAILPASS', '');
define('MAILPORT', '25');
define('MAILHOST', 'mail.funilariaepintura.com.br');

// DEFINE IDENTIDADE DO SITE ################
define('SITENAME', 'FUNILARIA E PINTURA');
define('SITEDESC', 'Funilaria e pintura, Estetica automotiva, Martelinho de ouro, Revitalização de pintura');

// DEFINE A BASE DO SITE ####################
define('HOME', 'http://alfa');
define('THEME', 'ADVTHEME');

 define('INCLUDE_PATH', HOME . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . THEME);
define('REQUIRE_PATH', 'themes' . DIRECTORY_SEPARATOR . THEME . DIRECTORY_SEPARATOR);


//DEFINE SOCIAIS

define('PG_TWITTER', '@UpInsideBr');
define('PG_FACEBOOK', 'Adventureandre');
define('PG_FACEBOOK_APP', '626590460695980');
define('PG_FACEBOOK_AUTHOR', 'Adventureandre');
define('PG_GOOGLE_AUTHOR', '103958419096641225872');
define('PG_GOOGLE_PUBLISHER', '107305124528362639842');

// AUTO LOAD DE CLASSES ####################
function incluirClasses($Class)
{

    $cDir = ['Conn', 'Helpers', 'Models'];
    $iDir = null;

    foreach ($cDir as $dirName):
        if (!$iDir && file_exists(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php') && !is_dir(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php')):
            include_once(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php');
            $iDir = true;
        endif;
    endforeach;

    if (!$iDir):
        trigger_error("Não foi possível incluir {$Class}.class.php", E_USER_ERROR);
        die;
    endif;
}

spl_autoload_register("incluirClasses");

// TRATAMENTO DE ERROS #####################
//CSS constantes :: Mensagens de Erro
define('ADV_ACCEPT', 'accept');
define('ADV_INFOR', 'infor');
define('ADV_ALERT', 'alert');
define('ADV_ERROR', 'error');

//ADVErro :: Exibe erros lançados :: Front
function ADVErro($ErrMsg, $ErrNo, $ErrDie = null, $Type = null)
{
    $CssClass = ($ErrNo == E_USER_NOTICE ? ADV_INFOR : ($ErrNo == E_USER_WARNING ? ADV_ALERT : ($ErrNo == E_USER_ERROR ? ADV_ERROR : $ErrNo)));
    if ($Type == 'dialog'):
        //nova msn criada dia 22/04/17
        $title = ($ErrNo == E_USER_NOTICE ? ADV_INFOR : ($ErrNo == E_USER_WARNING ? ADV_ALERT : ($ErrNo == E_USER_ERROR ? ADV_ERROR : $ErrNo)));
        $title = ($title === 'infor' ? 'Por favor:' : ($title === 'alert' ? 'Atenção:' : ($title === 'error' ? 'Oppsss:' : $title === $title)));
        if ($title == 'accept'):
            $title = 'Sucesso:';
        endif;
        echo "
                <div class=\"dialog\">
                    <div class=\"ajaxmsg msg {$CssClass}\" id=\"$CssClass\">
                        <strong class=\"tt\">{$title}</strong>
                        <p>{$ErrMsg}</p>
                        <a href=\"#\" class=\"closedial j_ajaxclose\">X FECHAR</a>
                    </div><!-- msg -->
                </div><!--dialog-->";
    else:
        echo "<p class=\"trigger {$CssClass}\">{$ErrMsg}<span class=\"ajax_close\"></span></p>";
    endif;
    if ($ErrDie):
        die;
    endif;
}

//PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine)
{
    $CssClass = ($ErrNo == E_USER_NOTICE ? ADV_INFOR : ($ErrNo == E_USER_WARNING ? ADV_ALERT : ($ErrNo == E_USER_ERROR ? ADV_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"></span></p>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');
