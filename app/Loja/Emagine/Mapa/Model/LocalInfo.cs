using System;
using System.Collections.Generic;
using Newtonsoft.Json;

namespace Emagine.Mapa.Model
{
    public class LocalInfo
    {
        [JsonProperty("latitude")]
        public double Latitude { get; set; }

        [JsonProperty("longitude")]
        public double Longitude { get; set; }
    }
}
