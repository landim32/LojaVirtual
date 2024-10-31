using Emagine.Login.BLL;
using Emagine.Login.IBLL;
using Emagine.Mapa.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Mapa.Factory
{
    public static class MapaBuscaFactory {
        private static MapaBuscaBLL _mapaBusca;

        public static MapaBuscaBLL create() {
            if (_mapaBusca == null) {
                _mapaBusca = new MapaBuscaBLL();
            }
            return _mapaBusca;
        }

    }
}
