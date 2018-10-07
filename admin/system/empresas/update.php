<div class="content form_create">

    <article>

        <header>
            <h1>Atualizar Empresa:</h1>
        </header>

        <?php
        $empresa = filter_input(INPUT_GET, 'emp', FILTER_VALIDATE_INT);
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($data && $data['SendPostForm']):
            $data['empresa_status'] = ($data['SendPostForm'] == 'Atualizar' ? '0' : '1');
            $data['empresa_capa'] = ($_FILES['empresa_capa']['tmp_name'] ? $_FILES['empresa_capa'] : 'null');

            unset($data['SendPostForm']);

            require('_models/AdminEmpresa.class.php');
            $cadastra = new AdminEmpresa;
            $cadastra->ExeUpdate($empresa,$data);

            ADVErro($cadastra->getError()[0], $cadastra->getError()[1]);
        else:
            $readEmp = new Read;
            $readEmp->ExeRead("app_empresas", "WHERE empresa_id = :emp", "emp={$empresa}");
            if (!$readEmp->getResult()):
                header('Location: painel.php?exe=empresas/index&empty=true');
            else:
                $data = $readEmp->getResult()[0];
            endif;
        endif;

        $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
        if ($checkCreate && empty($cadastra)):
            ADVErro("A empresa <b>{$data['empresa_title']}</b> foi cadastrada com sucesso no sistema!", ADV_ACCEPT);
        endif;
        ?>

        <form name="PostForm" action="" method="post" enctype="multipart/form-data">

            <label class="label">
                <span class="field">Logo da empresa: <sup>Exatamente 578x288px (JPG ou PNG)</sup></span>
                <input type="file" name="empresa_capa" />
            </label>

            <label class="label">
                <span class="field">Nome da Empresa:</span>
                <input type="text" name="empresa_title" value="<?php if (isset($data['empresa_title'])) echo $data['empresa_title']; ?>" />
            </label>

            <label class="label">
                <span class="field">Ramo de atividade:</span>
                <input type="text" name="empresa_ramo" value="<?php if (isset($data['empresa_ramo'])) echo $data['empresa_ramo']; ?>" />
            </label>

            <label class="label">
                <span class="field">Sobre a empresa:</span>
                <textarea name="empresa_sobre" rows="3"><?php if (isset($data['empresa_sobre'])) echo $data['empresa_sobre']; ?></textarea>
            </label>

            <div class="label_line">
                <label class="label_medium">
                    <span class="field">Site da Empresa:</span>
                    <input type="url" placeholder="http://www.upinside.com.br" name="empresa_site" value="<?php if (isset($data['empresa_site'])) echo $data['empresa_site']; ?>" />
                </label>

                <label class="label_medium">
                    <span class="field">Facebook Page:</span>
                    <input type="text" placeholder="upinside" name="empresa_facebook" value="<?php if (isset($data['empresa_facebook'])) echo $data['empresa_facebook']; ?>" />
                </label>                
            </div><!-- line -->

            <label class="label">
                <span class="field">Nome da rua / Número:</span>
                <input type="text" placeholder="Rua Nome da Rua / 1287" name="empresa_endereco" value="<?php if (isset($data['empresa_endereco'])) echo $data['empresa_endereco']; ?>" />
            </label>            

            <div class="label_line">
                <label class="label_small">
                    <span class="field">Estado UF:</span>
                    <select class="j_loadstate" name="empresa_uf">
                        <option value="" selected> Selecione o estado </option>
                        <?php
                        $readState = new Read;
                        $readState->ExeRead("app_estados", "ORDER BY estado_nome ASC");
                        foreach ($readState->getResult() as $estado):
                            extract($estado);
                            echo "<option value=\"{$estado_id}\" ";
                            if (isset($data['empresa_uf']) && $data['empresa_uf'] == $estado_id): echo 'selected';
                            endif;
                            echo "> {$estado_uf} / {$estado_nome} </option>";
                        endforeach;
                        ?>                        
                    </select>
                </label>

                <label class="label_small">
                    <span class="field">Cidade:</span>
                    <select class="j_loadcity" name="empresa_cidade">
                        <?php if (!isset($data['empresa_cidade'])): ?>
                            <option value="" selected disabled> Selecione antes um estado </option>
                            <?php
                        else:
                            $City = new Read;
                            $City->ExeRead("app_cidades", "WHERE estado_id = :uf ORDER BY cidade_nome ASC", "uf={$data['empresa_uf']}");
                            if ($City->getRowCount()):
                                foreach ($City->getResult() as $cidade):
                                    extract($cidade);
                                    echo "<option value=\"{$cidade_id}\" ";
                                    if (isset($data['empresa_cidade']) && $data['empresa_cidade'] == $cidade_id):
                                        echo "selected";
                                    endif;
                                    echo "> {$cidade_nome} </option>";
                                endforeach;
                            endif;
                        endif;
                        ?>
                    </select>
                </label>

                <label class="label_small">
                    <span class="field">Indicação:</span>
                    <select name="empresa_categoria">
                        <option value="" selected> Indique a empresa como </option>
                        <option value="onde-comer" <?php if (isset($data['empresa_categoria']) && $data['empresa_categoria'] == 'onde-comer') echo 'selected'; ?>> Onde Comer </option>
                        <option value="onde-ficar" <?php if (isset($data['empresa_categoria']) && $data['empresa_categoria'] == 'onde-ficar') echo 'selected'; ?>> Onde Ficar </option>
                        <option value="onde-comprar" <?php if (isset($data['empresa_categoria']) && $data['empresa_categoria'] == 'onde-comprar') echo 'selected'; ?>> Onde Comprar </option>
                        <option value="onde-se-divertir" <?php if (isset($data['empresa_categoria']) && $data['empresa_categoria'] == 'onde-se-divertir') echo 'selected'; ?>> Onde se Divertir </option>
                    </select>
                </label>
            </div><!--/line-->

            <div class="gbform"></div>

            <input type="submit" class="btn blue" value="Atualizar" name="SendPostForm" />
            <input type="submit" class="btn green" value="Atualizar & Publicar" name="SendPostForm" />
        </form>

    </article>

    <div class="clear"></div>
</div> <!-- content form- -->