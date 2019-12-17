using SQLite;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Treino.Model
{
    [Table("treino")]
    public class TreinoInfo
    {
        private IList<TreinoParteInfo> _treinoParte = null;

        public TreinoInfo() {
            _treinoParte = new List<TreinoParteInfo>();
        }

        [AutoIncrement, PrimaryKey]
        public int Id { get; set; }
        public string Nome { get; set; }
        public TreinoTipoEnum Tipo { get; set; }
        public bool Repetir { get; set; }

        [Ignore]
        public IList<TreinoParteInfo> Partes {
            get {
                return _treinoParte;
            }
            set {
                _treinoParte = value;
            }
        }
    }
}
