using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Base.Estilo
{
    public class EstiloQuantidade
    {
        public const string QUANTIDADE_FUNDO = "quantidade-fundo";
        public const string QUANTIDADE_TEXTO = "quantidade-texto";
        public const string QUANTIDADE_REMOVER_BOTAO = "quantidade-remover-botao";
        public const string QUANTIDADE_REMOVER_ICONE = "quantidade-remover-icone";
        public const string QUANTIDADE_ADICIONAR_BOTAO = "quantidade-adicionar-botao";
        public const string QUANTIDADE_ADICIONAR_ICONE = "quantidade-adicionar-icone";

        public EstiloFrame Fundo { get; set; } = new EstiloFrame();
        public EstiloLabel QuantidadeTexto { get; set; } = new EstiloLabel();
        public EstiloFrame RemoverBotao { get; set; } = new EstiloFrame();
        public EstiloIcon RemoverIcone { get; set; } = new EstiloIcon();
        public EstiloFrame AdicionarBotao { get; set; } = new EstiloFrame();
        public EstiloIcon AdicionarIcone { get; set; } = new EstiloIcon();
    }
}
