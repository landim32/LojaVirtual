using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Base.Estilo
{
    public class EstiloTotal
    {
        public const string TOTAL_FRAME = "total-frame";
        public const string TOTAL_LABEL = "total-label";
        public const string TOTAL_TEXTO = "total-texto";

        public EstiloFrame Frame { get; set; } = new EstiloFrame();
        public EstiloLabel Label { get; set; } = new EstiloLabel();
        public EstiloLabel Texto { get; set; } = new EstiloLabel();

    }
}
