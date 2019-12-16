using Emagine.Base.Estilo;
using Emagine.Veiculo.Cells;
using Emagine.Veiculo.Model;
using Emagine.Veiculo.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Veiculo.Pages
{
    public class VeiculoListaPage : ContentPage
    {
        private ListView _VeiculoListView;

        public VeiculoListaPage()
        {
            Title = "Selecione o Veículo";
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            //BackgroundColor = Color.FromHex("#d9d9d9");
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _VeiculoListView
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            _VeiculoListView.ItemsSource = buscar();
        }

        private IList<VeiculoInfo> buscar() {
            var veiculos = new List<VeiculoInfo>();
            veiculos.Add(new VeiculoInfo {
                Foto = "voyage.jpg",
                Titulo = "Voyage",
                NomeLoja = "Teste1",
                Motor = "Motor 1.6",
                Direcao = "Dir. Hidráulica",
                Assento = "5 assentos",
                ArCondicionado = "Ar Condicionado",
                Cambio = "Cambio Manual",
                Porta = "2 ou 4",
                AbsStr = "ABS: Não",
                AirbagStr = "Airbag: Sim",
                Valor = 58.12
            });
            veiculos.Add(new VeiculoInfo
            {
                Foto = "gol.jpg",
                Titulo = "Gol",
                NomeLoja = "Teste2",
                Motor = "Motor 1.0",
                Direcao = "Dir. Manual",
                Assento = "5 assentos",
                ArCondicionado = "Ar Condicionado",
                Cambio = "Cambio Manual",
                Porta = "2 ou 4",
                AbsStr = "ABS: Não",
                AirbagStr = "Airbag: Não",
                Valor = 45.67
            });
            veiculos.Add(new VeiculoInfo
            {
                Foto = "amarok.jpg",
                Titulo = "Amarok",
                NomeLoja = "Teste3",
                Motor = "Motor 4.0",
                Direcao = "Dir. Hidráulica",
                Assento = "6 assentos",
                ArCondicionado = "Ar Condicionado",
                Cambio = "Cambio Auto.",
                Porta = "4",
                AbsStr = "ABS: Sim",
                AirbagStr = "Airbag: Sim",
                Valor = 87.13
            });
            return veiculos;
        }

        private void inicializarComponente() {
            _VeiculoListView = new ListView {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                ItemTemplate = new DataTemplate(typeof(VeiculoCell))
            };
            _VeiculoListView.SetBinding(ListView.ItemsSourceProperty, new Binding(".")); 
            _VeiculoListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var veiculo = (VeiculoInfo)((ListView)sender).SelectedItem;
                Navigation.PushAsync(new VeiculoPage(veiculo));
                _VeiculoListView.SelectedItem = null;
                /*
                ImovelInfo imovel = (ImovelInfo)((ListView)sender).SelectedItem;

                //var perfil = PreferenciaUtils.Perfil;
                Navigation.PushAsync(new ImovelExibePage(imovel));
                _imovelListView.SelectedItem = null;
                */
            };
        }
    }
}