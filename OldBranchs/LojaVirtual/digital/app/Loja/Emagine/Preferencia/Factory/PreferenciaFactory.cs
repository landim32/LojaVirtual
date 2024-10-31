using Radar.BLL;


namespace Radar.Factory
{
	public static class PreferenciaFactory
	{
		private static PreferenciaBLL _Preferencia;

		public static PreferenciaBLL create()
		{
			if (_Preferencia == null)
				_Preferencia = new PreferenciaBLL();
			return _Preferencia;
		}
	}
}
