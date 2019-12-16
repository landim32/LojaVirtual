using Emagine.Frete.BLL;
using System;

namespace Emagine.Frete.Factory
{
    public static class TipoVeiculoFactory
    {
        private static TipoVeiculoBLL _tipo;

        public static TipoVeiculoBLL create() {
            if (_tipo == null) {
                _tipo = new TipoVeiculoBLL();
            }
            return _tipo;
        }

    }
}
