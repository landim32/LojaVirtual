using System;
using System.Collections.Generic;
using Newtonsoft.Json;

namespace Emagine.Mapa.Model
{
    public class LocalInfo
    {
        public LocalInfo(double lat, double lng) {
            this.Latitude = lat;
            this.Longitude = lng;
        }

        public LocalInfo() : this(0, 0) {
        }

        [JsonProperty("latitude")]
        public double Latitude { get; set; } = 0;

        [JsonProperty("longitude")]
        public double Longitude { get; set; } = 0;
    }
}
