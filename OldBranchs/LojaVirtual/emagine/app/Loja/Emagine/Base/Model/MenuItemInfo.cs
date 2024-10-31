using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Base.Model
{
    public class MenuItemInfo
    {
        public string Titulo { get; set; }
        public string Icone { get; set; }
        public string IconeFA { get; set; }
        public MenuEventHandler aoClicar { get; set; }

        public string TituloAbreviado
        {
            get
            {
                return Titulo;
            }
        }

        public bool ImagemExibe {
            get {
                return !string.IsNullOrEmpty(Icone);
            }
        }

        public bool IconeFAExibe
        {
            get
            {
                return !string.IsNullOrEmpty(IconeFA);
            }
        }
    }

    public delegate void MenuEventHandler(object sender, EventArgs e);
}
