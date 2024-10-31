using System;
using System.Collections.Generic;
using Emagine.Base.Utils;
using Plugin.Geolocator;
using Xamarin.Forms.Maps;

namespace Emagine.Mapa.Utils
{
    public static class MapsUtils
    {
        [Obsolete("Use GPSUtils")]
        public static bool IsLocationAvailable()
        {
            PermissaoUtils.pedirPermissao();
            if (!CrossGeolocator.IsSupported)
            {
                return false;
            }

            return CrossGeolocator.Current.IsGeolocationAvailable;
        }
    }
}
