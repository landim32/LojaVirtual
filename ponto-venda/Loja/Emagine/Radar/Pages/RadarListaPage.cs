using Emagine.Utils;
using Radar.BLL;
using Radar.Controls;
using Radar.Factory;
using Radar.Model;
using Radar.Popup;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public class RadarListaPage : TabbedPage
    {
        private ListView _ativoListView;
        private ListView _inativoListView;

        public RadarListaPage()
        {
            Title = "Meus Radares";
            /*
            _ativoListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
            };
            */

            RadarBLL regraRadar = RadarFactory.create();
            regraRadar.atualizarEndereco();
            var paginaAtivo = criarPaginaAtiva();
            
            var paginaInativo = criarPaginaInativo();
            
            atualizarRadar();
            Children.Add(paginaAtivo);
            Children.Add(paginaInativo);
        }

        private Page criarPaginaAtiva() {
            _ativoListView = new ListView
            {
                RowHeight = -1,
                HasUnevenRows = true,
                ItemTemplate = new DataTemplate(typeof(RadarAtivoCell)),
                Footer = new Label
				{
					Text = ""
				}
				
            };
            _ativoListView.ItemTapped += (sender, e) => {
                if (e.Item == null)
                    return;
                RadarInfo radar = (RadarInfo)e.Item;
                NavigationX.create(this).PushPopupAsyncX(new RadarVelocidadePopup(radar, this), true);
            };
            _ativoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            return new ContentPage {
                Title = "Meus radares",
                Content = _ativoListView,
                Icon = "ic_pin_drop_black_24dp.png"
            };
        }

        private Page criarPaginaInativo()
        {
            _inativoListView = new ListView
            {
                RowHeight = -1,
                HasUnevenRows = true,
                ItemTemplate = new DataTemplate(typeof(RadarInativoCell)),
                Footer = new Label
				{
				Text = ""
				}
            };
            _inativoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            return new ContentPage
            {
                Title = "Radares excluídos",
                Content = _inativoListView,
                Icon = "ic_delete_black_24dp.png"
            };
        }

        public void atualizarRadar() {
            var regraRadar = RadarFactory.create();
            var radaresUsuario = regraRadar.listarUsuario();
            var radaresInativo = regraRadar.listarInativo();
            _ativoListView.BindingContext = radaresUsuario;
            _inativoListView.BindingContext = radaresInativo;
        }
    }
}
