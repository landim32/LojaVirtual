using Emagine.Treino.BLL;
using Emagine.Treino.DALSQLite;
using Emagine.Treino.IDAL;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Treino.DALFactory
{
    public static class TreinoDALFactory
    {
        private static ITreinoDAL _treino = null;

        public static ITreinoDAL create()
        {
            if (_treino == null) {
                _treino = new TreinoDALSQLite();
            }
            return _treino;
        }
    }
}
