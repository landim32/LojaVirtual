using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloFrame : EstiloBase
    {
        public LayoutOptions HorizontalOptions { get; set; } = LayoutOptions.Start;
        public LayoutOptions VerticalOptions { get; set; } = LayoutOptions.Start;
        public double WidthRequest { get; set; }
        public double HeightRequest { get; set; }
        public int CornerRadius { get; set; }
        public Thickness Padding { get; set; }
        public Thickness Margin { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(Frame));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Frame.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            estilo.Setters.Add(new Setter
            {
                Property = Frame.HorizontalOptionsProperty,
                Value = HorizontalOptions
            });
            estilo.Setters.Add(new Setter
            {
                Property = Frame.VerticalOptionsProperty,
                Value = VerticalOptions
            });
            if (WidthRequest > 0) {
                estilo.Setters.Add(new Setter
                {
                    Property = Frame.WidthRequestProperty,
                    Value = WidthRequest
                });
            }
            if (HeightRequest > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Frame.HeightRequestProperty,
                    Value = HeightRequest
                });
            }
            if (CornerRadius > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Frame.CornerRadiusProperty,
                    Value = CornerRadius
                });
            }
            if (Padding != default(Thickness))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Frame.PaddingProperty,
                    Value = Padding
                });
            }
            if (Margin != default(Thickness))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Frame.MarginProperty,
                    Value = Margin
                });
            }
            return estilo;
        }
    }
}
