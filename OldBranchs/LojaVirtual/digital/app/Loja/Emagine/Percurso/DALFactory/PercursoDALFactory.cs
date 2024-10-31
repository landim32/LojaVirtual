using Radar.DALSQLite;
using Radar.IDAL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.DALFactory
{
    public class PercursoDALFactory
    {
        private static IPercursoDAL _Percurso;

        public static IPercursoDAL create()
        {
            if (_Percurso == null)
                _Percurso = new PercursoDALSQLite();
            return _Percurso;
        }
    }
}
