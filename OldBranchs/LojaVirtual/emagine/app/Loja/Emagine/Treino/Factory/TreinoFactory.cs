using Emagine.Treino.BLL;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Treino.Factory
{
    public static class TreinoFactory
    {
        private static TreinoBLL _treino = null;

        public static TreinoBLL create()
        {
            if (_treino == null) {
                _treino = new TreinoBLL();
            }
            return _treino;
        }
    }
}
