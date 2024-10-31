using Emagine.Frete.BLL;
using System;

namespace Emagine.Frete.Factory
{
    public static class FreteFactory
    {
        private static FreteBLL _Frete;

        public static FreteBLL create() {
            if (_Frete == null) {
                _Frete = new FreteBLL();
            }
            return _Frete;
        }
    }
}
