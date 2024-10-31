using Emagine.Treino.DALFactory;
using Emagine.Treino.IDAL;
using Emagine.Treino.Model;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Treino.BLL
{
    public class TreinoBLL
    {
        private ITreinoDAL _db;

        public TreinoBLL() {
            _db = TreinoDALFactory.create();
        }

        public IList<TreinoInfo> listar() {
            return _db.listar();
        }

        public TreinoInfo pegar(int id) {
            return _db.pegar(id);
        }

        public int inserir(TreinoInfo treino) {
            return _db.inserir(treino);
        }

        public void alterar(TreinoInfo treino) {
            _db.alterar(treino);
        }

        public void excluir(int id) {
            _db.excluir(id);
        }
    }
}
