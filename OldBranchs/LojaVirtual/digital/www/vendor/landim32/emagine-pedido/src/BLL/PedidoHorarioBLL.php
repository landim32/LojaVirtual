<?php
namespace Emagine\Pedido\BLL;

use Exception;
use Landim32\EasyDB\DB;
use Emagine\Pedido\DAL\PedidoHorarioDAL;
use Emagine\Pedido\Model\PedidoHorarioInfo;

/**
 * Class PedidoHorarioBLL
 * @package Emagine\Pedido\BLL
 * @tablename pedido_horario
 * @author EmagineCRUD
 */
class PedidoHorarioBLL {

	/**
     * @throws Exception
     * @param int $idLoja
	 * @return PedidoHorarioInfo[]
	 */
	public function listar($idLoja) {
		$dal = new PedidoHorarioDAL();
		return $dal->listar($idLoja);
	}

	/**
     * @throws Exception
	 * @param int $idHorario
	 * @return PedidoHorarioInfo
	 */
	public function pegar($idHorario) {
		$dal = new PedidoHorarioDAL();
		return $dal->pegar($idHorario);
	}

	/**
	 * @param string[] $postData
	 * @param PedidoHorarioInfo|null $horario
	 * @return PedidoHorarioInfo
	 */
	public function pegarDoPost($postData, $horario = null) {
		if (is_null($horario)) {
			$horario = new PedidoHorarioInfo();
		}
		if (array_key_exists("id_horario", $postData)) {
			$horario->setId(intval($postData["id_horario"]));
		}
		if (array_key_exists("id_loja", $postData)) {
			$horario->setIdLoja(intval($postData["id_loja"]));
		}
		if (array_key_exists("inicio", $postData)) {
			$horario->setInicio(intval($postData["inicio"]));
		}
		if (array_key_exists("fim", $postData)) {
			$horario->setFim(intval($postData["fim"]));
		}
		return $horario;
	}

	/**
	 * @throws Exception
	 * @param PedidoHorarioInfo $horario
	 */
	protected function validar(&$horario) {
		if (!($horario->getIdLoja() > 0)) {
			throw new Exception('Id da loja não informado.');
		}
		if (!($horario->getInicio() >= 0)) {
			throw new Exception('Preencha o inicio.');
		}
		if (!($horario->getFim() >= 0)) {
			throw new Exception('Preencha o fim.');
		}
	}

	/**
	 * @throws Exception
	 * @param PedidoHorarioInfo $horario
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($horario, $transaction = true) {
		$this->validar($horario);
		$dal = new PedidoHorarioDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$idHorario = $dal->inserir($horario);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
		return $idHorario;
	}

	/**
	 * @throws Exception
	 * @param PedidoHorarioInfo $horario
	 * @param bool $transaction
	 */
	public function alterar($horario, $transaction = true) {
		$this->validar($horario);
		$dal = new PedidoHorarioDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($horario);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}

	/**
	 * @throws Exception
	 * @param int $id_horario
	 * @param bool $transaction
	 */
	public function excluir($id_horario, $transaction = true) {
		$dal = new PedidoHorarioDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_horario);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}
	/**
     * @throws Exception
	 * @param int $idLoja
	 */
	public function limparPorIdLoja($idLoja) {
		$dal = new PedidoHorarioDAL();
		$dal->limparPorIdLoja($idLoja);
	}

    /**
     * @throws Exception
     * @param string $name
     * @param string $className
     * @param string|null $selecionado
     * @return string
     */
    public function dropdownList($name, $className = "form-control", $selecionado = null) {
        $str = "";
        $str .= "<select name='" . $name . "' class='" . $className . "'>\n";
        for ($h = 0; $h < 24; $h++) {
            for ($m = 0; $m < 60; $m = $m + 15) {
                $segundo = ($h * (60 * 60)) + ($m * 60);
                $str .= "<option value=\"" . $segundo . "\"";
                if ($segundo == $selecionado) {
                    $str .= " selected=\"selected\"";
                }
                $str .= ">" . sprintf("%02d:%02d", $h, $m) . "</option>\n";
            }
        }
        $str .= "</select>";
        return $str;
    }

}

