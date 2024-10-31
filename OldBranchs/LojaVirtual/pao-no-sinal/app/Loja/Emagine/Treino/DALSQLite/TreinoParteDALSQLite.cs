using Emagine.IBLL;
using Emagine.Treino.IDAL;
using Emagine.Treino.Model;
using SQLite;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Treino.DALSQLite
{
    public class TreinoParteDALSQLite: ITreinoParteDAL
    {
        SQLiteConnection database;
        static object locker = new object();

        public TreinoParteDALSQLite()
        {
            database = DependencyService.Get<ISQLite>().GetConnection();
            database.CreateTable<TreinoParteInfo>();
        }

        public IList<TreinoParteInfo> listar(int idTreino)
        {
            lock (locker)
            {
                return (
                    from i in database.Table<TreinoParteInfo>()
                    where i.IdTreino == idTreino
                    orderby i.Id
                    select i
                ).ToList();
            }
        }

        public TreinoParteInfo pegar(int id)
        {
            lock (locker)
            {
                return database.Table<TreinoParteInfo>().FirstOrDefault(x => x.Id == id);
            }
        }

        public int inserir(TreinoParteInfo treino) {
            lock (locker)
            {
                return database.Insert(treino);
            }
        }

        public void alterar(TreinoParteInfo treino) {
            lock (locker)
            {
                database.Update(treino);
            }
        }

        public void excluir(int id) {
            database.Delete<TreinoParteInfo>(id);
        }
    }
}
