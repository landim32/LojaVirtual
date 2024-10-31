<?php
namespace Emagine\Pagamento\MundiPagg\BLL;

use stdClass;
use Exception;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

class UsuarioMundiPaggBLL
{
    const MUNDI_PAGG_ID = "MUNDI_PAGG_ID";

    /**
     * @throws Exception
     * @param UsuarioInfo $usuario
     * @return string
     */
    public function inserir(UsuarioInfo $usuario) {
        $data = new stdClass();
        $data->name = $usuario->getNome();
        $data->email = $usuario->getEmail();
        $data->code = $usuario->getId();
        if (!isNullOrEmpty($usuario->getCpfCnpj())) {
            $data->document = $usuario->getCpfCnpj();
        }
        $data->type = "individual";

        $mundiPagg = new MundiPaggBLL();
        $retorno = $mundiPagg->post("/customers", $data);
        if (isset($retorno->id)) {
            return $retorno->id;
        }
        else {
            throw new Exception("O gateway retornou sem o id do cliente.");
        }
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return string
     */
    public function pegarUsuarioId($id_usuario) {
        $regraUsuario = new UsuarioBLL();
        $usuario = $regraUsuario->pegar($id_usuario);
        $mundiPaggId = $usuario->getPreferencia(self::MUNDI_PAGG_ID);
        if (isNullOrEmpty($mundiPaggId)) {
            $mundiPaggId = $this->inserir($usuario);
            $usuario->setPreferencia(self::MUNDI_PAGG_ID, $mundiPaggId);
            $regraUsuario->alterar($usuario);
        }
        return $mundiPaggId;
    }
}