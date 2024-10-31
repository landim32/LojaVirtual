using SQLite;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Treino.Model
{
    [Table("treino_parte")]
    public class TreinoParteInfo
    {
        [AutoIncrement, PrimaryKey]
        public int Id { get; set; }
        public int IdTreino { get; set; }
        public TreinoTipoEnum Tipo { get; set; }
        public TimeSpan Tempo { get; set; }
    }
}
