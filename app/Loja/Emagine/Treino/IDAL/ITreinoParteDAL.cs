using Emagine.Treino.Model;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Treino.IDAL
{
    public interface ITreinoParteDAL
    {
        IList<TreinoParteInfo> listar(int idTreino);
        TreinoParteInfo pegar(int id);
        int inserir(TreinoParteInfo treino);
        void alterar(TreinoParteInfo treino);
        void excluir(int id);
    }
}
