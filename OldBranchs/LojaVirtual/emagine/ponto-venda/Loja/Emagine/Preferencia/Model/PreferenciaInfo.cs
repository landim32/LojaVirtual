using System;
using SQLite;
namespace Radar.Model
{
	[Table("preferencia")]
	public class PreferenciaInfo
	{
		    [PrimaryKey]
			public string Preferencia { get; set; }
			public string Valor { get; set; }

	}
}
