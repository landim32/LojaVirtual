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
        int RaioBusca { get; set; }
        bool podeMudarLoja();
        Task<IList<LojaInfo>> listar(int? idUsuario = null);
        Task<IList<LojaInfo>> buscar(double latitude, double longitude, int raio, int idSeguimento = 0);
        Task<LojaInfo> pegar(int idLoja);
        Task gravarAtual(LojaInfo loja);
        Task limparAtual();
        LojaInfo pegarAtual();
    }
}
