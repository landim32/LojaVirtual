using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloImage : EstiloBase
    {
        public double WidthRequest { get; set; }
        public double HeightRequest { get; set; }
        public Aspect Aspect { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(Image));

            if (WidthRequest != default(double))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Image.WidthRequestProperty,
                    Value = WidthRequest
                });
            }
            if (HeightRequest != default(double))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Image.HeightRequestProperty,
                    Value = HeightRequest
                });
            }
            if (Aspect != default(Aspect))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Image.AspectProperty,
                    Value = Aspect
                });
            }
            return estilo;
        }
    }
}
