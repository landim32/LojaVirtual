using Radar.DALSQLite;
using Radar.IDAL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.DALFactory
{
    public class GastoDALFactory
    {
        private static IGastoDAL _Gasto;

        public static IGastoDAL create()
        {
            if (_Gasto == null)
                _Gasto = new GastoDALSQLite();
            return _Gasto;
        }
    }
}
