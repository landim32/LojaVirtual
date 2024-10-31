<?php
namespace Emagine\Pedido\DAL;

use Emagine\Pedido\Model\PedidoEstatisticaInfo;
use Emagine\Pedido\Model\ProdutoVendidoInfo;
use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Pedido\Model\PedidoInfo;

/**
 * Class PedidoDAL
 * @package EmaginePedido\DAL
 * @tablename pedido
 * @author EmagineCRUD
 */
class PedidoDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				pedido.id_pedido,
				pedido.id_loja,
				pedido.id_usuario,
				pedido.id_pagamento,
				pedido.data_inclusao,
				pedido.ultima_alteracao,
				pedido.cod_entrega,
				pedido.cod_pagamento,
				pedido.cod_situacao,
				pedido.nota,
				pedido.valor_frete,
				pedido.troco_para,
				pedido.cep,
				pedido.logradouro,
				pedido.complemento,
				pedido.numero,
				pedido.bairro,
				pedido.cidade,
				pedido.uf,
			    pedido.dia_entrega,
				pedido.horario_entrega,
				pedido.latitude,
				pedido.longitude,
				pedido.tempo,
				pedido.tempo_str,
				pedido.distancia,
				pedido.distancia_str,
				pedido.observacao,
				pedido.comentario
			FROM pedido
		";
	}

	/**
     * @throws Exception
     * @param int $id_loja
     * @param int $id_usuario
     * @param int $cod_entrega
     * @param int[] $situacoes
     * @param string $dataInicio
     * @param string $dataFim
	 * @return PedidoInfo[]
	 */
	public function listar($id_loja = 0, $id_usuario = 0, $cod_entrega = 0, $situacoes = array(), $dataInicio = null, $dataFim = null) {
        $query = $this->query() . "
			WHERE (1=1)
		";
        if ($id_loja > 0) {
            $query .= " AND pedido.id_loja = :id_loja ";
        }
        if ($id_usuario > 0) {
            $query .= " AND pedido.id_usuario = :id_usuario ";
        }
        if ($cod_entrega > 0) {
            $query .= " AND pedido.cod_entrega = :cod_entrega ";
        }
        if (count($situacoes) > 0) {
            $query .= " AND pedido.cod_situacao IN (" . implode(", ", $situacoes) . ") ";
        }
        if (!is_null($dataInicio) && !is_null($dataFim)) {
            $query .= " AND pedido.ultima_alteracao BETWEEN :data_ini AND :data_fim ";
        }
        elseif (!is_null($dataInicio)) {
            $query .= " AND pedido.ultima_alteracao >= :data_ini ";
        }
        elseif (!is_null($dataFim)) {
            $query .= " AND pedido.ultima_alteracao <= :data_fim ";
        }
		$db = DB::getDB()->prepare($query);
        if ($id_loja > 0) {
            $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        }
        if ($id_usuario > 0) {
            $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }
        if ($cod_entrega > 0) {
            $db->bindValue(":cod_entrega", $cod_entrega, PDO::PARAM_INT);
        }
        if (!is_null($dataInicio) && !is_null($dataFim)) {
            $data_ini = date("Y-m-d", strtotime($dataInicio)) . " 00:00:00";
            $data_fim = date("Y-m-d", strtotime($dataFim)) . " 23:59:59";
            $db->bindValue(":data_ini", $data_ini);
            $db->bindValue(":data_fim", $data_fim);
        }
        elseif (!is_null($dataInicio)) {
            $data_ini = date("Y-m-d", strtotime($dataInicio)) . " 00:00:00";
            $db->bindValue(":data_ini", $data_ini);
        }
        elseif (!is_null($dataFim)) {
            $data_fim = date("Y-m-d", strtotime($dataFim)) . " 23:59:59";
            $db->bindValue(":data_fim", $data_fim);
        }
		return DB::getResult($db,"Emagine\\Pedido\\Model\\PedidoInfo");
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @param int $id_usuario
     * @param int[] $situacoes
     * @return PedidoInfo[]
     */
    public function listarAvaliacao($id_loja, $id_usuario, $situacoes) {
        $query = $this->query() . "
			WHERE (pedido.nota > 0)
		";
        if ($id_loja > 0) {
            $query .= " AND pedido.id_loja = :id_loja ";
        }
        if ($id_usuario > 0) {
            $query .= " AND pedido.id_usuario = :id_usuario ";
        }
        if (count($situacoes) > 0) {
            $query .= " AND pedido.cod_situacao IN (" . implode(", ", $situacoes) . ") ";
        }
        $db = DB::getDB()->prepare($query);
        if ($id_loja > 0) {
            $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        }
        if ($id_usuario > 0) {
            $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }
        return DB::getResult($db,"Emagine\\Pedido\\Model\\PedidoInfo");
    }

	/**
     * @throws Exception
	 * @param int $id_pedido
	 * @return PedidoInfo
	 */
	public function pegar($id_pedido) {
		$query = $this->query() . "
			WHERE pedido.id_pedido = :id_pedido
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_pedido", $id_pedido, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Pedido\\Model\\PedidoInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param PedidoInfo $pedido
	 */
	public function preencherCampo(PDOStatement $db, PedidoInfo $pedido) {
        $db->bindValue(":id_pagamento", $pedido->getIdPagamento(), PDO::PARAM_INT);
        $db->bindValue(":cod_entrega", $pedido->getCodEntrega(), PDO::PARAM_INT);
		$db->bindValue(":cod_pagamento", $pedido->getCodPagamento(), PDO::PARAM_INT);
        $db->bindValue(":nota", $pedido->getNota(), PDO::PARAM_INT);
		$db->bindValue(":valor_frete", $pedido->getValorFrete());
        $db->bindValue(":troco_para", $pedido->getTrocoPara());
		$db->bindValue(":cep", $pedido->getCep());
		$db->bindValue(":logradouro", $pedido->getLogradouro());
		$db->bindValue(":complemento", $pedido->getComplemento());
		$db->bindValue(":numero", $pedido->getNumero());
		$db->bindValue(":bairro", $pedido->getBairro());
		$db->bindValue(":cidade", $pedido->getCidade());
		$db->bindValue(":uf", $pedido->getUf());
        $db->bindValue(":dia_entrega", $pedido->getDiaEntrega());
        $db->bindValue(":horario_entrega", $pedido->getHorarioEntrega());
        $db->bindValue(":latitude", $pedido->getLatitude());
        $db->bindValue(":longitude", $pedido->getLongitude());
        $db->bindValue(":tempo", $pedido->getTempo(), PDO::PARAM_INT);
        $db->bindValue(":tempo_str", $pedido->getTempoStr());
        $db->bindValue(":distancia", $pedido->getDistancia(), PDO::PARAM_INT);
        $db->bindValue(":distancia_str", $pedido->getDistanciaStr());
        $db->bindValue(":observacao", $pedido->getObservacao());
        $db->bindValue(":comentario", $pedido->getComentario());
	}

	/**
     * @throws Exception
	 * @param PedidoInfo $pedido
	 * @return int
	 */
	public function inserir($pedido) {
		$query = "
			INSERT INTO pedido (
			    id_loja,
				id_usuario,
				id_pagamento,
				data_inclusao,
				ultima_alteracao,
				cod_entrega,
				cod_pagamento,
				cod_situacao,
				nota,
				valor_frete,
				troco_para,
				cep,
				logradouro,
				complemento,
				numero,
				bairro,
				cidade,
				uf,
			    dia_entrega,
				horario_entrega,
				latitude,
				longitude,
				tempo,
				tempo_str,
				distancia,
				distancia_str,
				observacao,
				comentario
			) VALUES (
                :id_loja,
				:id_usuario,
				:id_pagamento,
				NOW(),
				NOW(),
				:cod_entrega,
				:cod_pagamento,
				:cod_situacao,
				:nota,
				:valor_frete,
				:troco_para,
				:cep,
				:logradouro,
				:complemento,
				:numero,
				:bairro,
				:cidade,
				:uf,
			    :dia_entrega,
				:horario_entrega,
				:latitude,
				:longitude,
				:tempo,
				:tempo_str,
				:distancia,
				:distancia_str,
				:observacao,
				:comentario
			)
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $pedido->getIdUsuario(), PDO::PARAM_INT);
        $db->bindValue(":id_loja", $pedido->getIdLoja(), PDO::PARAM_INT);
        $db->bindValue(":cod_situacao", $pedido->getCodSituacao(), PDO::PARAM_INT);
		$this->preencherCampo($db, $pedido);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param PedidoInfo $pedido
	 */
	public function alterar($pedido) {
		$query = "
			UPDATE pedido SET
			    id_pagamento = :id_pagamento,
			    ultima_alteracao = NOW(),
			    cod_entrega = :cod_entrega, 
				cod_pagamento = :cod_pagamento,
				nota = :nota,
				valor_frete = :valor_frete,
				troco_para = :troco_para,
				cep = :cep,
				logradouro = :logradouro,
				complemento = :complemento,
				numero = :numero,
				bairro = :bairro,
				cidade = :cidade,
				uf = :uf,
			    dia_entrega = :dia_entrega,
				horario_entrega = :horario_entrega,
				latitude = :latitude,
				longitude = :longitude,
				tempo = :tempo,
				tempo_str = :tempo_str,
				distancia = :distancia,
				distancia_str = :distancia_str,
				observacao = :observacao,
				comentario = :comentario
			WHERE pedido.id_pedido = :id_pedido
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $pedido);
		$db->bindValue(":id_pedido", $pedido->getId(), PDO::PARAM_INT);
		$db->execute();
	}

    /**
     * @param int $id_pedido
     * @param int $cod_situacao
     * @throws Exception
     */
    public function alterarSituacao($id_pedido, $cod_situacao) {
        $query = "
			UPDATE pedido SET
			    ultima_alteracao = NOW(),
				cod_situacao = :cod_situacao
			WHERE pedido.id_pedido = :id_pedido
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_pedido", $id_pedido, PDO::PARAM_INT);
        $db->bindValue(":cod_situacao", $cod_situacao, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @param int $id_pedido
     * @param float $latitude
     * @param float $longitude
     * @throws Exception
     */
    public function alterarPosicao($id_pedido, $latitude, $longitude) {
        $query = "
			UPDATE pedido SET
			    ultima_alteracao = NOW(),
				latitude = :latitude,
				longitude = :longitude
			WHERE pedido.id_pedido = :id_pedido
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_pedido", $id_pedido, PDO::PARAM_INT);
        $db->bindValue(":latitude", $latitude);
        $db->bindValue(":longitude", $longitude);
        $db->execute();
    }

    /**
     * @param int $id_loja
     * @return int
     * @throws Exception
     */
    public function pegarQuantidade($id_loja) {
        $query = "
            SELECT COUNT(*) AS 'quantidade'
            FROM pedido
			WHERE pedido.id_loja = :id_loja
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        return intval( DB::getValue($db, "quantidade") );
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $dataInicio
     * @param string $dataFim
     * @param int $limite
     * @return ProdutoVendidoInfo[]
     */
    public function listarProdutoPorVenda($id_loja, $dataInicio = null, $dataFim = null, $limite = 0) {
        $query = "
            SELECT 
                loja.id_loja,
                loja.slug AS 'loja_slug',
	            loja.nome AS 'loja',
	            produto.id_produto,
	            produto.slug AS 'produto_slug',
	            produto.nome AS 'produto',
	            CASE 
	                WHEN produto.valor_promocao > 0 THEN
                        produto.valor_promocao
                    ELSE 	                   
                        produto.valor
                END AS 'preco',  
	            COUNT(pedido.id_pedido) AS 'vendas',
	            SUM(pedido_item.quantidade) AS 'quantidade',
	            SUM(pedido_item.quantidade * CASE 
	                WHEN produto.valor_promocao > 0 THEN
                        produto.valor_promocao
                    ELSE 	                   
                        produto.valor
                END) AS 'valor_total'
	        FROM pedido_item
	        INNER JOIN pedido ON pedido.id_pedido = pedido_item.id_pedido
	        INNER JOIN produto ON produto.id_produto = pedido_item.id_produto
	        INNER JOIN loja ON loja.id_loja = produto.id_loja
	        WHERE (1=1)
	    ";
        if ($id_loja > 0) {
            $query .= " AND loja.id_loja = :id_loja ";
        }
        if (!is_null($dataInicio) && !is_null($dataFim)) {
            $query .= " AND pedido.ultima_alteracao BETWEEN :data_ini AND :data_fim ";
        }
        elseif (!is_null($dataInicio)) {
            $query .= " AND pedido.ultima_alteracao >= :data_ini ";
        }
        elseif (!is_null($dataFim)) {
            $query .= " AND pedido.ultima_alteracao <= :data_fim ";
        }
        $query .= "
            AND pedido.cod_situacao IN (" . PedidoInfo::ENTREGUE . ", " . PedidoInfo::FINALIZADO . ")
	        GROUP BY 
                loja.id_loja,
                loja.slug,
	            loja.nome,
	            produto.id_produto,
	            produto.slug,
	            produto.nome,
    	        preco
    	    ORDER BY quantidade DESC
        ";
        if ($limite > 0) {
            $query .= " LIMIT :limite";
        }
        //var_dump($query, $dataInicio, $dataFim);
        $db = DB::getDB()->prepare($query);
        if ($id_loja > 0) {
            $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        }
        if (!is_null($dataInicio) && !is_null($dataFim)) {
            $data_ini = date("Y-m-d", strtotime($dataInicio) . " 00:00:00");
            $data_fim = date("Y-m-d", strtotime($dataFim) . " 23:59:59");
            $db->bindValue(":data_ini", $data_ini);
            $db->bindValue(":data_fim", $data_fim);
        }
        elseif (!is_null($dataInicio)) {
            $data_ini = date("Y-m-d", strtotime($dataInicio) . " 00:00:00");
            $db->bindValue(":data_ini", $data_ini);
        }
        elseif (!is_null($dataFim)) {
            $data_fim = date("Y-m-d", strtotime($dataFim) . " 23:59:59");
            $db->bindValue(":data_fim", $data_fim);
        }
        if ($limite > 0) {
            $db->bindValue(":limite", $limite, PDO::PARAM_INT);
        }
        return DB::getResult($db,"Emagine\\Pedido\\Model\\ProdutoVendidoInfo");
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $dataInicio
     * @param string $dataFim
     * @return PedidoEstatisticaInfo[]
     */
    public function listarVendaPorPeriodo($id_loja, $dataInicio = null, $dataFim = null) {
        $query = "
            SELECT
                DATE_FORMAT(pedido.ultima_alteracao, '%Y-%m-%d') AS 'legenda',
                COUNT(pedido.id_pedido) AS 'valor'
            FROM pedido
            WHERE (1=1)
        ";
        if ($id_loja > 0) {
            $query .= " AND pedido.id_loja = :id_loja ";
        }
        if (!is_null($dataInicio) && !is_null($dataFim)) {
            $query .= " AND pedido.ultima_alteracao BETWEEN :data_ini AND :data_fim ";
        }
        elseif (!is_null($dataInicio)) {
            $query .= " AND pedido.ultima_alteracao >= :data_ini ";
        }
        elseif (!is_null($dataFim)) {
            $query .= " AND pedido.ultima_alteracao <= :data_fim ";
        }
        $query .= "
            AND pedido.cod_situacao IN (" . PedidoInfo::ENTREGUE . "," . PedidoInfo::FINALIZADO . ")
            GROUP BY
                DATE_FORMAT(pedido.ultima_alteracao, '%Y-%m-%d')
            ORDER BY
                DATE_FORMAT(pedido.ultima_alteracao, '%Y-%m-%d') 
	    ";
        $db = DB::getDB()->prepare($query);
        if ($id_loja > 0) {
            $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        }
        if (!is_null($dataInicio) && !is_null($dataFim)) {
            $data_ini = date("Y-m-d", strtotime($dataInicio)) . " 00:00:00";
            $data_fim = date("Y-m-d", strtotime($dataFim)) . " 23:59:59";
            $db->bindValue(":data_ini", $data_ini);
            $db->bindValue(":data_fim", $data_fim);
        }
        elseif (!is_null($dataInicio)) {
            $data_ini = date("Y-m-d", strtotime($dataInicio)) . " 00:00:00";
            $db->bindValue(":data_ini", $data_ini);
        }
        elseif (!is_null($dataFim)) {
            $data_fim = date("Y-m-d", strtotime($dataFim)) . " 23:59:59";
            $db->bindValue(":data_fim", $data_fim);
        }
        return DB::getResult($db,"Emagine\\Pedido\\Model\\PedidoEstatisticaInfo");
    }


    /**
     * @throws Exception
     * @param int $id_loja
     * @return array<string,string>
     */
    public function listarMesAtividade($id_loja) {
        $query = "
            SELECT DISTINCT
                DATE_FORMAT(pedido.ultima_alteracao, '%Y-%m-01') AS 'legenda',
                COUNT(pedido.id_pedido) AS 'valor'
            FROM pedido
            WHERE (1=1)
        ";
        if ($id_loja > 0) {
            $query .= " AND pedido.id_loja = :id_loja ";
        }
        $query .= "
            GROUP BY 
                DATE_FORMAT(pedido.ultima_alteracao, '%Y-%m-01')
            ORDER BY
                DATE_FORMAT(pedido.ultima_alteracao, '%Y-%m-01') 
	    ";
        $db = DB::getDB()->prepare($query);
        if ($id_loja > 0) {
            $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        }
        return DB::getDictionary($db, "legenda", "valor");
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @return double
     */
    public function pegarNota($id_loja) {
        $query = "
            SELECT AVG(pedido.nota) AS 'nota'
            FROM pedido
            WHERE pedido.id_loja = :id_loja
            AND pedido.nota > 0
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        return intval( DB::getValue($db, "nota") );
    }
}

