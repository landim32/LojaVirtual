using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms.Maps;

namespace Emagine.Mapa.Controls
{
    public class MapTapEventArgs : EventArgs
    {
        public Position Position { get; set; }
    }
}
