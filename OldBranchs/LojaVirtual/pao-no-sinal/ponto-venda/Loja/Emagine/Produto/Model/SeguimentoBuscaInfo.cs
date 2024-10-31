using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Produto.Model
{
    public class SeguimentoBuscaInfo
    {
        [JsonProperty("latitude")]
        public double Latitude { get; set; }
        [JsonProperty("longitude")]
        public double Longitude { get; set; }
        [JsonProperty("raio")]
        public int Raio { get; set; }
    }
}
