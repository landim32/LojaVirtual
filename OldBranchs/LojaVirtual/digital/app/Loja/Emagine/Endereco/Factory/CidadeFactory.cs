using Emagine.Endereco.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Factory
{
    public static class CidadeFactory
    {
        private static CidadeBLL _Cidade;

        public static CidadeBLL create()
        {
            if (_Cidade == null)
            {
                _Cidade = new CidadeBLL();
            }
            return _Cidade;
        }

    }
}
