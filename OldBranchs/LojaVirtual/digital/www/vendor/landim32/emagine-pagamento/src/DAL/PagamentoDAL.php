<?php
namespace Emagine\Pagamento\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Pagamento\Model\PagamentoInfo;

class PagamentoDAL
{

    /**
     * @return string
     */
    protected function query() {
        return "
            SELECT
                pagamento.id_pagamento,
                pagamento.id_usuario,
                pagamento.id_deposito,
                pagamento.data_inclusao,
                pagamento.ultima_alteracao,
                pagamento.data_vencimento,
                pagamento.data_pagamento,
                pagamento.valor_desconto,
                pagamento.valor_juro,
                pagamento.valor_multa,
                pagamento.troco_para,
                pagamento.cod_tipo,
                pagamento.cod_situacao,
                pagamento.observacao,
                pagamento.mensagem,
                pagamento.cpf,
                pagamento.logradouro,
                pagamento.complemento,
                pagamento.numero,
                pagamento.bairro,
                pagamento.cidade,
                pagamento.uf,
                pagamento.cep,
                pagamento.boleto_url,
                pagamento.boleto_linha_digitavel,
                pagamento.autenticacao_url
            FROM pagamento
        ";
    }

    /**
     * @param int $id_usuario
     * @param int $cod_situacao
     * @return PagamentoInfo[]
     * @throws Exception
     */
    public function listar($id_usuario, $cod_situacao) {
        $query = $this->query() . "
            WHERE (1=1)
        ";
        if ($id_usuario > 0) {
            $query .= " AND pagamento.id_usuario = :id_usuario ";
        }
        if ($cod_situacao > 0) {
            $query .= " AND pagamento.cod_situacao = :cod_situacao ";
        }
        $query .= " ORDER BY pagamento.data_vencimento";
        $db = DB::getDB()->prepare($query);
        if ($id_usuario > 0) {
            $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }
        if ($cod_situacao > 0) {
            $db->bindValue(":cod_situacao", $cod_situacao, PDO::PARAM_INT);
        }
        return DB::getResult($db,"Emagine\\Pagamento\\Model\\PagamentoInfo");
    }

    /**
     * @param int $id_pagamento
     * @return PagamentoInfo
     * @throws Exception
     */
    public function pegar($id_pagamento) {
        $query = $this->query() . "
            WHERE pagamento.id_pagamento = :id_pagamento
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_pagamento", $id_pagamento, PDO::PARAM_INT);
        return DB::getValueAsClass($db,"Emagine\\Pagamento\\Model\\PagamentoInfo");
    }

    /**
     * @param PDOStatement $db
     * @param PagamentoInfo $pagamento
     */
    private function preencherCampo(PDOStatement $db, PagamentoInfo $pagamento) {
        $db->bindValue(":id_usuario", $pagamento->getIdUsuario(), PDO::PARAM_INT);
        if ($pagamento->getIdDeposito() > 0) {
            $db->bindValue(":id_deposito", $pagamento->getIdDeposito(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_deposito", null);
        }
        $db->bindValue(":data_vencimento", $pagamento->getDataVencimento());
        $db->bindValue(":data_pagamento", $pagamento->getDataPagamento());
        $db->bindValue(":valor_desconto", $pagamento->getValorDesconto());
        $db->bindValue(":valor_juro", $pagamento->getValorJuro());
        $db->bindValue(":valor_multa", $pagamento->getValorMulta());
        $db->bindValue(":troco_para", $pagamento->getTrocoPara());
        $db->bindValue(":cod_tipo", $pagamento->getCodTipo(), PDO::PARAM_INT);
        $db->bindValue(":cod_situacao", $pagamento->getCodSituacao(), PDO::PARAM_INT);
        $db->bindValue(":observacao", $pagamento->getObservacao());

        $db->bindValue(":cpf", $pagamento->getCpf());

        $db->bindValue(":logradouro", $pagamento->getLogradouro());
        $db->bindValue(":complemento", $pagamento->getComplemento());
        $db->bindValue(":numero", $pagamento->getNumero());
        $db->bindValue(":bairro", $pagamento->getBairro());
        $db->bindValue(":cidade", $pagamento->getCidade());
        $db->bindValue(":uf", $pagamento->getUf());
        $db->bindValue(":cep", $pagamento->getCep());

        $db->bindValue(":boleto_url", $pagamento->getBoletoUrl());
        $db->bindValue(":boleto_linha_digitavel", $pagamento->getBoletoLinhaDigitavel());

        $db->bindValue(":autenticacao_url", $pagamento->getAutenticacaoUrl());
        $db->bindValue(":mensagem", $pagamento->getMensagem());

    }

    /**
     * @param PagamentoInfo $pagamento
     * @return int
     * @throws \Landim32\EasyDB\DBException
     */
    public function inserir($pagamento) {
        $query = "
			INSERT INTO pagamento (
				id_usuario,
			    id_deposito,
				data_inclusao,
				ultima_alteracao,
				data_vencimento,
				data_pagamento,
				valor_desconto,
				valor_juro,
				valor_multa,
				troco_para,
				cod_tipo,
				cod_situacao,
				observacao,
				cpf,
				logradouro,
				complemento,
				numero,
				bairro,
				cidade,
				uf,
				cep,
				boleto_url,
				boleto_linha_digitavel,
				autenticacao_url,
				mensagem
			) VALUES (
				:id_usuario,
			    :id_deposito,
				NOW(),
				NOW(),
				:data_vencimento,
				:data_pagamento,
				:valor_desconto,
				:valor_juro,
				:valor_multa,
				:troco_para,
				:cod_tipo,
				:cod_situacao,
				:observacao,
				:cpf,
				:logradouro,
				:complemento,
				:numero,
				:bairro,
				:cidade,
				:uf,
				:cep,
				:boleto_url,
				:boleto_linha_digitavel,
				:autenticacao_url,
				:mensagem
			)
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $pagamento);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @param PagamentoInfo $pagamento
     * @throws Exception
     */
    public function alterar($pagamento) {
        $query = "
			UPDATE pagamento SET
				id_usuario = :id_usuario,
			    id_deposito = :id_deposito,
				ultima_alteracao = NOW(),
				data_vencimento = :data_vencimento,
				data_pagamento = :data_pagamento,
				valor_desconto = :valor_desconto,
				valor_juro = :valor_juro,
				valor_multa = :valor_multa,
				troco_para = :troco_para,
				cod_tipo = :cod_tipo,
				cod_situacao = :cod_situacao,
				observacao = :observacao,
				cpf = :cpf,
				logradouro = :logradouro,
				complemento = :complemento,
				numero = :numero,
				bairro = :bairro,
				cidade = :cidade,
				uf = :uf,
				cep = :cep,
				boleto_url = :boleto_url,
				boleto_linha_digitavel = :boleto_linha_digitavel,
				autenticacao_url = :autenticacao_url,
                mensagem = :mensagem
			WHERE id_pagamento = :id_pagamento
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $pagamento);
        $db->bindValue(":id_pagamento", $pagamento->getId(), PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @param int $id_pagamento
     * @throws Exception
     */
    public function excluir($id_pagamento) {
        $query = "
			DELETE FROM pagamento
			WHERE id_pagamento = :id_pagamento
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_pagamento", $id_pagamento, PDO::PARAM_INT);
        $db->execute();
    }

}