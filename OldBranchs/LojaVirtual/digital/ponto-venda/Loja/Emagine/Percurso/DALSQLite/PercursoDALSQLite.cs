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
    public class PercursoDALSQLite: IPercursoDAL
    {
        SQLiteConnection database;
        static object locker = new object();

        public PercursoDALSQLite()
        {
            database = DependencyService.Get<ISQLite>().GetConnection();
            database.CreateTable<PercursoInfo>();
        }

		public IList<PercursoInfo> listarPercurso(int idPercurso)
		{
			lock (locker)
			{
				return (
					from i in database.Table<PercursoInfo>()
					where i.Id == idPercurso
					select i
				).ToList();
			}
		}

        public IList<PercursoInfo> listar()
        {
            lock (locker)
            {
                return (
                    from i in database.Table<PercursoInfo>()
                    orderby i.Id descending
                    select i
                    ).ToList();
            }
        }

		public IList<PercursoInfo> listarEnderecoNulo()
		{
			lock (locker)
			{
				return (
					from i in database.Table<PercursoInfo>()
					where i.Endereco == null
					select i
				).ToList();
			}
		}

        public PercursoInfo pegar(int id)
        {
            lock (locker)
            {
                return database.Table<PercursoInfo>().FirstOrDefault(x => x.Id == id);
            }
        }

        public int gravar(PercursoInfo percurso)
        {
            lock (locker)
            {
                if (percurso.Id != 0)
                {
                    return database.Update(percurso);
                    //return percurso.Id;
                }
                else
                {
                    return database.Insert(percurso);
                    /*
                    return (
                        from p in database.Table<PercursoInfo>()
                        orderby p.Id
                        select p.Id
                    ).Take(1).FirstOrDefault();
                    */
                }
            }
        }

        public void excluir(int id)
        {
            database.Delete<PercursoInfo>(id);
        }
    }
}
