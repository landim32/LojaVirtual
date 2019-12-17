using Emagine.Base.Estilo;
using Plugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloLista : EstiloBase
    {
        public SeparatorVisibility UsaSeparador { get; set; }
        public Color CorSeparador { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(ListView));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = ListView.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            if (UsaSeparador != SeparatorVisibility.Default)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = ListView.SeparatorVisibilityProperty,
                    Value = UsaSeparador
                });
            }
            if (CorSeparador != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = ListView.SeparatorColorProperty,
                    Value = CorSeparador
                });
            }
            return estilo;
        }
    }
}
