using System;
using Newtonsoft.Json;

namespace Emagine.Frete.Model
{
    public class FreteHistoricoInfo
    {
        [JsonProperty("id_historico")]
        public long Id { get; set; }
        [JsonProperty("id_frete")]
        public long IdFrete { get; set; }
        [JsonProperty("latitude")]
        public double Latitude { get; set; }
        [JsonProperty("longitude")]
        public double Longitude { get; set; }
        [JsonProperty("endereco")]
        public string Endereco { get; set; }

        public DateTime DataHora { get; set; } = DateTime.Now;

        public string TextoCell
        {
            get
            {
                return string.Format("{0} hrs - Lat: {1} / Long: {2}", DataHora.ToString("dd/MM/yyyy HH:mm"), Latitude, Longitude);
            }
        }
        /*
         * {id_historico: 1, id_frete: 1, latitude: 0.0, longitude: 0.0, endereco: ""}*/

    }
}
