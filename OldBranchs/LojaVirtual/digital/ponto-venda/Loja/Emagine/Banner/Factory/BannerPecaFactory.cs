using Emagine.Banner.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Banner.Factory
{
    public static class BannerPecaFactory
    {
        private static BannerPecaBLL _peca;

        public static BannerPecaBLL create()
        {
            if (_peca == null)
            {
                _peca = new BannerPecaBLL();
            }
            return _peca;
        }

    }
}
