using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Base.Model
{
    public class AvaliacaoInfo
    {
        public int Nota { get; set; }
        public string Comentario { get; set; }

        public AvaliacaoInfo(int nota, string comentario) {
            this.Nota = nota;
            this.Comentario = comentario;
        }

        public AvaliacaoInfo() : this(0, string.Empty) {
        }
    }
}
