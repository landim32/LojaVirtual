<?php
namespace Emagine\Log\Model;

use Emagine\Log\Factory\LogFactory;
use stdClass;
use JsonSerializable;

class LogInfo implements JsonSerializable
{
    const VISUALIZAR_LOG = "vizualizar-log";

    const ERRO = 0;
    const AVISO = 1;
    const INFORMACAO = 2;

    private $id_log;
    private $id_usuario;
    private $id_loja;
    private $cod_tipo;
    private $data_inclusao;
    private $nome;
    private $titulo;
    private $descricao;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_log;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value) {
        $this->id_log = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setIdUsuario($value) {
        $this->id_usuario = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdLoja() {
        return $this->id_loja;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setIdLoja($value) {
        $this->id_loja = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodTipo() {
        return $this->cod_tipo;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setCodTipo($value) {
        $this->cod_tipo = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataInclusao() {
        return $this->data_inclusao;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDataInclusao($value) {
        $this->data_inclusao = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setNome($value) {
        $this->nome = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTitulo($value) {
        $this->titulo = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDescricao($value) {
        $this->descricao = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataInclusaoStr() {
        //return humanizeDateDiff(time(), strtotime($this->getDataInclusao()));
        return date("d/m/Y h:i:s", strtotime($this->getDataInclusao()));
    }

    /**
     * @return string
     */
    public function getTipo() {
        $regraLog = LogFactory::create();
        $tipos = $regraLog->listarTipo();
        if (array_key_exists($this->getCodTipo(), $tipos)) {
            return $tipos[$this->getCodTipo()];
        }
        return "";
    }

    /**
     * @return string
     */
    public function getClasse() {
        switch ($this->getCodTipo()) {
            case LogInfo::INFORMACAO:
                $classe = "info";
                break;
            case LogInfo::AVISO:
                $classe = "warning";
                break;
            default:
                $classe = "danger";
                break;
        }
        return $classe;
    }

    /**
     * @return string
     */
    public function getIcone() {
        switch ($this->getCodTipo()) {
            case LogInfo::INFORMACAO:
                $icone = "fa-question-circle";
                break;
            case LogInfo::AVISO:
                $icone = "fa fa-fw fa-warning";
                break;
            default:
                $icone = "fa fa-fw fa-ban";
                break;
        }
        return $icone;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $log = new stdClass();
        $log->id_log = $this->getId();
        $log->id_usuario = $this->getIdUsuario();
        $log->id_loja = $this->getIdLoja();
        $log->cod_tipo = $this->getCodTipo();
        $log->data_inclusao = $this->getDataInclusao();
        $log->nome = $this->getNome();
        $log->titulo = $this->getTitulo();
        $log->descricao = $this->getDescricao();
        return $log;
    }

    /**
     * @param stdClass $value
     * @return LogInfo
     */
    public static function fromJson($value) {
        $log = new LogInfo();
        $log->setId($value->id_log);
        $log->setIdUsuario($value->id_usuario);
        $log->setIdLoja($value->id_loja);
        $log->setCodTipo($value->cod_tipo);
        $log->setDataInclusao($value->data_inclusao);
        $log->setNome($value->nome);
        $log->setTitulo($value->titulo);
        $log->setDescricao($value->descricao);
        return $log;
    }

}