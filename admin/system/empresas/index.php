<div class="content list_content">

    <section class="list_emp">

        <h1>Empresas:</h1>      

        <?php
        $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
        if ($empty):
            ADVErro("Oppsss: Você tentou editar uma empresa que não existe no sistema!", ADV_INFOR);
        endif;

        $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
        if ($action):
            require ('_models/AdminEmpresa.class.php');

            $empAction = filter_input(INPUT_GET, 'emp', FILTER_VALIDATE_INT);
            $empUpdate = new AdminEmpresa();

            switch ($action):
                case 'active':
                    $empUpdate->ExeStatus($empAction, '1');
                    ADVErro("O status da empresa foi atualizado para <b>ativo</b>. Empresa publicado!", ADV_ACCEPT);
                    break;

                case 'inative':
                    $empUpdate->ExeStatus($empAction, '0');
                    ADVErro("O status da empresa foi atualizado para <b>inativo</b>. Empresa agora é um rascunho!", ADV_ALERT);
                    break;

                case 'delete':
                    $empUpdate->ExeDelete($empAction);
                    ADVErro($empUpdate->getError()[0], $empUpdate->getError()[1]);
                    break;

                default :
                    ADVErro("Ação não foi identifica pelo sistema, favor utilize os botões!", ADV_ALERT);
            endswitch;
        endif;

        $empi = 0;
        $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        $Pager = new Pager('painel.php?exe=empresas/index&page=');
        $Pager->ExePager($getPage, 10);

        $readEmp = new Read;

        $readEmp->ExeRead("app_empresas", "ORDER BY empresa_status ASC, empresa_title ASC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
        if ($readEmp->getResult()):
            foreach ($readEmp->getResult() as $emp):
                $empi++;
                extract($emp);
                $status = (!$empresa_status ? 'style="background: #fffed8"' : '');

                $stateObj = clone($readEmp);
                $stateObj->ExeRead("app_estados", "WHERE estado_id = :est", "est={$empresa_uf}");
                $state = ($stateObj->getResult() ? $stateObj->getResult()[0]['estado_uf'] : 'NULL');

                $cityObj = clone($readEmp);
                $cityObj->ExeRead("app_cidades", "WHERE cidade_id = :city", "city={$empresa_cidade}");
                $city = ($cityObj->getResult() ? $cityObj->getResult()[0]['cidade_nome'] : 'NULL');
                ?>
                <article<?php if ($empi % 2 == 0) echo ' class="right"'; ?> <?= $status; ?>>
                    <header>

                        <div class="img thumb_small">
                            <?= Check::Image('../uploads/' . $empresa_capa, $empresa_title, 120, 70); ?>
                        </div>

                        <hgroup>
                            <h1><a target="_blank" href="../empresa/<?= $empresa_name; ?>" title="Ver Empresa"><?= $empresa_title; ?></a></h1>
                            <h2><?= $city . ' / ' . $state; ?></h2>
                        </hgroup>
                    </header>
                    <ul class="info post_actions">
                        <li><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($empresa_date)); ?>Hs</li>
                        <li><a class="act_view" target="_blank" href="../empresa/<?= $empresa_name; ?>" title="Ver no site">Ver no site</a></li>
                        <li><a class="act_edit" href="painel.php?exe=empresas/update&emp=<?= $empresa_id; ?>" title="Editar">Editar</a></li>

                        <?php if (!$empresa_status): ?>
                            <li><a class="act_inative" href="painel.php?exe=empresas/index&emp=<?= $empresa_id; ?>&action=active" title="Ativar">Ativar</a></li>
                        <?php else: ?>
                            <li><a class="act_ative" href="painel.php?exe=empresas/index&emp=<?= $empresa_id; ?>&action=inative" title="Inativar">Inativar</a></li>
                        <?php endif; ?>

                        <li><a class="act_delete" href="painel.php?exe=empresas/index&emp=<?= $empresa_id; ?>&action=delete" title="Excluir">Deletar</a></li>
                    </ul>
                </article>
                <?php
            endforeach;
        else:
            $Pager->ReturnPage();
            ADVErro("Desculpe, ainda não existem empresas cadastradas!", ADV_INFOR);
        endif;
        ?>

        <div class="clear"></div>
    </section>


    <?php
    $Pager->ExePaginator("app_empresas");
    echo $Pager->getPaginator();
    ?>

    <div class="clear"></div>
</div> <!-- content home -->