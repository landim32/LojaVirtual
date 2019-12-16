using Radar.DALFactory;
using Radar.IDAL;
using Radar.Model;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.BLL
{
	public class PreferenciaBLL
	{
		private IPreferenciaDAL _preferenciaDB;

		public PreferenciaBLL()
		{
			_preferenciaDB = PreferenciaDALFactory.create();
		}

		public IList<PreferenciaInfo> listar()
		{
			IList<PreferenciaInfo> preferencias = _preferenciaDB.listar();

			return preferencias;
		}

        [Obsolete("Usar pegarBool agora.")]
        public bool pegarBooleano(string preferencia)
        {
            return pegarBool(preferencia);
        }

        public string pegar(string preferencia, string padrao = "")
		{
			PreferenciaInfo _preferencia = _preferenciaDB.pegar(preferencia);
    		return (_preferencia != null) ? _preferencia.Valor : padrao;
		}

        public bool pegarBool(string chave, bool padrao = false) {
            PreferenciaInfo preferencia = _preferenciaDB.pegar(chave);
            if (preferencia != null) {
                bool retorno = padrao;
                if (bool.TryParse(preferencia.Valor, out retorno)) {
                    return retorno;
                }
            }
            return padrao;
        }

        public int pegarInt(string chave, int padrao = 0) {
            PreferenciaInfo preferencia = _preferenciaDB.pegar(chave);
            if (preferencia != null) {
                int retorno = padrao;
                if (int.TryParse(preferencia.Valor, out retorno))
                    return retorno;
            }
            return padrao;
        }

        public long pegarLong(string chave, long padrao = 0)
        {
            PreferenciaInfo preferencia = _preferenciaDB.pegar(chave);
            if (preferencia != null)
            {
                long retorno = padrao;
                if (long.TryParse(preferencia.Valor, out retorno))
                    return retorno;
            }
            return padrao;
        }

        public void gravar(string preferencia, int valor)
		{
			PreferenciaInfo pref = new PreferenciaInfo() { 
				Preferencia = preferencia,
				Valor = valor.ToString()
			};
			_preferenciaDB.gravar(pref);
		}

        public void gravar(string preferencia, bool valor)
        {
            PreferenciaInfo pref = new PreferenciaInfo()
            {
                Preferencia = preferencia,
                Valor = valor.ToString()
            };
            _preferenciaDB.gravar(pref);
        }

        public void gravar(string preferencia, long valor)
        {
            PreferenciaInfo pref = new PreferenciaInfo()
            {
                Preferencia = preferencia,
                Valor = valor.ToString()
            };
            _preferenciaDB.gravar(pref);
        }

        public void excluir(string preferencia)
		{
			
		}

        
    }
}
