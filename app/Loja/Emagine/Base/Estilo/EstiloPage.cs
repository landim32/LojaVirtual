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
    public class EstiloPage : EstiloBase
    {
        public string BackgroundImage { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(ContentPage));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = ContentPage.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            if (!string.IsNullOrEmpty(BackgroundImage))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = ContentPage.BackgroundImageProperty,
                    Value = BackgroundImage
                });
            }
            return estilo;
        }
    }
}
