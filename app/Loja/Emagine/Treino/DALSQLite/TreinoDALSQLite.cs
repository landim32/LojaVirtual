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
    public class TreinoDALSQLite: ITreinoDAL
    {
        SQLiteConnection database;
        static object locker = new object();

        public TreinoDALSQLite()
        {
            database = DependencyService.Get<ISQLite>().GetConnection();
            database.CreateTable<TreinoInfo>();
        }

        public IList<TreinoInfo> listar()
        {
            lock (locker)
            {
                return (
                    from i in database.Table<TreinoInfo>()
                    orderby i.Nome
                    select i
                ).ToList();
            }
        }

        public TreinoInfo pegar(int id)
        {
            lock (locker)
            {
                return database.Table<TreinoInfo>().FirstOrDefault(x => x.Id == id);
            }
        }

        public int inserir(TreinoInfo treino) {
            lock (locker)
            {
                return database.Insert(treino);
            }
        }

        public void alterar(TreinoInfo treino) {
            lock (locker)
            {
                database.Update(treino);
            }
        }

        public void excluir(int id) {
            database.Delete<TreinoInfo>(id);
        }
    }
}
