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
    public class GastoDALSQLite: IGastoDAL
    {
        SQLiteConnection database;
        static object locker = new object();

        public GastoDALSQLite()
        {
            database = DependencyService.Get<ISQLite>().GetConnection();
            database.CreateTable<GastoInfo>();
        }

		public IList<GastoInfo> listar(int idPercurso)
		{
			lock (locker)
			{
				return (
					from i in database.Table<GastoInfo>()
					where i.IdPercurso == idPercurso
                    orderby i.DataInclusao
					select i
				).ToList();
			}
		}

        public IList<GastoInfo> listar()
        {
            lock (locker)
            {
                return (from i in database.Table<GastoInfo>() select i).ToList();
            }
        }

        public GastoInfo pegar(int id)
        {
            lock (locker)
            {
                return database.Table<GastoInfo>().FirstOrDefault(x => x.Id == id);
            }
        }

        public int gravar(GastoInfo gasto)
        {
            lock (locker)
            {
                if (gasto.Id != 0)
                {
                    return database.Update(gasto);
                }
                else
                {
                    gasto.DataInclusao = DateTime.Now;
                    return database.Insert(gasto);
                }
            }
        }

        public void excluir(int id)
        {
            database.Delete<GastoInfo>(id);
        }
    }
}
