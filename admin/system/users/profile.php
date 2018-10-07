<div class="content form_create">

    <article>

        <?php extract($_SESSION['userlogin']); ?>

        <h1>Olá <?= "{$user_name} {$user_lastname}"; ?>, atualize seu perfíl!</h1>

        <?php
        $ClienteData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $userId = $_SESSION['userlogin']['user_id'];

        if ($ClienteData && $ClienteData['SendPostForm']):
            unset($ClienteData['SendPostForm']);
            extract($ClienteData);

            require('_models/AdminUser.class.php');
            $cadastra = new AdminUser;
            $cadastra->ExeUpdate($userId, $ClienteData);

            if ($cadastra->getResult()):
                ADVErro("Seus dados foram atualizados com sucesso! <i>O sistema será atualizado no próximo login!!!</i>", ADV_ACCEPT);
            else:
                ADVErro($cadastra->getError()[0], $cadastra->getError()[1]);
            endif;
        else:
            extract($_SESSION['userlogin']);
        endif;
        ?>

        <form action = "" method = "post" name = "UserEditForm">

            <label class="label">
                <span class="field">Nome:</span>
                <input
                    type = "text"
                    name = "user_name"
                    value = "<?= $user_name; ?>"
                    title = "Informe seu primeiro nome"
                    required
                    />
            </label>

            <label class="label">
                <span class="field">Sobrenome:</span>
                <input
                    type = "text"
                    name = "user_lastname"
                    value = "<?= $user_lastname; ?>"
                    title = "Informe seu sobrenome"
                    required
                    />
            </label>

            <label class="label">
                <span class="field">E-mail:</span>
                <input
                    type = "email"
                    name = "user_email"
                    value = "<?= $user_email; ?>"
                    title = "Informe seu e-mail"
                    required
                    />
            </label>

            <div class="label_line">

                <label class="label">
                    <span class="field">Senha:</span>
                    <input
                        style="width: 260px;"
                        type = "password"
                        name = "user_password"
                        value = ""
                        title = "Informe sua senha [ de 6 a 12 caracteres! ]"
                        pattern = ".{6,12}"
                        />
                </label>                
            </div>

            <input type="submit" name="SendPostForm" value="Atualizar Perfil" class="btn blue" />

        </form>


    </article>

    <div class="clear"></div>
</div> <!-- content home -->