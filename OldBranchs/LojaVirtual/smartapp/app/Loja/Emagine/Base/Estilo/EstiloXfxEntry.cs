using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xfx;

namespace Emagine.Base.Estilo
{
    public class EstiloXfxEntry: EstiloBase
    {
        public int FontSize { get; set; }
        public string FontFamily { get; set; }
        public Color TextColor { get; set; }
        public FontAttributes FontAttributes { get; set; }
        public Thickness Margin { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(XfxEntry));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = XfxEntry.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            if (FontSize > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = XfxEntry.FontSizeProperty,
                    Value = FontSize
                });
            }
            if (!string.IsNullOrEmpty(FontFamily))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = XfxEntry.FontFamilyProperty,
                    Value = FontFamily
                });
            }
            if (TextColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = XfxEntry.TextColorProperty,
                    Value = TextColor
                });
            }
            if (FontAttributes != FontAttributes.None)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = XfxEntry.FontAttributesProperty,
                    Value = FontAttributes
                });
            }
            if (Margin != default(Thickness))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = XfxEntry.MarginProperty,
                    Value = Margin
                });
            }
            return estilo;
        }
    }
}
