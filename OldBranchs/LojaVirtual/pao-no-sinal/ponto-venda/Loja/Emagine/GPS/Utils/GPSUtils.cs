using Emagine;
using Emagine.Base.Utils;
using Emagine.GPS.Model;
using Plugin.Geolocator;
using Plugin.Geolocator.Abstractions;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;
using Emagine.GPS.BLL;

namespace Emagine.GPS.Utils
{
    public static class GPSUtils
    {
        private static GPSBLL _gps;

        public static bool UsaLocalizacao { get; set; } = true;

        public static GPSBLL Current {
            get {
                if (!UsaLocalizacao) {
                    throw new Exception("UsaLocalizacao não está ativa.");
                }
                if (_gps == null) {
                    _gps = new GPSBLL();
                }
                return _gps;
            }
        }
    }
}
