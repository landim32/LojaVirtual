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
    public class EstiloIcone : EstiloBase
    {
        public int Tamanho { get; set; }
        public Color Cor { get; set; }

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
            if (Tamanho > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = IconImage.IconSizeProperty,
                    Value = Tamanho
                });
            }
            if (Cor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = IconImage.IconColorProperty,
                    Value = Cor
                });
            }
            return estilo;
        }
    }
}
