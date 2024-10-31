using Radar.BLL;

namespace Radar.Factory
{
    public static class GastoFactory
    {
        private static GastoBLL _Gasto;

        public static GastoBLL create() {
            if (_Gasto == null)
                _Gasto = new GastoBLL();
            return _Gasto;
        }
    }
}
