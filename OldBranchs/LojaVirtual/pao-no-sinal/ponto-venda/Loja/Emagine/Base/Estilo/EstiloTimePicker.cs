using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloTimePicker: EstiloBase
    {
        public Color CorTexto { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(TimePicker));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = TimePicker.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            if (CorTexto != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = TimePicker.TextColorProperty,
                    Value = CorTexto
                });
            }
            return estilo;
        }
    }
}
