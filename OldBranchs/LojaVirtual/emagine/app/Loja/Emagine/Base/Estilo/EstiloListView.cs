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
    public class EstiloListView : EstiloBase
    {
        public bool SeparatorVisibility { get; set; }
        public Color SeparatorColor { get; set; }

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
            if (SeparatorVisibility)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = ListView.SeparatorVisibilityProperty,
                    Value = SeparatorVisibility
                });
            }
            if (SeparatorColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = ListView.SeparatorColorProperty,
                    Value = SeparatorColor
                });
            }
            return estilo;
        }
    }
}
