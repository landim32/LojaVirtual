using Emagine.Treino.Model;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Treino.IDAL
{
    public interface ITreinoDAL
    {
        IList<TreinoInfo> listar();
        TreinoInfo pegar(int id);
        int inserir(TreinoInfo treino);
        void alterar(TreinoInfo treino);
        void excluir(int id);
    }
}
