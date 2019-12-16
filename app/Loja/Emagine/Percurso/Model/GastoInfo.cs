using SQLite;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Model
{
    [Table("gasto")]
    public class GastoInfo
    {
        [AutoIncrement, PrimaryKey]
        public int Id { get; set; }
        public int IdPercurso { get; set; }
        public DateTime DataInclusao { get; set; }
        public string Observacao { get; set; }
        public GastoTipoEnum Tipo { get; set; }
        public float Latitude { get; set; }
        public float Longitude { get; set; }
        public string Endereco { get; set; }
        public string FotoPath { get; set; }
        public string FotoBase64 { get; set; }
    }
}
