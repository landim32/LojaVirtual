using System;
using System.Collections.Generic;
using System.Text;

namespace Loja.Emagine.GPS.Utils
{
    public static class DistanciaUtils
    {
        public const int DIAMETRO_TERRA = 6371;

        private static double toRadians(double deg)
        {
            return deg * (Math.PI / 180);
        }

        public static double calcularDistancia(double initialLat, double initialLong, double finalLat, double finalLong)
        {
            double dLat = toRadians(finalLat - initialLat);
            double dLon = toRadians(finalLong - initialLong);
            double lat1 = toRadians(initialLat);
            double lat2 = toRadians(finalLat);

            double a = Math.Sin(dLat / 2) * Math.Sin(dLat / 2) +
                   Math.Sin(dLon / 2) * Math.Sin(dLon / 2) * Math.Cos(lat1) * Math.Cos(lat2);
            double c = 2 * Math.Atan2(Math.Sqrt(a), Math.Sqrt(1 - a));
            return DIAMETRO_TERRA * c * 1000;
        }
    }
}
