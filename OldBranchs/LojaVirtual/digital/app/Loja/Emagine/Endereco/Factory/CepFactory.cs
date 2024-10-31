using Emagine.Endereco.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Factory
{
    public static class CepFactory
    {
        private static CepBLL _Cep;

        public static CepBLL create()
        {
            if (_Cep == null)
            {
                _Cep = new CepBLL();
            }
            return _Cep;
        }

    }
}
