using System;
using Newtonsoft.Json;

namespace Emagine.Frete.Model
{
    public class DisponibilidadeInfo
    {
        [JsonProperty("id_disponibilidade")]
        public long Id { get; set; }
        [JsonProperty("id_usuario")]
        public int IdMotorista { get; set; }
        [JsonProperty("data")]
        public DateTime Data { get; set; }
        [JsonProperty("cidade")]
        public string Cidade { get; set; }
        [JsonProperty("uf")]
        public string Estado { get; set; }
        [JsonIgnore]
        public string LocalidadeLbl {
            get {
                return "Local: " + Estado + ", " + Cidade;
            }
        }
        [JsonIgnore]
        public string Titulo
        {
            get
            {
                return "Data: " + Data.ToString("dd/MM/yyyy");
            }
        }
    }
}
