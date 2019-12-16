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
    public class RadarDALSQLite: IRadarDAL
    {
        SQLiteConnection database;
        static object locker = new object();

        public RadarDALSQLite()
        {
            database = DependencyService.Get<ISQLite>().GetConnection();
            database.CreateTable<RadarInfo>();
        }

        public IList<RadarInfo> listar()
        {
            lock (locker)
			{
				return database.Table<RadarInfo>().Take(20000).ToList();
			}
        }

        public IList<RadarInfo> listarUsuario()
        {
            lock (locker)
            {
                return (
                    from r in database.Table<RadarInfo>()
					where (r.usuario == 1 && r.Ativo == true)
                    select r
                ).ToList();
            }
        }

        public IList<RadarInfo> listarInativo() {
            lock (locker)
            {
                return (
                    from r in database.Table<RadarInfo>()
                    where (r.Ativo == false)
                    select r
                ).ToList();
            }
        }

        public IList<RadarInfo> listar(RadarBuscaInfo busca) {
            lock (locker)
            {
                string query =
                    "SELECT * FROM radar WHERE (" +
                    "((latsin * " + busca.latitudeSin.ToString().Replace(',', '.') + ") + (latcos * " + busca.latitudeCos.ToString().Replace(',', '.') + ")) * " +
                    "((loncos * " + busca.longitudeCos.ToString().Replace(',', '.') + ") + (lonsin * " + busca.longitudeSin.ToString().Replace(',', '.') + "))" +
                    ") > " + busca.distanciaCos.ToString().Replace(',', '.');
                if (busca.Filtros.Count() > 0) {
                    if (busca.Filtros.Count() == 1)
                    {
                        query += " AND type = " + ((int)busca.Filtros[0]).ToString();
                    }
                    else {
                        var lista = new List<string>();
                        foreach (var str in busca.Filtros)
                            lista.Add(((int)str).ToString());
                        query += " AND type IN (" + string.Join(", ", lista.ToArray()) + ")";
                    }
                }
                query += " AND (Ativo = 1 OR Ativo IS NULL) ";
                return database.Query<RadarInfo>(query);
                /*
                return database.Query<RadarInfo>(
                    "select * from radar where ((latsin * ?) + (latcos * ?) * (loncos * ?) + (lonsin * ?)) > ?",
                    new object[5] { latitudeSin, latitudeCos, longitudeCos, longitudeSin, distanciaCos }
                );
                */
            }
        }

        /// <summary>
        /// Lista dos radares dentro de uma região
        /// </summary>
        /// <param name="latitude">Latitude do centro da região</param>
        /// <param name="longitude">Longitude do centro da região</param>
        /// <param name="latitudeDelta">Delta da latitude</param>
        /// <param name="longitudeDelta">Delta da longitude</param>
        /// <returns>Lista de radares da região</returns>
        public IList<RadarInfo> listar(double latitude, double longitude, double latitudeDelta, double longitudeDelta, IList<RadarTipoEnum> filtro)
        {
            lock (locker)
            {
                string query = "select * from radar where lon between ? and ? and lat between ? and ?";
                if (filtro.Count() > 0)
                {
					if (filtro.Count() == 1)
					{
						query += " AND type = " + ((int)filtro[0]).ToString();
					}
					else {
						var lista = new List<string>();
                        foreach (var str in filtro)
                            lista.Add(((int)str).ToString());
                        query += " AND type IN (" + string.Join(", ", lista.ToArray()) + ")";
                    }
                }
                query += " AND (Ativo = 1 OR Ativo IS NULL) ";
                return database.Query<RadarInfo>(
                    query,
                    new object[4] {
                        longitude - longitudeDelta,
                        longitude + longitudeDelta,
                        latitude - latitudeDelta,
                        latitude + latitudeDelta
                    }
                );
            }
        }

		public IList<RadarInfo> listarEnderecoNulo()
		{
			
			string query = "select * from radar where usuario = ? and Endereco = ? and Ativo = ?";
			lock (locker)
			{
				return database.Query<RadarInfo>(query,
					new object[3] {
					    true,
					    "",
					    true
					});
			    }
			}

        public RadarInfo pegar(int idRadar)
        {
            lock (locker)
            {
                return database.Table<RadarInfo>().FirstOrDefault(x => x.id_radar == idRadar);
                //string query = "select * from radar where id = ";
            }
        }

        public int gravar(RadarInfo radar)
        {
            lock (locker)
            {
                if (radar.Id != 0)
                {
                    return database.Update(radar);
                    //return radar.Id;
                }
                else
                {
                    return database.Insert(radar);
                }
            }
        }


        public void excluir(int idLocal)
        {
            database.Delete<RadarInfo>(idLocal);
        }
    }
}
