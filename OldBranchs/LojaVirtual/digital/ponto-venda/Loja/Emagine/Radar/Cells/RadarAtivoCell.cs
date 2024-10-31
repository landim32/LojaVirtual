using Radar.BLL;
using Radar.Factory;
using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public class RadarAtivoCell : RadarBaseCell
    {
        private MenuItem _excluirButton;

        protected override void inicializarComponente() {
            base.inicializarComponente();
            inicializarExcluirButton();
        }

        private void inicializarExcluirButton()
        {
            _excluirButton = new MenuItem
            {
                CommandParameter = "{Binding .}",
                Text = "Excluir",
                IsDestructive = true
            };
            _excluirButton.Clicked += (object sender, EventArgs e) =>
            {
                RadarInfo radar = (RadarInfo)((MenuItem)sender).BindingContext;
                RadarBLL regraRadar = RadarFactory.create();
                regraRadar.excluir(radar);
                var radarListView = (ListView)this.Parent;
                var contentPagina = (ContentPage)radarListView.Parent;
                var pagina = (RadarListaPage)contentPagina.Parent;
                pagina.atualizarRadar();
            };
            ContextActions.Add(_excluirButton);
        }
    }
}
