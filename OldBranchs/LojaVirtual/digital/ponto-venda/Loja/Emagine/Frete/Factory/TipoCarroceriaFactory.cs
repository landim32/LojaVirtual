using Emagine.Frete.BLL;
using System;

namespace Emagine.Frete.Factory
{
    public static class TipoCarroceriaFactory
    {
        private static TipoCarroceriaBLL _carroceria;

        public static TipoCarroceriaBLL create() {
            if (_carroceria == null) {
                _carroceria = new TipoCarroceriaBLL();
            }
            return _carroceria;
        }

    }
}
