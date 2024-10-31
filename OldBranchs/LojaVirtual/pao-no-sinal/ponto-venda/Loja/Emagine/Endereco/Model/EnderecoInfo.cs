using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Endereco.Model
{
    public class EnderecoInfo
    {
        [JsonProperty("cep")]
        public string Cep { get; set; }
        [JsonProperty("uf")]
        public string Uf { get; set; }
        [JsonProperty("cidade")]
        public string Cidade { get; set; }
        [JsonProperty("bairro")]
        public string Bairro { get; set; }
        [JsonProperty("logradouro")]
        public string Logradouro { get; set; }
        [JsonProperty("complemento")]
        public string Complemento { get; set; }
        [JsonProperty("numero")]
        public string Numero { get; set; }
        [JsonProperty("latitude")]
        public double? Latitude { get; set; }
        [JsonProperty("longitude")]
        public double? Longitude { get; set; }

        [JsonIgnore]
        [Obsolete("Já é usando no Logradouro")]
        public string Rua { get; set; }

        [JsonIgnore]
        public string Posicao {
            get {
                if (Latitude.HasValue && Longitude.HasValue) {
                    return Latitude.Value.ToString("N5") + ", " + Longitude.Value.ToString("N5");
                }
                return "Não informada";
            }
        }

        [Obsolete("Tá uma bosta isso, coisa do Carlos. Tem um monte de campos e só mostra o CEP.")]
        public override string ToString()
		{
            return "Cep: " + Cep;
		}

	}
}
