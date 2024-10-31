using Radar.DALSQLite;
using Radar.IDAL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.DALFactory
{
    public class PercursoPontoDALFactory
    {
        private static IPercursoPontoDAL _Ponto;

        public static IPercursoPontoDAL create()
        {
            if (_Ponto == null)
                _Ponto = new PercursoPontoDALSQLite();
            return _Ponto;
        }
    }
}
