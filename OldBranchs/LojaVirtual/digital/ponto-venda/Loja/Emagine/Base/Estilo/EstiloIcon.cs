using Emagine.Base.Estilo;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloIcon : EstiloBase
    {
        public int IconSize { get; set; }
        public Color IconColor { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(IconImage));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = IconImage.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            if (IconSize > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = IconImage.IconSizeProperty,
                    Value = IconSize
                });
            }
            if (IconColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = IconImage.IconColorProperty,
                    Value = IconColor
                });
            }
            return estilo;
        }
    }
}
