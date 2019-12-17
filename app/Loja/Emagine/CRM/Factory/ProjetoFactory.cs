using Emagine.CRM.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Emagine.CRM.Factory
{
    public static class ProjetoFactory
    {
        private static ProjetoBLL _Projeto;

        public static ProjetoBLL create() {
            if (_Projeto == null) {
                _Projeto = new ProjetoBLL();
            }
            return _Projeto;
        }
    }
}
