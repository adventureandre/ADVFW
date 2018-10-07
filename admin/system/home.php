<div class="content home">

    <aside>
        <h1 class="boxtitle">Estatísticas de Acesso:</h1>

        <article class="sitecontent boxaside">
            <h1 class="boxsubtitle">Conteúdo:</h1>

            <?php
            //OBJETO READ
            $read = new Read;

            //VISITAS DO SITE
            $read->FullRead("SELECT SUM(siteviews_views) AS views FROM adv_siteviews");
            $Views = $read->getResult()[0]['views'];

            //USUÁRIOS
            $read->FullRead("SELECT SUM(siteviews_users) AS users FROM adv_siteviews");
            $Users = $read->getResult()[0]['users'];

            //MÉDIA DE PAGEVIEWS
            $read->FullRead("SELECT SUM(siteviews_pages) AS pages FROM adv_siteviews");
            $ResPages = $read->getResult()[0]['pages'];
            $Pages = substr($ResPages / $Users, 0, 5);

            //POSTS
            $read->ExeRead("adv_posts");
            $Posts = $read->getRowCount();

            //EMPRESAS
            $read->ExeRead("app_empresas");
            $Empresas = $read->getRowCount();
            ?>

            <ul>
                <li class="view"><span><?= $Views; ?></span> visitas</li>
                <li class="user"><span><?= $Users; ?></span> usuários</li>
                <li class="page"><span><?= $Pages; ?></span> pageviews</li>
                <li class="line"></li>
                <li class="post"><span><?= $Posts; ?></span> posts</li>
                <li class="emp"><span><?= $Empresas; ?></span> empresas</li>
                <!--<li class="comm"><span>38</span> comentários</li>-->
            </ul>
            <div class="clear"></div>
        </article>

        <article class="useragent boxaside">
            <h1 class="boxsubtitle">Navegador:</h1>

            <?php
            //LE O TOTAL DE VISITAS DOS NAVEGADORES
            $read->FullRead("SELECT SUM(agent_views) AS TotalViews FROM adv_siteviews_agent");
            $TotalViews = $read->getResult()[0]['TotalViews'];

            $read->ExeRead("adv_siteviews_agent", "ORDER BY agent_views DESC LIMIT 3");
            if (!$read->getResult()):
                ADVErro("Oppsss, Ainda não existem estatísticas de navegadores!", ADV_INFOR);
            else:
                echo "<ul>";
                foreach ($read->getResult() as $nav):
                    extract($nav);

                    //REALIZA PORCENTAGEM DE VISITAS POR NAVEGADOR!
                    $percent = substr(( $agent_views / $TotalViews ) * 100, 0, 5);
                    ?>
                    <li>
                        <p><strong><?= $agent_name; ?>:</strong> <?= $percent; ?>%</p>
                        <span style="width: <?= $percent; ?>%"></span>
                        <p><?= $agent_views; ?> visitas</p>
                    </li>
                    <?php
                endforeach;
                echo "</ul>";
            endif;
            ?>

            <div class="clear"></div>
        </article>
    </aside>

    <section class="content_statistics">

        <h1 class="boxtitle">Publicações:</h1>

        <section>
            <h1 class="boxsubtitle">Artigos Recentes:</h1>

            <?php
            $read->ExeRead("adv_posts", "ORDER BY post_date DESC LIMIT 3");
            if ($read->getResult()):
                foreach ($read->getResult() as $re):
                    extract($re);
                    ?>
                    <article>

                        <div class="img thumb_small">
                            <?= Check::Image('../uploads/' . $post_cover, $post_title, 120, 70); ?>
                        </div>

                        <h1><a target="_blank" href="../artigo/<?= $post_name; ?>" title="Ver Post"><?= Check::Words($post_title, 10) ?></a></h1>
                        <ul class="info post_actions">
                            <li><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($post_date)); ?>Hs</li>
                            <li><a class="act_view" target="_blank" href="../artigo/<?= $post_name; ?>" title="Ver no site">Ver no site</a></li>
                            <li><a class="act_edit" href="painel.php?exe=posts/update&postid=<?= $post_id; ?>" title="Editar">Editar</a></li>

                            <?php if (!$post_status): ?>
                                <li><a class="act_inative" href="painel.php?exe=posts/index&post=<?= $post_id; ?>&action=active" title="Ativar">Ativar</a></li>
                            <?php else: ?>
                                <li><a class="act_ative" href="painel.php?exe=posts/index&post=<?= $post_id; ?>&action=inative" title="Inativar">Inativar</a></li>
                            <?php endif; ?>

                            <li><a class="act_delete" href="painel.php?exe=posts/index&post=<?= $post_id; ?>&action=delete" title="Excluir">Deletar</a></li>
                        </ul>

                    </article>
                    <?php
                endforeach;
            endif;
            ?>
        </section>          


        <section>
            <h1 class="boxsubtitle">Artigos Mais Vistos:</h1>

            <?php
            $read->ExeRead("adv_posts", "ORDER BY post_views DESC LIMIT 3");
            if ($read->getResult()):
                foreach ($read->getResult() as $re):
                    extract($re);
                    ?>
                    <article>

                        <div class="img thumb_small">
                            <?= Check::Image('../uploads/' . $post_cover, $post_title, 120, 70); ?>
                        </div>

                        <h1><a target="_blank" href="../artigo/<?= $post_name; ?>" title="Ver Post"><?= Check::Words($post_title, 10) ?></a></h1>
                        <ul class="info post_actions">
                            <li><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($post_date)); ?>Hs</li>
                            <li><a class="act_view" target="_blank" href="../artigo/<?= $post_name; ?>" title="Ver no site">Ver no site</a></li>
                            <li><a class="act_edit" href="painel.php?exe=posts/update&postid=<?= $post_id; ?>" title="Editar">Editar</a></li>

                            <?php if (!$post_status): ?>
                                <li><a class="act_inative" href="painel.php?exe=posts/index&post=<?= $post_id; ?>&action=active" title="Ativar">Ativar</a></li>
                            <?php else: ?>
                                <li><a class="act_ative" href="painel.php?exe=posts/index&post=<?= $post_id; ?>&action=inative" title="Inativar">Inativar</a></li>
                            <?php endif; ?>

                            <li><a class="act_delete" href="painel.php?exe=posts/index&post=<?= $post_id; ?>&action=delete" title="Excluir">Deletar</a></li>
                        </ul>

                    </article>
                    <?php
                endforeach;
            endif;
            ?>
        </section>                           

    </section> <!-- Estatísticas -->

    <div class="clear"></div>
</div> <!-- content home -->