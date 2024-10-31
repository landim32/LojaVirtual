using Emagine.Base.Controls;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloMenuButton: EstiloBase
    {
        public int FontSize { get; set; }
        public string FontFamily { get; set; }
        public Color TextColor { get; set; }
        public FontAttributes FontAttributes { get; set; }
        public int CornerRadius { get; set; }
        public int IconSize { get; set; }
        public Thickness Padding { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(MenuButton));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = MenuButton.FrameColorProperty,
                    Value = BackgroundColor
                });
            }
            if (FontSize > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = MenuButton.FontSizeProperty,
                    Value = FontSize
                });
            }
            if (IconSize > 0)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = MenuButton.IconSizeProperty,
                    Value = IconSize
                });
            }
            if (!string.IsNullOrEmpty(FontFamily))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = MenuButton.FontFamilyProperty,
                    Value = FontFamily
                });
            }
            if (TextColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = MenuButton.TextColorProperty,
                    Value = TextColor
                });
            }
            if (FontAttributes != FontAttributes.None)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = MenuButton.FontAttributesProperty,
                    Value = FontAttributes
                });
            }
            if (Padding != default(Thickness))
            {
                estilo.Setters.Add(new Setter
                {
                    Property = MenuButton.FramePaddingProperty,
                    Value = Padding
                });
            }
            return estilo;
        }
    }
}
