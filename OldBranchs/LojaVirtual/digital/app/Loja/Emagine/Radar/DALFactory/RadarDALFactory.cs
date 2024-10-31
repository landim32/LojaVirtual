using Radar.DALSQLite;
using Radar.IDAL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.DALFactory
{
    public class RadarDALFactory
    {
        private static IRadarDAL _Radar;

        public static IRadarDAL create()
        {
            if (_Radar == null)
                _Radar = new RadarDALSQLite();
            return _Radar;
        }
    }
}
