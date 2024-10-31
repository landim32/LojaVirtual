<?php
namespace Emagine\Social\Model;

use Exception;
use Emagine\Social\DALFactory\MensagemDALFactory;

class ContatoInfo
{
    private $id_usuario;
    private $nome;
    private $foto;
    private $mensagens = null;
    private $id_autor;

    /**
     * @return int
     */
    public function getIdAutor()
    {
        return $this->id_autor;
    }

    /**
     * @param int $value
     */
    public function setIdAutor($value)
    {
        $this->id_autor = $value;
    }

    /**
     * @return MensagemInfo[]
     */
    public function getMensagens()
    {
        return $this->mensagens;
    }

    /**
     * @param MensagemInfo[] $value
     */
    public function setMensagens($value)
    {
        $this->mensagens = $value;
    }

    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param int $value
     */
    public function setIdUsuario($value)
    {
        $this->id_usuario = $value;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $value
     */
    public function setNome($value)
    {
        $this->nome = $value;
    }

    /**
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param string $value
     */
    public function setFoto($value)
    {
        $this->foto = $value;
    }

    /**
     * @throws Exception
     * @param integer $id_usuario
     * @return MensagemInfo[]
     */
    public function listarMensagem($id_usuario)
    {
        $dal = MensagemDALFactory::create();
        $this->mensagens = $dal->listar($id_usuario);
        return $this->mensagens;
    }

    /**
     * @param MensagemInfo $mensagem
     */
    public function adicionarMensagem($mensagem)
    {
        $this->mensagens[] = $mensagem;
    }



}