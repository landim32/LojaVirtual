using System;
using Xamarin.Forms;
using Radar.BLL;
using Radar.Factory;
using Radar.Pages.Popup;
using Rg.Plugins.Popup.Extensions;
using Radar.Controls;
using Emagine.Utils;
using Radar.Model;

namespace Radar {
    public partial class ModoMapaPage : ContentPage
	{
        public ModoMapaPage()
        {
            InitializeComponent();
            Title = "Modo Mapa";

        }

        protected override void OnAppearing() {
            base.OnAppearing();
            bussola.IsToggled = PreferenciaUtils.Bussola;
            sinalGPS.IsToggled = PreferenciaUtils.SinalGPS;
            imagenSatelite.IsToggled = PreferenciaUtils.ImagemSatelite;
            infoTrafego.IsToggled = PreferenciaUtils.InfoTrafego;
            rotacionarMapa.IsToggled = PreferenciaUtils.RotacionarMapa;
            suavizarAnimacao.IsToggled = PreferenciaUtils.SuavizarAnimacao;
        }

        public void bussolaToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.Bussola = e.Value;
        }

		public void sinalGPSToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.SinalGPS = e.Value;
        }

		public void imagenSateliteToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.ImagemSatelite = e.Value;
        }

		public void infoTrafegoToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.InfoTrafego = e.Value;
        }

		public void rotacionarMapaToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.RotacionarMapa = e.Value;
        }

        public void suavizarAnimacaoToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.SuavizarAnimacao = e.Value;
        }

        public void nivelZoomTapped(object sender, EventArgs e)
        {
            NavigationX.create(this).PushPopupAsyncX(new NivelZoomPopUp(), true);
        }
	}
}

