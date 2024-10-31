using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.IBLL
{
    public interface ILojaBLL
    {
        bool podeMudarLoja();
        Task<IList<LojaInfo>> listar(int? idUsuario = null);
        Task<IList<LojaInfo>> buscar(double latitude, double longitude);
        void gravarAtual(LojaInfo loja);
        Task limparAtual();
        LojaInfo pegarAtual();
    }
}
