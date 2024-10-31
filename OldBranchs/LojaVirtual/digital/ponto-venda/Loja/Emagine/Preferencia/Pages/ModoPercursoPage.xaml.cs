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
	public partial class ModoPercursoPage : ContentPage
	{
        public ModoPercursoPage() 
        {
            InitializeComponent();
            Title = "Percursos";
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            salvarPercurso.IsToggled = PreferenciaUtils.SalvarPercurso;
            //excluirAntigos.IsToggled = PreferenciaUtils.ExcluirAntigo;
        }


        public void salvarPercursoToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.SalvarPercurso = e.Value;
        }

		public void excluirAntigosToggled(object sender, ToggledEventArgs e)
		{
            PreferenciaUtils.ExcluirAntigo = e.Value;
        }

        public void tempoPercursoTapped(object sender, EventArgs e) {
            NavigationX.create(this).PushPopupAsyncX(new TempoPercursoPopUp(), true);
        }
	}
}

