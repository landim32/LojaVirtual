using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloLabel : EstiloBase
    {
        public int FontSize { get; set; }
        public string FontFamily { get; set; }
        public Color TextColor { get; set; }
        public FontAttributes FontAttributes { get; set; }
        public LineBreakMode LineBreakMode { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(Label));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Label.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            if (FontSize > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Label.FontSizeProperty,
                    Value = FontSize
                });
            }
            if (!string.IsNullOrEmpty(FontFamily))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Label.FontFamilyProperty,
                    Value = FontFamily
                });
            }
            if (TextColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Label.TextColorProperty,
                    Value = TextColor
                });
            }
            if (FontAttributes != FontAttributes.None)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Label.FontAttributesProperty,
                    Value = FontAttributes
                });
            }
            if (LineBreakMode != default(LineBreakMode))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = Label.LineBreakModeProperty,
                    Value = LineBreakMode
                });
            }
            return estilo;
        }
    }
}
