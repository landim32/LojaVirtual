using Emagine.Banner.Model;
using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Events
{
    public class LojaListaEventArgs: EventArgs
    {
        public IList<BannerPecaInfo> Banners { get; set; }
        public IList<LojaInfo> Lojas { get; set; }
    }
}
