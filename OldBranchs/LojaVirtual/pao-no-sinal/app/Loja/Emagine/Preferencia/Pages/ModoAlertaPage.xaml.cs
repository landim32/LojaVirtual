using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Diagnostics;
using Xamarin.Forms;
using Radar.Model;
using Radar.BLL;
using Radar.Factory;
using Radar.Pages.Popup;
using Rg.Plugins.Popup.Extensions;
using Emagine.Utils;

namespace Radar
{
	public partial class ModoAlertaPage : ContentPage
	{           
        public ModoAlertaPage() {
            InitializeComponent();
            Title = "Alertas";
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            radarMovel.IsToggled = PreferenciaUtils.RadarMovel;
            pedagio.IsToggled = PreferenciaUtils.Pedagio;
            policiaRodoviaria.IsToggled = PreferenciaUtils.PoliciaRodoviaria;
            lombada.IsToggled = PreferenciaUtils.Lombada;
            alertaInteligente.IsToggled = PreferenciaUtils.AlertaInteligente;
            beepAviso.IsToggled = PreferenciaUtils.BeepAviso;
            vibrarAlerta.IsToggled = PreferenciaUtils.VibrarAlerta;
            sobreposicaoVisual.IsToggled = PreferenciaUtils.SobreposicaoVisual;
        }


        public void radarMovelToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.RadarMovel = e.Value;
		}

		public void pedagioToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.Pedagio = e.Value;
        }

		public void policiaRodoviariaToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.PoliciaRodoviaria = e.Value;
        }

		public void lombadaToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.Lombada = e.Value;
        }

		public void alertaInteligenteToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.AlertaInteligente = e.Value;
        }

		public void beepAvisoToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.BeepAviso = e.Value;
        }

		public void vibrarAlertaToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.VibrarAlerta = e.Value;
        }

        public void sobreposicaoVisualToggled(object sender, ToggledEventArgs e) {
            PreferenciaUtils.SobreposicaoVisual = e.Value;
        }

        public void tempoDuracaoTapped(object sender, EventArgs e) {
            //Navigation.PushPopupAsync(new TempoDuracaoPopUp());
            NavigationX.create(this).PushPopupAsyncX(new TempoDuracaoPopUp(), true);
        }

        public void tempoAlertaTapped(object sender, EventArgs e) {
            NavigationX.create(this).PushPopupAsyncX(new TempoAlertaPopUp(), true);
        }

        public void distanciaAlertaTapped(object sender, EventArgs e) {
            NavigationX.create(this).PushPopupAsyncX(new DistanciaAlertaPopUp(), true);
        }
	}
}

