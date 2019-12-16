using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.IDAL
{
    public interface IPercursoPontoDAL
    {
        IList<PercursoPontoInfo> listar(int idPercurso);
        PercursoPontoInfo pegar(int id);
        int gravar(PercursoPontoInfo percurso);
        void excluir(int id);
    }
}
