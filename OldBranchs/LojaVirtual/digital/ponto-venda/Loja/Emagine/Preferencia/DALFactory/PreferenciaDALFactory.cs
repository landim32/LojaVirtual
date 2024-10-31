using Radar.DALSQLite;
using Radar.IDAL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.DALFactory
{
	public class PreferenciaDALFactory
	{
		private static IPreferenciaDAL _Preferencia;

		public static IPreferenciaDAL create()
		{
			if (_Preferencia == null)
				_Preferencia = new PreferenciaDALSQLite();
			return _Preferencia;
		}
	}
}
