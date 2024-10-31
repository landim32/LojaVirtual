using System;
using System.Collections.Generic;
using Android.Content;
using Android.Gms.Maps;
using Android.Gms.Maps.Model;
using Emagine.Mapa.Controls;
using Loja.Droid;
using Xamarin.Forms;
using Xamarin.Forms.Maps;
using Xamarin.Forms.Maps.Android;
using Xamarin.Forms.Platform.Android;

[assembly: ExportRenderer(typeof(CustomMap), typeof(CustomMapRenderer))]
namespace Loja.Droid
{
    public class CustomMapRenderer : MapRenderer
    {
        private bool _inicializouMapa = false;
        private IList<Position> _polylinePosicoes;
        private GoogleMap _map;
        private CustomMap _mapaForm;
        private Polyline _polyline;

        public CustomMapRenderer(Context context) : base(context)
        {
            _inicializouMapa = false;
        }

        protected override void OnElementChanged(ElementChangedEventArgs<Map> e)
        {
            base.OnElementChanged(e);

            if (e.OldElement != null)
            {
                // Unsubscribe
            }

            if (_map != null)
            {
                _map.MapClick -= googleMapClick;
            }

            if (e.NewElement != null)
            {
                _mapaForm = (CustomMap)e.NewElement;
                _polylinePosicoes = _mapaForm.Polyline;
                _mapaForm.aoResetarPolyline += (sender, polyline) =>
                {
                    resetarPolyline(polyline);
                };
                _mapaForm.aoZoomPolyline += (sender, polyline, animated) =>
                {
                    zoomPolyline(polyline, animated);
                };
                Control.GetMapAsync(this);
            }
        }

        private void googleMapClick(object sender, GoogleMap.MapClickEventArgs e)
        {
            ((CustomMap)Element).OnTap(new Position(e.Point.Latitude, e.Point.Longitude));
        }

        protected override void OnMapReady(GoogleMap map)
        {
            base.OnMapReady(map);
            _map = map;
            _map.UiSettings.MyLocationButtonEnabled = false;
            _map.UiSettings.ZoomControlsEnabled = false;
            _map.UiSettings.ZoomGesturesEnabled = true;
            _map.UiSettings.RotateGesturesEnabled = true;

            if (_map != null)
            {
                _map.MapClick += googleMapClick;
            }
            if (NativeMap != null)
            {
                var polylineOptions = new PolylineOptions();
                polylineOptions.InvokeColor(0x66FF0000);
                foreach (var position in _polylinePosicoes)
                {
                    polylineOptions.Add(new LatLng(position.Latitude, position.Longitude));
                }
                _polyline = NativeMap.AddPolyline(polylineOptions);
            }
            if (_mapaForm != null)
            {
                if (!_inicializouMapa)
                {
                    _mapaForm.inicializarMapa();
                    _inicializouMapa = true;
                }
            }
        }

        private void resetarPolyline(IList<Position> novaRota)
        {
            if (_polyline != null)
            {
                _polyline.Remove();
                _polyline.Dispose();
                _polyline = null;
            }
            if (NativeMap != null)
            {
                var polylineOptions = new PolylineOptions();
                polylineOptions.InvokeColor(0x66FF0000);
                while (polylineOptions.Points != null && polylineOptions.Points.Count > 0)
                {
                    polylineOptions.Points.RemoveAt(0);
                }
                if (novaRota != null)
                {
                    foreach (var position in novaRota)
                    {
                        polylineOptions.Add(new LatLng(position.Latitude, position.Longitude));
                    }
                    _polyline = NativeMap.AddPolyline(polylineOptions);
                }
            }
        }

        private void zoomPolyline(IList<Position> polyline, bool animated = false)
        {
            if (polyline.Count > 0)
            {
                LatLngBounds.Builder builder = new LatLngBounds.Builder();
                foreach (var p in polyline)
                {
                    builder.Include(new LatLng(p.Latitude, p.Longitude));
                }
                CameraUpdate cameraUpdate = CameraUpdateFactory.NewLatLngBounds(builder.Build(), 20);
                if (animated)
                {
                    _map.AnimateCamera(cameraUpdate);
                }
                else {
                    _map.MoveCamera(cameraUpdate);
                }
            }
        }
    }
}
