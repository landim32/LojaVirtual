<?php

namespace Emagine\Produto\DAL;

use PDO;
use Exception;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\Produto\Model\LojaInfo;

/**
 * Class LojaDAL
 * @package Emagine\Produto\DAL
 * @tablename loja
 * @author EmagineCRUD
 */
class LojaDAL {

	/**
     * @param bool $distinct
	 * @return string
	 */
	public function query($distinct = false) {
		return "
			SELECT " . ($distinct ? "DISTINCT" : "") . "
				loja.id_loja,
				loja.id_seguimento,
				loja.slug,
				loja.foto,
				loja.email,
				loja.nome,
				loja.cnpj,
				loja.descricao,
				loja.cep,
				loja.logradouro,
				loja.complemento,
				loja.numero,
				loja.bairro,
				loja.cidade,
				loja.uf,
				loja.latitude,
				loja.longitude,
				loja.usa_retirar,
                loja.usa_retirada_mapeada,
				loja.usa_entregar,
				loja.controle_estoque,
				loja.estoque_minimo,
				loja.valor_minimo,
				loja.usa_gateway,
    			loja.cod_gateway,
    			loja.aceita_credito_online,
    			loja.aceita_debito_online,
    			loja.aceita_boleto,
    			loja.aceita_dinheiro,
    			loja.aceita_cartao_offline,
				loja.cod_situacao,
			    loja.mensagem_retirada,
				(
				  SELECT AVG(pedido.nota)
				  FROM pedido
				  WHERE pedido.id_loja = loja.id_loja
				  AND pedido.nota > 0
				) AS 'nota'
			FROM loja
		";
	}

    /**
     * @return string
     */
    public function queryBusca() {
        return "
			SELECT 
				loja.id_loja,
				loja.id_seguimento,
				loja.slug,
				loja.foto,
				loja.email,
				loja.nome,
				loja.cnpj,
				loja.descricao,
				loja.cep,
				loja.logradouro,
				loja.complemento,
				loja.numero,
				loja.bairro,
				loja.cidade,
				loja.uf,
				loja.latitude,
				loja.longitude,
				loja.usa_retirar,
				loja.usa_retirada_mapeada,
				loja.usa_entregar,
				loja.controle_estoque,
				loja.estoque_minimo,
				loja.valor_minimo,
				loja.usa_gateway,
    			loja.cod_gateway,
    			loja.aceita_credito_online,
    			loja.aceita_debito_online,
    			loja.aceita_boleto,
    			loja.aceita_dinheiro,
    			loja.aceita_cartao_offline,
				loja.cod_situacao,
			    loja.mensagem_retirada,
				(
				  SELECT AVG(pedido.nota)
				  FROM pedido
				  WHERE pedido.id_loja = loja.id_loja
				  AND pedido.nota > 0
				) AS 'nota',
				ROUND(
                  (
                    (
                      ACOS(
                        SIN(loja.latitude * PI() / 180) * SIN(:latitude1 * PI() / 180) + 
                        COS(loja.latitude * PI() / 180) * COS(:latitude2 * PI() / 180) * 
                        COS((loja.longitude - :longitude1) * PI() / 180)
                      ) * 180 / PI()
                    ) * 60 * 1.1515
                  ) * 1.609344 * 1000
                ) AS 'distancia'
			FROM loja
		";
    }

	/**
     * @throws Exception
     * @param int $id_seguimento
	 * @return LojaInfo[]
	 */
	public function listar($id_seguimento = null) {
		$query = $this->query() . "
		    WHERE loja.cod_situacao = :cod_situacao
		";
        if ($id_seguimento > 0) {
            $query .= " AND loja.id_seguimento = :id_seguimento ";
        }
        $query .= " ORDER BY loja.nome ";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":cod_situacao", LojaInfo::ATIVO, PDO::PARAM_INT);
        if ($id_seguimento > 0) {
            $db->bindValue(":id_seguimento", $id_seguimento, PDO::PARAM_INT);
        }
		return DB::getResult($db,"Emagine\\Produto\\Model\\LojaInfo");
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return LojaInfo[]
     */
    public function listarPorUsuario($id_usuario) {
        $query = $this->query() . "
            INNER JOIN loja_usuario ON loja_usuario.id_loja = loja.id_loja
            WHERE loja_usuario.id_usuario = :id_usuario
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Produto\\Model\\LojaInfo");
    }

