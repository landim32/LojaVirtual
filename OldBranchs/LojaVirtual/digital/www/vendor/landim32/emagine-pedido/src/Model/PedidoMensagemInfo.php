<?php
namespace Emagine\Pedido\Model;

use stdClass;
use Exception;
use Emagine\Social\Model\MensagemInfo;

class PedidoMensagemInfo extends MensagemInfo
{
    private $id_pedido;

    /**
     * @return int
     */
    public function getIdPedido() {
        return $this->id_pedido;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setIdPedido($value) {
        $this->id_pedido = $value;
        return $this;
    }

    /**
     * @param stdClass $value
     * @return PedidoMensagemInfo
     */
    public static function fromJson($value) {
        $mensagem = new PedidoMensagemInfo();
        $mensagem->setId($value->id_mensagem);
        $mensagem->setIdUsuario($value->id_usuario);
        $mensagem->setIdAutor($value->id_autor);
        $mensagem->setDataInclusao($value->data_inclusao);
        $mensagem->setLido($value->lido);
        $mensagem->setMensagem($value->mensagem);
        $mensagem->setIdPedido($value->id_pedido);
        return $mensagem;
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $mensagem = parent::jsonSerialize();
        $mensagem->id_pedido = $this->getIdPedido();
        return $mensagem;
    }

}