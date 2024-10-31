using System;
using System.Collections.Generic;
using Newtonsoft.Json;

namespace Emagine.Frete.Model
{
    public class LocalInfo
    {
        [JsonProperty("latitude")]
        public float Latitude { get; set; }

        [JsonProperty("longitude")]
        public float Longitude { get; set; }
    }
}
