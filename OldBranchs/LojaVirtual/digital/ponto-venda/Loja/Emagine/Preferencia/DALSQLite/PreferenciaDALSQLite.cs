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
	public class PreferenciaDALSQLite : IPreferenciaDAL
	{
		SQLiteConnection database;
		static object locker = new object();

		public PreferenciaDALSQLite()
		{
			database = DependencyService.Get<ISQLite>().GetConnection();
			database.CreateTable<PreferenciaInfo>();
		}

		public IList<PreferenciaInfo> listar()
		{
			lock (locker)
			{
				return (from i in database.Table<PreferenciaInfo>() select i).ToList();
			}
		}

		public PreferenciaInfo pegar(string preferencia)
		{
			lock (locker)
			{
				return database.Table<PreferenciaInfo>().FirstOrDefault(x => x.Preferencia == preferencia);
			}
		}

		public int gravar(PreferenciaInfo preferencia)
		{
			lock (locker)
			{
				
				return database.InsertOrReplace(preferencia);

			}
		}

		public void excluir(string preferencia)
		{
			database.Delete<PreferenciaInfo>(preferencia);
		}

        
    }
}
