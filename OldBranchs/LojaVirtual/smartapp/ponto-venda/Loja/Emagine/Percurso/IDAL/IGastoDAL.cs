using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.IDAL
{
    public interface IGastoDAL
    {
        IList<GastoInfo> listar(int idGasto);
        IList<GastoInfo> listar();
        GastoInfo pegar(int id);
        int gravar(GastoInfo gasto);
        void excluir(int id);
    }
}
