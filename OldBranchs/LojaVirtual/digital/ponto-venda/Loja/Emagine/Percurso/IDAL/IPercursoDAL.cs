using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.IDAL
{
    public interface IPercursoDAL
    {
        IList<PercursoInfo> listar();
		IList<PercursoInfo> listarEnderecoNulo();
		IList<PercursoInfo> listarPercurso(int id);
        PercursoInfo pegar(int idPercurso);
        int gravar(PercursoInfo percurso);
        void excluir(int id);
    }
}
