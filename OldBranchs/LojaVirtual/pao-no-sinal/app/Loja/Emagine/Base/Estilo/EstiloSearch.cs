using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloSearch: EstiloBase
    {
        public int FontSize { get; set; }
        public string FontFamily { get; set; }
        public Color TextColor { get; set; }
        public FontAttributes FontAttributes { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(SearchBar));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = SearchBar.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            if (FontSize > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = SearchBar.FontSizeProperty,
                    Value = FontSize
                });
            }
            if (!string.IsNullOrEmpty(FontFamily))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = SearchBar.FontFamilyProperty,
                    Value = FontFamily
                });
            }
            if (TextColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = SearchBar.TextColorProperty,
                    Value = TextColor
                });
            }
            if (FontAttributes != FontAttributes.None)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = SearchBar.FontAttributesProperty,
                    Value = FontAttributes
                });
            }
            return estilo;
        }
    }
}
