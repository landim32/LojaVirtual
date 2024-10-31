using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public abstract class EstiloBase
    {
        public EstiloBase() {
            BackgroundColor = Color.Transparent;
        }

        public Color BackgroundColor { get; set; }

        public abstract Style gerar();
    }
}
