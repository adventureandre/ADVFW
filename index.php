<?php
require_once "_app/Config.inc.php";

$Link = new Link;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <title></title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,500,700" rel="stylesheet">
    <link rel="base" href="<?= HOME ?>">
    <link rel="stylesheet" href="<?=INCLUDE_PATH ?>/css/boot.css">
    <link rel="stylesheet" href="<?=INCLUDE_PATH ?>/css/style.css">
    <?php
    $Link->getTags();
    ?>
</head>
<body>


    <?php
    require(REQUIRE_PATH . 'inc/header.inc.php');

    if (!require($Link->getPatch())):
        ADVErro('Erro ao incluir arquivo de navegação!', ADV_ERROR, true);
    endif;

    require(REQUIRE_PATH . 'inc/footer.inc.php');
    ?>

<script src="<?= HOME ?>/_cdn/jquery.js"></script>
<script src="<?= HOME ?>/_cdn/sharebox.js"></script>
</body>
</html>
