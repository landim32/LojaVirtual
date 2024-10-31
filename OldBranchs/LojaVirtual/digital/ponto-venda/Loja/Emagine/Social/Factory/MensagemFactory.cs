using Emagine.Produto.BLL;
using Emagine.Social.BLL;
using Loja.Emagine.Produto.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Social.Factory
{
    public static class MensagemFactory
    {
        private static MensagemBLL _mensagem;

        public static MensagemBLL create()
        {
            if (_mensagem == null)
            {
                _mensagem = new MensagemBLL();
            }
            return _mensagem;
        }

    }
}
