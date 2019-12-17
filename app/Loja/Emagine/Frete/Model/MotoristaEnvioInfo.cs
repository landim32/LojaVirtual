using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Frete.Model
{
    public class MotoristaEnvioInfo
    {
        [JsonProperty("id_motorista")]
        public int IdMotorista { get; set; }

        [JsonProperty("latitude")]
        public double Latitude { get; set; }

        [JsonProperty("longitude")]
        public double Longitude { get; set; }

        [JsonProperty("cod_disponibilidade")]
        public int CodDisponibilidade { get; set; }
    }
}
