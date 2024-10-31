using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloBoxView : EstiloBase
    {
        public LayoutOptions HorizontalOptions { get; set; } = LayoutOptions.Start;
        public LayoutOptions VerticalOptions { get; set; } = LayoutOptions.Start;
        public double WidthRequest { get; set; }
        public double HeightRequest { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(BoxView));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = BoxView.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            estilo.Setters.Add(new Setter
            {
                Property = BoxView.HorizontalOptionsProperty,
                Value = HorizontalOptions
            });
            estilo.Setters.Add(new Setter
            {
                Property = BoxView.VerticalOptionsProperty,
                Value = VerticalOptions
            });
            if (WidthRequest > 0) {
                estilo.Setters.Add(new Setter
                {
                    Property = BoxView.WidthProperty,
                    Value = WidthRequest
                });
            }
            if (HeightRequest > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = BoxView.HeightProperty,
                    Value = HeightRequest
                });
            }
            return estilo;
        }
    }
}
