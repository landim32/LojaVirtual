using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Radar.BLL;
using Radar.Factory;
using Xamarin.Forms;
using Radar.Pages.Popup;
using Rg.Plugins.Popup.Extensions;
using Emagine.Utils;

namespace Radar.Pages {
    public partial class ModoAudioPage : ContentPage {

        public ModoAudioPage() {
            InitializeComponent();
            Title = "Áudio";
        }

        protected override void OnAppearing() {
            base.OnAppearing();
            //volumePersonalizado.IsToggled = PreferenciaUtils.VolumePersonalizado;
            somCaixa.IsToggled = PreferenciaUtils.SomCaixa;
        }

        //public void volumePersonalizadoToggled(object sender, ToggledEventArgs e) {
        //    PreferenciaUtils.VolumePersonalizado = e.Value;
        //}

		public void somCaixaToggled(object sender, ToggledEventArgs e) {
            PreferenciaUtils.SomCaixa = e.Value;
        }

        public void canalAudioTapped(object sender, EventArgs e) {
            NavigationX.create(this).PushPopupAsyncX(new CanalAudioPopUp(), true);
        }

        public void alturaVolumeTapped(object sender, EventArgs e) {
            NavigationX.create(this).PushPopupAsyncX(new AlturaVolumePopUp(), true);
        }

        public void somAlertaTapped(object sender, EventArgs e) {
            NavigationX.create(this).PushPopupAsyncX(new SomAlertaPopUp(), true);
        }
    }
}