    /**
     * @throws Exception
     * @param float $latitude
     * @param float $longitude
     * @param int $raio
     * @param int $id_seguimento
     * @return LojaInfo[]
     */
    public function buscarPorPosicao($latitude, $longitude, $raio, $id_seguimento) {
        $query = $this->queryBusca() . "
            WHERE loja.cod_situacao = :cod_situacao
        ";
        if ($raio > 0) {
            $query .= " 
                AND ROUND(
                  (
                    (
                      ACOS(
                        SIN(loja.latitude * PI() / 180) * SIN(:latitude1 * PI() / 180) + 
                        COS(loja.latitude * PI() / 180) * COS(:latitude2 * PI() / 180) * 
                        COS((loja.longitude - :longitude1) * PI() / 180)
                      ) * 180 / PI()
                    ) * 60 * 1.1515
                  ) * 1.609344
                ) <= :raio 
            ";
        }
        if ($id_seguimento > 0) {
            $query .= " AND loja.id_seguimento = :id_seguimento ";
        }
        $query .= " ORDER BY distancia ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":latitude1", $latitude);
        $db->bindValue(":latitude2", $latitude);
        $db->bindValue(":longitude1", $longitude);
        if ($raio > 0) {
            $db->bindValue(":raio", $raio, PDO::PARAM_INT);
        }
        if ($id_seguimento > 0) {
            $db->bindValue(":id_seguimento", $id_seguimento, PDO::PARAM_INT);
        }
        $db->bindValue(":cod_situacao", LojaInfo::ATIVO);
        return DB::getResult($db,"Emagine\\Produto\\Model\\LojaInfo");
    }

