<?php
$pg_google_author = '103958419096641225872';
$pg_google_publisher = '107305124528362639842';
$pg_fb_app = '626590460695980';
$pg_fb_page = 'upinside';
$pg_twitter = '@UpInsideBr';
$pg_domain = 'www.upisnide.com.br';
$pg_sitekit = INCLUDE_PATH . '/img/sitekit/';
$pg_url = HOME."/single_post/".$post_name;

$pg_title = $post_title;






$pg_share = str_replace('/404', '', $pg_url);

?>

<ul class="sharebox <?= (!empty($ShareClass) ? $ShareClass : ''); ?>">
    <li class=" like facebook"><span class="count">0</span> <a href="<?= urlencode($pg_share); ?>&app_id=<?= $pg_fb_app; ?>" title="Compartilhe no Facebook">Compartilhe no Facebook</a></li>
    <li class="like google"><span class="count">0</span> <a href="<?= $pg_share; ?>" title="Recomende no Google+">Recomende no Google+</a></li>
    <li class="like twitter"><span class="count">0</span> <a href="<?= urlencode($pg_share); ?>" rel="&text=<?= $pg_title; ?> <?= $pg_twitter; ?>" title= "Conte Isto no Twitter">Conte Isto no Twitter</a></li>
</ul>

