using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.GPS.Model
{
    public class LocalEventArgs : EventArgs
    {
        public GPSLocalInfo Posicao { get; set; }
    }
}
