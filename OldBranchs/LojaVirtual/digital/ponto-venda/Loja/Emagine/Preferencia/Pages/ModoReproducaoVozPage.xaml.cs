using Radar.BLL;
using Radar.Factory;
using System;
using System.Collections.Generic;

using Xamarin.Forms;

namespace Radar
{
	public partial class ModoReproducaoVozPage : ContentPage
	{
        public ModoReproducaoVozPage()
		{
			InitializeComponent();
            Title = "Reprodução Voz";
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            habilitar.IsToggled = PreferenciaUtils.HabilitarVoz;
            ligarDesligar.IsToggled = PreferenciaUtils.LigarDesligar;
            encurtar.IsToggled = PreferenciaUtils.Encurtar;
            alertaSonoro.IsToggled = PreferenciaUtils.AlertaSonoro;
        }

        public void habilitarToggled(object sender, ToggledEventArgs e) {
            PreferenciaUtils.HabilitarVoz = e.Value;
        }

        public void ligarDesligarToggled(object sender, ToggledEventArgs e) {
            PreferenciaUtils.LigarDesligar = e.Value;
        }

        public void encurtarToggled(object sender, ToggledEventArgs e) {
            PreferenciaUtils.Encurtar = e.Value;
        }

        public void alertaSonoroToggled(object sender, ToggledEventArgs e) {
            PreferenciaUtils.AlertaSonoro = e.Value;
        }
    }
}
