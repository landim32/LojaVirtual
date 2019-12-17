using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloBotao: EstiloBase
    {
        public int FontSize { get; set; }
        public string FontFamily { get; set; }
        public Color TextColor { get; set; }
        public FontAttributes FontAttributes { get; set; }
        public int CornerRadius { get; set; }
        public int BorderRadius {
            get {
                return CornerRadius;
            }
            set {
                CornerRadius = value;
            }
        }

        public override Style gerar()
        {
            var estilo = new Style(typeof(Button));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Button.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            if (FontSize > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Button.FontSizeProperty,
                    Value = FontSize
                });
            }
            if (!string.IsNullOrEmpty(FontFamily))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Button.FontFamilyProperty,
                    Value = FontFamily
                });
            }
            if (TextColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Button.TextColorProperty,
                    Value = TextColor
                });
            }
            if (FontAttributes != FontAttributes.None) {
                estilo.Setters.Add(new Setter
                {
                    Property = Button.FontAttributesProperty,
                    Value = FontAttributes
                });
            }
            if (CornerRadius > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Button.BorderRadiusProperty,
                    Value = CornerRadius
                });
            }
            return estilo;
        }
    }
}
