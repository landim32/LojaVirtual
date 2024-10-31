using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Base.Estilo
{
    public class EstiloLoja
    {
        public const string LOJA_FRAME = "loja-frame";
        public const string LOJA_FOTO = "loja-foto";
        public const string LOJA_TITULO = "loja-titulo";
        public const string LOJA_ICONE = "loja-icone";
        public const string LOJA_ENDERECO = "loja-endereco";
        public const string LOJA_DISTANCIA = "loja-distancia";

        public EstiloFrame Frame { get; set; } = new EstiloFrame();
        public EstiloImage Foto { get; set; } = new EstiloImage();
        public EstiloLabel Titulo { get; set; } = new EstiloLabel();
        public EstiloIcon Icone { get; set; } = new EstiloIcon();
        public EstiloLabel Endereco { get; set; } = new EstiloLabel();
        public EstiloLabel Distancia { get; set; } = new EstiloLabel();
    }
}
