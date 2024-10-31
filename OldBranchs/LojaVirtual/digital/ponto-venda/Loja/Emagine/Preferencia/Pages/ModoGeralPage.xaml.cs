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
	public partial class ModoGeralPage : ContentPage
	{
        public ModoGeralPage() 
        {
            InitializeComponent();
            Title = "Gerais";
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            verificarIniciar.IsToggled = PreferenciaUtils.VerificarIniciar;
        }


        public void verificarIniciarToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.VerificarIniciar = e.Value;
        }

        public void intervaloVerificacaoTapped(object sender, EventArgs e) {
            NavigationX.create(this).PushPopupAsyncX(new InvervaloVerificacaoPopUp(), true);
        }

        public void desativarGPSTapped(object sender, EventArgs e) {
            NavigationX.create(this).PushPopupAsyncX(new DesativarGPSPopUp(), true);
        }
	}
}

