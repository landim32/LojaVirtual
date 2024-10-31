using Emagine.Produto.BLL;
using Loja.Emagine.Produto.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Factory
{
    public static class SeguimentoFactory
    {
        private static SeguimentoBLL _seguimento;

        public static SeguimentoBLL create()
        {
            if (_seguimento == null)
            {
                _seguimento = new SeguimentoBLL();
            }
            return _seguimento;
        }

    }
}
