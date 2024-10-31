using System;
using System.Collections.Generic;
using Xamarin.Forms.Maps;

namespace Emagine.Mapa.Controls
{
    public class CustomMap : Map
    {
        public delegate void ZoomEventHandler(object sender, IList<Position> polyline, bool animated = false);

        public event EventHandler aoInicializarMapa;
        public event EventHandler<IList<Position>> aoResetarPolyline;
        public event ZoomEventHandler aoZoomPolyline;
        public event EventHandler<MapTapEventArgs> aoClicar;

        private IList<Position> _polyline;

        public IList<Position> Polyline
        {
            get {
                return _polyline;
            }
            set {
                _polyline = value;
                aoResetarPolyline?.Invoke(this, _polyline);
            }
        }

        public CustomMap()
        {
            _polyline = new List<Position>();
        }

        public void OnTap(Position coordinate)
        {
            OnTap(new MapTapEventArgs {
                Position = coordinate
            });
        }

        protected virtual void OnTap(MapTapEventArgs e)
        {
            aoClicar?.Invoke(this, e);
        }

        public void zoomPolyline(bool animated = false) {
            aoZoomPolyline?.Invoke(this, _polyline, animated);
        }

        public void resetarPolyline() {
            _polyline = new List<Position>();
            aoResetarPolyline?.Invoke(this, _polyline);
        }

        public void inicializarMapa() {
            aoInicializarMapa?.Invoke(this, new EventArgs());
        }
    }
}
