<?php

/**
 * AdminEmpresa.class [ MODEL ADMIN ]
 * Responável por gerenciar as empresas no admin do sistema!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class AdminEmpresa {

    private $Data;
    private $Empresa;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'app_empresas';

    /**
     * <b>Cadastrar a Empresa:</b> Envelope os dados da empresa em um array atribuitivo e execute esse método
     * para cadastrar a mesma no banco.
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;
        if (in_array('', $this->Data)):
            $this->Error = ["Erro ao Cadastrar: Para cadastrar uma empresa, preencha todos os campos!", ADV_ALERT];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->sendCapa();
            $this->Create();
        endif;
    }

    /**
     * <b>Atualizar a Empresa:</b> Envelope os dados em uma array atribuitivo e informe o id de uma empresa
     * para atualiza-la no banco de dados!
     * @param INT $EmpresaId = Id da Empresa
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($EmpresaId, array $Data) {
        $this->Empresa = (int) $EmpresaId;
        $this->Data = $Data;
        if (in_array('', $this->Data)):
            $this->Error = ["Erro ao Atualizar: Para atualizar <b>{$this->Data['empresa_title']}</b>, preencha todos os campos!", ADV_ALERT];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->sendCapa();
            $this->Update();
        endif;
    }

    /**
     * <b>Deleta Empresas:</b> Informe o ID da empresa a ser removida para que esse método realize uma
     * checagem excluinto todos os dados nessesários e removendo a empresa do banco!
     * @param INT $EmpresaId = Id da empresa!
     */
    public function ExeDelete($EmpresaId) {
        $this->Empresa = (int) $EmpresaId;

        $ReadEmp = new Read;
        $ReadEmp->ExeRead(self::Entity, "WHERE empresa_id = :emp", "emp={$this->Empresa}");
        if (!$ReadEmp->getResult()):
            $this->Error = ["A empresa que você tentou deletar não existe no sistema!", ADV_ERROR];
            $this->Result = false;
        else:
            $EmpDelete = $ReadEmp->getResult()[0];
            if (file_exists('../uploads/' . $EmpDelete['empresa_capa']) && !is_dir('../uploads/' . $EmpDelete['empresa_capa'])):
                unlink('../uploads/' . $EmpDelete['empresa_capa']);
            endif;

            $deleta = new Delete;
            $deleta->ExeDelete(self::Entity, "WHERE empresa_id = :emp", "emp={$this->Empresa}");

            $this->Error = ["A empresa <b>{$EmpDelete['empresa_title']}</b> foi removida com sucesso do sistema!", ADV_ACCEPT];
            $this->Result = true;
        endif;
    }

    /**
     * <b>Ativa/Inativa Empresa:</b> Informe o ID da empresa e o status e um status sendo 1 para ativo e 0 para
     * rascunho. Esse méto ativa e inativa as empresas!
     * @param INT $PostId = Id do post
     * @param STRING $PostStatus = 1 para ativo, 0 para inativo
     */
    public function ExeStatus($EmpresaId, $EmpresaStatus) {
        $this->Empresa = (int) $EmpresaId;
        $this->Data['empresa_status'] = (string) $EmpresaStatus;
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE empresa_id = :id", "id={$this->Empresa}");
    }

    /**
     * <b>Verificar Ação:</b> Retorna TRUE se ação for efetuada ou FALSE se não. Para verificar erros
     * execute um getError();
     * @return BOOL $Var = True or False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com um erro e um tipo.
     * @return ARRAY $Error = Array associativo com o erro
     */
    public function getError() {
        return $this->Error;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Valida e cria os dados para realizar o cadastro. Realiza Upload da Capa!
    private function setData() {
        $this->Data['empresa_name'] = Check::Name($this->Data['empresa_title']);
        $this->Data['empresa_date'] = date('Y-m-d H:i:s');
    }

    //Verifica o NAME da empresa. Se existir adiciona um pós-fix +1
    private function setName() {
        $Where = ( isset($this->Empresa) ? "empresa_id != {$this->Empresa} AND" : '');

        $ReadName = new Read;
       $ReadName->ExeRead(self::Entity, "WHERE {$Where} empresa_title = :t", "t={$this->Data['empresa_title']}");
        if ($ReadName->getResult()):
           $this->Data['empresa_name'] = $this->Data['empresa_name'] . '-' . $ReadName->getRowCount();
        endif;
    }

    //Verifica e envia a capa da empresa para a pasta!
    private function sendCapa() {
        if (!empty($this->Data['empresa_capa']['tmp_name'])):
            list($w, $h) = getimagesize($this->Data['empresa_capa']['tmp_name']);

            if ($w != '578' || $h != '288'):
                $this->Error = ['Capa Inválida: A Capa da empresa deve ter exatamente 578x288px do tipo .JPG, .PNG ou .GIF!', ADV_INFOR];
                $this->Result = false;
            else:
                $this->checkCover();
                $Upload = new Upload;
                $Upload->Image($this->Data['empresa_capa'], $this->Data['empresa_name'], 578, 'empresas');

                if ($Upload->getError()):
                    $this->Error = $Upload->getError();
                    $this->Result = false;
                else:
                    $this->Data['empresa_capa'] = $Upload->getResult();
                    $this->Result = true;
                endif;
            endif;
        endif;
    }

    //Verifica se já existe uma capa, se sim deleta para enviar outra!
    private function checkCover() {
        $readCapa = new Read;
        $readCapa->FullRead("SELECT empresa_capa FROM app_empresas WHERE empresa_id = :id", "id={$this->Empresa}");

        if ($readCapa->getRowCount()):
            $delCapa = $readCapa->getResult()[0]['empresa_capa'];
            if (file_exists("../uploads/{$delCapa}") && !is_dir("../uploads/{$delCapa}")):
                unlink("../uploads/{$delCapa}");
            endif;
        endif;
    }

    //Cadastra a empresa no banco!
    private function Create() {
        $Create = new Create;
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResult()):
            $this->Result = $Create->getResult();
            $this->Error = ["A empresa <b>{$this->Data['empresa_title']}</b> foi cadastrada com sucesso no sistema!", ADV_ACCEPT];
        endif;
    }

    //Atualiza a empresa no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE empresa_id = :id", "id={$this->Empresa}");
        if ($Update->getRowCount() >= 1):
            $this->Error = ["A Empresa <b>{$this->Data['empresa_title']}</b> foi atualizada com sucesso!", ADV_ACCEPT];
            $this->Result = true;
        endif;
    }

}
