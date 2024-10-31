using Radar.IDAL;
using Radar.Model;
using SQLite;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.DALSQLite
{
    public class PercursoPontoDALSQLite: IPercursoPontoDAL
    {
        SQLiteConnection database;
        static object locker = new object();

        public PercursoPontoDALSQLite()
        {
            database = DependencyService.Get<ISQLite>().GetConnection();
            database.CreateTable<PercursoPontoInfo>();
        }

        public IList<PercursoPontoInfo> listar(int idPercurso)
        {
            lock (locker)
            {
                return (
                    from i in database.Table<PercursoPontoInfo>()
                    where i.IdPercurso == idPercurso
                    select i
                ).ToList();
            }
        }

        public PercursoPontoInfo pegar(int id)
        {
            lock (locker)
            {
                return database.Table<PercursoPontoInfo>().FirstOrDefault(x => x.Id == id);
            }
        }
        
         public PercursoPontoInfo pegarUltimoMovimento(int id)
        {
            lock (locker)
            {
                return database.Table<PercursoPontoInfo>().Last(x => x.Movimento == true);
            }
        }

        public int gravar(PercursoPontoInfo percurso)
        {
            lock (locker)
            {
                if (percurso.Id != 0)
                {
                    database.Update(percurso);
                    return percurso.Id;
                }
                else
                {
                    return database.Insert(percurso);
                }
            }
        }

        public void excluir(int id)
        {
            database.Delete<PercursoPontoInfo>(id);
        }
    }
}
