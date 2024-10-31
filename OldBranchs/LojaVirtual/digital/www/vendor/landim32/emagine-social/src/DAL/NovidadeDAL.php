<?php

namespace Emagine\Social\DAL;



class NovidadeDAL
{
    /**
     * @return string
     */
    private function queryNovidade() {
        return "
            SELECT
                imovel.id_imovel,
                imovel.id_conta,
                imovel.id_usuario,
                imovel.id_foto,
                conta.cod_servico,
                imovel.data_inclusao,
                imovel.ultima_alteracao,
                imovel_foto.foto,
                imovel.negocio,
                imovel.titulo,
                imovel.tipo_imovel,
                imovel.uf,
                imovel.cidade,
                imovel.bairro,
                imovel.quartos,
                imovel.suites,
                imovel.banheiros,
                imovel.garagens,
                imovel.preco,
                imovel.area_util,
                imovel.codigo,
                imovel.parceria,
                conta.nome as 'conta_nome',
                usuario.nome as 'usuario_nome',
                usuario.foto as 'usuario_foto'
            FROM imovel
            INNER JOIN conta ON conta.id_conta = imovel.id_conta
            LEFT JOIN imovel_foto ON imovel_foto.id_foto = imovel.id_foto
            LEFT JOIN usuario ON usuario.id_usuario = imovel.id_usuario
        ";
    }
}