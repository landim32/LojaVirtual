using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Frete.Model
{
    public class TipoVeiculoInfo
    {
        [JsonProperty("id_tipo")]
        public int Id { get; set; }

        [JsonProperty("nome")]
        public string Nome { get; set; }

        [JsonProperty("cod_tipo")]
        public int CodTipo {
            get {
                return (int)Tipo;
            }
            set {
                Tipo = (TipoVeiculoEnum)value;
            }
        }

        [JsonIgnore]
        public TipoVeiculoEnum Tipo { get; set; }

        [JsonProperty("foto")]
        public string Foto { get; set; }

        [JsonProperty("foto_url")]
        public string FotoUrl { get; set; }

        [JsonProperty("capacidade")]
        public double Capacidade { get; set; }

        public override string ToString()
        {
            return this.Nome;
        }
    }
}
