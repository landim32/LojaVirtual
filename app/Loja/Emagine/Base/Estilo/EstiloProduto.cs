using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Base.Estilo
{
    public class EstiloProduto
    {
        public const string PRODUTO_FRAME = "produto-frame";
        public const string PRODUTO_FOTO = "produto-foto";
        public const string PRODUTO_TITULO = "produto-titulo";
        public const string PRODUTO_ICONE = "produto-icone";
        public const string PRODUTO_LABEL = "produto-label";
        public const string PRODUTO_DESCRICAO = "produto-descricao";
        public const string PRODUTO_VOLUME = "produto-volume";
        public const string PRODUTO_QUANTIDADE = "produto-quantidade";
        public const string PRODUTO_PRECO_VALOR = "produto-preco";
        public const string PRODUTO_PRECO_MOEDA = "produto-preco-moeda";
        public const string PRODUTO_PROMOCAO_VALOR = "produto-promocao";
        public const string PRODUTO_PROMOCAO_MOEDA = "produto-promocao-moeda";

        public EstiloFrame Frame { get; set; } = new EstiloFrame();
        public EstiloImage Foto { get; set; } = new EstiloImage();
        public EstiloLabel Titulo { get; set; } = new EstiloLabel();
        public EstiloIcon Icone { get; set; } = new EstiloIcon();
        public EstiloLabel Label { get; set; } = new EstiloLabel();
        public EstiloLabel Descricao { get; set; } = new EstiloLabel();
        public EstiloLabel Volume { get; set; } = new EstiloLabel();
        public EstiloLabel Quantidade { get; set; } = new EstiloLabel();
        public EstiloLabel PrecoMoeda { get; set; } = new EstiloLabel();
        public EstiloLabel PrecoValor { get; set; } = new EstiloLabel();
        public EstiloLabel PromocaoMoeda { get; set; } = new EstiloLabel();
        public EstiloLabel PromocaoValor { get; set; } = new EstiloLabel();
    }
}
