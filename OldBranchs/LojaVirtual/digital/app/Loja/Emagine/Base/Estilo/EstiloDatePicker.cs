using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class EstiloDatePicker: EstiloBase
    {
        public Color CorTexto { get; set; }

        public override Style gerar()
        {
            var estilo = new Style(typeof(DatePicker));

            if (BackgroundColor != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = DatePicker.BackgroundColorProperty,
                    Value = BackgroundColor
                });
            }
            if (CorTexto != Color.Transparent)
            {
                estilo.Setters.Add(new Setter
                {
                    Property = DatePicker.TextColorProperty,
                    Value = CorTexto
                });
            }
            return estilo;
        }
    }
}
