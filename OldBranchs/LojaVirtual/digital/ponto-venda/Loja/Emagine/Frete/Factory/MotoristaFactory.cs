using Emagine.Frete.BLL;
using System;

namespace Emagine.Frete.Factory
{
    public static class MotoristaFactory
    {
        private static MotoristaBLL _Motorista;

        public static Type Tipo { get; set; } = typeof(MotoristaBLL);

        public static MotoristaBLL create() {
            if (_Motorista == null) {
                _Motorista = (MotoristaBLL)Activator.CreateInstance(Tipo);
            }
            return _Motorista;
        }

    }
}
