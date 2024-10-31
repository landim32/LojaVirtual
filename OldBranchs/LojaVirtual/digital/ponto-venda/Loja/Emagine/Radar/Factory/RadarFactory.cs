using Radar.BLL;


namespace Radar.Factory
{
    public static class RadarFactory
    {
        private static RadarBLL _Radar;

        public static RadarBLL create() {
            if (_Radar == null)
                _Radar = new RadarBLL();
            return _Radar;
        }
    }
}
