using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloStackLayout : EstiloBase
    {
        public override Style gerar()
        {
            var estilo = new Style(typeof(StackLayout));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = StackLayout.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            return estilo;
        }
    }
}
