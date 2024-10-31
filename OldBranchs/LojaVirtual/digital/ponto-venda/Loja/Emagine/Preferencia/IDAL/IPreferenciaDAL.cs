using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.IDAL
{
	public interface IPreferenciaDAL
	{
		IList<PreferenciaInfo> listar();
		PreferenciaInfo pegar(string Preferencia);
		int gravar(PreferenciaInfo preferencia);
       
        void excluir(string preferencia);
	}
}
