using System;
using System.Collections.Generic;

namespace Emagine.Mapa.Model
{
    public class GoogleGeocodingInfo
    {
        public string status { get; set; }
        public List<GoogleGeocodingResult> results { get; set; }
    }
}
