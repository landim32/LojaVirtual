using Radar.BLL;
using Radar.Factory;
using System;
using System.Collections.Generic;

using Xamarin.Forms;

namespace Radar {
    public partial class ModoAutoInicioPage : ContentPage {

        public ModoAutoInicioPage() {
            InitializeComponent();
            Title = "Auto Inicio/Desligamento";
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            habilitar.IsToggled = PreferenciaUtils.InicioDesligamento;
        }

        public void habilitarToggled(object sender, ToggledEventArgs e) {
            PreferenciaUtils.InicioDesligamento = e.Value;
        }
    }
}
