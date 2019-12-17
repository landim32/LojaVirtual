using Emagine.CRM.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Emagine.CRM.Factory
{
    public static class AtendimentoFactory
    {
        private static AtendimentoBLL _Atendimento;

        public static AtendimentoBLL create() {
            if (_Atendimento == null) {
                _Atendimento = new AtendimentoBLL();
            }
            return _Atendimento;
        }
    }
}
