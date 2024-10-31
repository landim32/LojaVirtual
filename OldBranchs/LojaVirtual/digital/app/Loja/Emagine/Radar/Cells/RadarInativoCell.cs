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
    public class RadarInativoCell: RadarBaseCell
    {
        private MenuItem _reativarButton;

        protected override void inicializarComponente()
        {
            base.inicializarComponente();
            inicializarReativarButton();
        }

        private void inicializarReativarButton()
        {
            _reativarButton = new MenuItem
            {
                CommandParameter = "{Binding .}",
                Text = "Reativar",
                IsDestructive = true
            };
            _reativarButton.Clicked += (object sender, EventArgs e) =>
            {
                RadarBLL regraRadar = RadarFactory.create();
                RadarInfo radar = (RadarInfo)((MenuItem)sender).BindingContext;
                radar.Ativo = true;
                regraRadar.gravar(radar);
                var radarListView = (ListView)this.Parent;
                var contentPagina = (ContentPage)radarListView.Parent;
                var pagina = (RadarListaPage)contentPagina.Parent;
                pagina.atualizarRadar();
            };
            ContextActions.Add(_reativarButton);
        }
    }
}