    /**
     * @throws Exception
     * @param string $palavraChave
     * @return LojaInfo[]
     */
    public function buscarPorPalavra($palavraChave) {
        $query = $this->query() . "
		    WHERE loja.cod_situacao = :cod_situacao
		    AND loja.nome LIKE :palavra_chave
		    ORDER BY loja.nome
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":cod_situacao", LojaInfo::ATIVO, PDO::PARAM_INT);
        $db->bindValue(":palavra_chave", "%" . $palavraChave . "%");
        return DB::getResult($db,"Emagine\\Produto\\Model\\LojaInfo");
    }

	/**
     * @throws Exception
	 * @param int $id_loja
	 * @return LojaInfo
	 */
	public function pegar($id_loja) {
		$query = $this->query() . "
			WHERE loja.id_loja = :id_loja
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Produto\\Model\\LojaInfo");
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int $id_loja
     * @return LojaInfo
     */
    public function pegarPorUsuario($id_usuario, $id_loja) {
        $query = $this->query() . "
            INNER JOIN loja_usuario ON loja_usuario.id_loja = loja.id_loja
            WHERE loja_usuario.id_loja = :id_loja
            AND loja_usuario.id_usuario = :id_usuario
            LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\LojaInfo");
    }

    /**
     * @throws Exception
     * @param string $slug
     * @return LojaInfo
     */
    public function pegarPorSlug($slug) {
        $query = $this->query() . "
			WHERE loja.slug = :slug
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":slug", $slug);
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\LojaInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param LojaInfo $loja
	 */
	public function preencherCampo(PDOStatement $db, LojaInfo $loja) {
	    if ($loja->getIdSeguimento() > 0) {
            $db->bindValue(":id_seguimento", $loja->getIdSeguimento(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_seguimento", null);
        }
        $db->bindValue(":email", $loja->getEmail());
		$db->bindValue(":nome", $loja->getNome());
        $db->bindValue(":cnpj", $loja->getCnpj());
        $db->bindValue(":foto", $loja->getFoto());
        $db->bindValue(":slug", $loja->getSlug());
		$db->bindValue(":descricao", $loja->getDescricao());
        $db->bindValue(":cep", $loja->getCep());
        $db->bindValue(":logradouro", $loja->getLogradouro());
        $db->bindValue(":complemento", $loja->getComplemento());
        $db->bindValue(":numero", $loja->getNumero());
        $db->bindValue(":bairro", $loja->getBairro());
        $db->bindValue(":cidade", $loja->getCidade());
        $db->bindValue(":uf", $loja->getUf());
        $latitude = number_format($loja->getLatitude(), 6, ".", "");
        $db->bindValue(":latitude", $latitude);
        $longitude = number_format($loja->getLongitude(), 6, ".", "");
        $db->bindValue(":longitude", $longitude);
        $db->bindValue(":usa_retirar", $loja->getUsaRetirar(), PDO::PARAM_BOOL);
        $db->bindValue(":usa_retirada_mapeada", $loja->getUsaRetiradaMapeada(), PDO::PARAM_BOOL);
        $db->bindValue(":controle_estoque", $loja->getControleEstoque(), PDO::PARAM_BOOL);
        $db->bindValue(":estoque_minimo", $loja->getEstoqueMinimo(), PDO::PARAM_INT);
        $db->bindValue(":valor_minimo", number_format($loja->getValorMinimo(), 2, ".", ""));
        $db->bindValue(":usa_entregar", $loja->getUsaEntregar(), PDO::PARAM_BOOL);
        $db->bindValue(":usa_gateway", $loja->getUsaGateway(), PDO::PARAM_BOOL);
        $db->bindValue(":cod_gateway", $loja->getCodGateway());
        $db->bindValue(":aceita_credito_online", $loja->getAceitaCreditoOnline(),PDO::PARAM_BOOL);
        $db->bindValue(":aceita_debito_online", $loja->getAceitaDebitoOnline(),PDO::PARAM_BOOL);
        $db->bindValue(":aceita_boleto", $loja->getAceitaBoleto(),PDO::PARAM_BOOL);
        $db->bindValue(":aceita_dinheiro", $loja->getAceitaDinheiro(),PDO::PARAM_BOOL);
        $db->bindValue(":aceita_cartao_offline", $loja->getAceitaCartaoOffline(),PDO::PARAM_BOOL);
		$db->bindValue(":cod_situacao", $loja->getCodSituacao(), PDO::PARAM_INT);
        $db->bindValue(":mensagem_retirada", $loja->getMensagemRetirada());
	}

	/**
     * @throws Exception
	 * @param LojaInfo $loja
	 * @return int
	 */
	public function inserir($loja) {
		$query = "
			INSERT INTO loja (
			    id_seguimento,
			    email,
				nome,
				cnpj,
				foto,
				slug,
				descricao,
				cep,
				logradouro,
				complemento,
				numero,
				bairro,
				cidade,
				uf,
				latitude,
				longitude,
				usa_retirar,
				usa_retirada_mapeada,
				usa_entregar,
				controle_estoque,
				estoque_minimo,
				valor_minimo,
				usa_gateway,
    			cod_gateway,
    			aceita_credito_online,
    			aceita_debito_online,
    			aceita_boleto,
    			aceita_dinheiro,
    			aceita_cartao_offline,
				cod_situacao,
			    mensagem_retirada
			) VALUES (
			    :id_seguimento,
			    :email,
				:nome,
				:cnpj,
				:foto,
				:slug,
				:descricao,
				:cep,
				:logradouro,
				:complemento,
				:numero,
				:bairro,
				:cidade,
				:uf,
				:latitude,
				:longitude,
				:usa_retirar,
				:usa_retirada_mapeada,
				:usa_entregar,
				:controle_estoque,
				:estoque_minimo,
				:valor_minimo,
				:usa_gateway,
    			:cod_gateway,
    			:aceita_credito_online,
    			:aceita_debito_online,
    			:aceita_boleto,
    			:aceita_dinheiro,
    			:aceita_cartao_offline,
				:cod_situacao,
			    :mensagem_retirada
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $loja);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param LojaInfo $loja
	 */
	public function alterar($loja) {
		$query = "
			UPDATE loja SET
			    id_seguimento = :id_seguimento, 
			    email = :email,
				nome = :nome,
				cnpj = :cnpj,
				slug = :slug,
				foto = :foto,
				descricao = :descricao,
				cep = :cep,
				logradouro = :logradouro,
				complemento = :complemento,
				numero = :numero,
				bairro = :bairro,
				cidade = :cidade,
				uf = :uf,
				latitude = :latitude,
				longitude = :longitude,
				usa_retirar = :usa_retirar,
				usa_retirada_mapeada = :usa_retirada_mapeada,
				usa_entregar = :usa_entregar,
				controle_estoque = :controle_estoque,
				estoque_minimo = :estoque_minimo,
				valor_minimo = :valor_minimo,
				usa_gateway = :usa_gateway,
    			cod_gateway = :cod_gateway,
    			aceita_credito_online = :aceita_credito_online,
    			aceita_debito_online = :aceita_debito_online,
    			aceita_boleto = :aceita_boleto,
    			aceita_dinheiro = :aceita_dinheiro,
    			aceita_cartao_offline = :aceita_cartao_offline,
				cod_situacao = :cod_situacao,
			    mensagem_retirada = :mensagem_retirada
			WHERE loja.id_loja = :id_loja
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $loja);
		$db->bindValue(":id_loja", $loja->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_loja
	 */
	public function excluir($id_loja) {
		$query = "
			DELETE FROM loja
			WHERE id_loja = :id_loja
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
		$db->execute();
	}
}

