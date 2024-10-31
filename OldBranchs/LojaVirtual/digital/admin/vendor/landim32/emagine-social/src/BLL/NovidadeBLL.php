<?php

namespace Emagine\Social\BLL;

use Emagine\Login\BLL\UsuarioBLL;
use Imobsync\Imovel\BLL\ImovelBLL;
use Imobsync\Imovel\Model\ImovelInfo;
use Emagine\Social\Model\NovidadeInfo;

class NovidadeBLL
{
    /**
     * @param ImovelInfo $imovel
     * @return NovidadeInfo
     */
    private function gerarNovidade($imovel) {
        $novidade = new NovidadeInfo();
        $str = "Disponibilizou para ";
        switch (strtolower($imovel->getNegocio())) {
            case 'venda':
            case 'lancamento':
                $str .= "<b>Venda</b>";
                break;
            case 'aluguel':
                $str .= "<b>Locação</b>";
                break;
            case 'temporada':
                $str .= "<b>Locação por Temporada</b>";
                break;
        }
        $str .= " o seguinte imóvel na rede de parceiros por R$ ".number_format($imovel->getPreco(), 2, ',', '.');
        $novidade->setTipo(NovidadeInfo::TIPO_IMOVEL);
        $novidade->setSubTitulo($str);
        $novidade->setTitulo($imovel->getTitulo());

        $regraImovel = new ImovelBLL();
        $novidade->setDescricao($regraImovel->gerarDescricao($imovel));

        $novidade->setIdUsuario($imovel->getIdUsuario());
        //$novidade->setIdConta($imovel->getIdConta());
        $novidade->setDataInclusao(strtotime($imovel->getDataInclusao()));
        $novidade->setUltimaAlteracao(strtotime($imovel->getUltimaAlteracao()));
        /*
        if ($imovel->getConta()->getCodServico() == 7) {
            $novidade->setImobiliaria(null);
        }
        else
            $novidade->setImobiliaria($imovel->getConta()->getNome());
        */
        if (!is_null($imovel->getUsuario())) {
            $novidade->setNome($imovel->getUsuario()->getNome());
            $novidade->setImobiliaria(null);
        }
        $novidade->setFoto($imovel->getThumbnailUrl(100, 100));
        $novidade->setUrl('imovel-view?id='.$imovel->getId());

        $novidade->setImovel($imovel);

        return $novidade;
    }


    public function listarNovidadePorStatus($id_usuario, $apartir_de = null, $limite = 5) {
        $query = "
            SELECT
                imovel.id_imovel,
                imovel.id_conta,
                imovel.id_usuario,
                imovel.titulo,
                imovel.negocio,
                imovel.tipo_imovel,
                imovel.bairro,
                imovel.cidade,
                imovel.uf,
                imovel.vencimento_exclusividade,
                conta.nome as 'conta_nome',
                usuario.nome as 'usuario_nome',
                usuario.foto as 'usuario_foto'
            FROM imovel
            INNER JOIN conta ON conta.id_conta = imovel.id_conta
            LEFT JOIN imovel_foto ON imovel_foto.id_foto = imovel.id_foto
            LEFT JOIN usuario ON usuario.id_usuario = imovel.id_usuario
            WHERE imovel.id_usuario = '".do_escape($id_usuario)."'
            AND conta.cod_situacao IN (1,6)
            AND imovel.tem_exclusividade = 1
            AND imovel.vencimento_exclusividade <= now()
        ";
        if (!is_null($apartir_de))
            $query .= " AND imovel.ultima_alteracao > '".date('Y-m-d H:i:s', $apartir_de)."' ";
        $query .= " ORDER BY imovel.ultima_alteracao DESC ";
        if ($limite > 0)
            $query .= " LIMIT ".do_escape($limite);

        $novidades = array();
        $imoveis = get_result($query);
        /** @var ImovelInfo $imovel */
        foreach ($imoveis as $imovel) {
            $novidade = new NovidadeInfo();
            $novidade->setTipo(NovidadeInfo::TIPO_EXCLUSIVIDADE);
            $novidade->setUltimaAlteracao(strtotime($imovel->getVencimentoExclusividade()));
            $str = "<strong>" . $imovel->getTitulo() . "</strong> ";
            if ($novidade->getUltimaAlteracao() > time())
                $str .= "terá seu contrato de exclusividade expirado ";
            else
                $str .= "teve seu contrato de exclusividade expirado ";
            $str .= "em ".date('d/m/Y', $novidade->getUltimaAlteracao()).'.';
            $novidade->setSubTitulo($str);
            $novidade->setTitulo($imovel->getTitulo());
            $novidade->setIdImovel($imovel->getId());
            $novidade->setIdUsuario($imovel->getIdUsuario());
            $novidade->setIdConta($imovel->getIdConta());
            if (is_null($imovel->getUsuario())) {
                $novidade->setNome($imovel->getUsuario()->getNome());
            }
            else {
                $novidade->setNome($imovel->getConta()->getNome());
                $novidade->setImobiliaria(null);
            }
            $regraImovel = new ImovelBLL();
            $imovel = $regraImovel->pegar($imovel->getId());
            $novidade->setUrl($regraImovel->getLink($imovel->getId()));
            $novidade->setImovel($imovel);

            $novidades[] = $novidade;
        }
        return $novidades;
    }

    public function listarNovidadePerfil($id_usuario, $uf, $apartir_de = null, $limite = 5) {
        if (is_null($id_usuario)) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        }
        $query = $this->queryNovidade();
        $query .= "
            WHERE imovel.id_usuario = '".do_escape($id_usuario)."'
            AND conta.cod_situacao IN (1,6)
            AND imovel.cod_situacao = 1
            AND imovel.parceria = 1
        ";
        if (!is_null($uf))
            $query .= " AND UPPER(imovel.uf) = '".strtoupper($uf)."' ";
        if (!is_null($apartir_de))
            $query .= " AND imovel.ultima_alteracao > '".date('Y-m-d H:i:s', $apartir_de)."' ";
        $query .= " ORDER BY imovel.ultima_alteracao DESC ";
        if ($limite > 0)
            $query .= " LIMIT ".do_escape($limite);
        $novidades = array();
        $imoveis = get_result($query);
        foreach ($imoveis as $imovel) {
            $novidades[] = $this->gerarNovidade($imovel);
        }
        return $novidades;
    }

    /**
     * @param int|null $id_conta
     * @param int|null $id_usuario
     * @param string|null $uf
     * @param int|null $apartir_de
     * @param int $limite
     * @return NovidadeInfo[]
     */
    public function listarNovidade($id_conta = null, $id_usuario = null, $uf = null, $apartir_de = null, $limite = 5) {
        $regraImovel = new ImovelBLL();
        $imoveis = $regraImovel->listarNovidade($id_conta, null, $id_usuario, $uf, $apartir_de, $limite);
        $novidades = array();
        foreach ($imoveis as $imovel) {
            $novidades[] = $this->gerarNovidade($imovel);
        }
        return $novidades;
    }
}