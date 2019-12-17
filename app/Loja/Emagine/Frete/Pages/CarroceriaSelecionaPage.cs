using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Frete.Cells;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class CarroceriaSelecionaPage : ContentPage
    {
        private ListView _VeiculoLst;
        //private List<TipoCaminhaoInfo> _Veiculos;

        public event EventHandler<TipoCarroceriaInfo> AoSelecionar;


        public CarroceriaSelecionaPage()
        {
            Title = "Selecione o tipo de carroceria";
            inicializarComponente();
            Content = _VeiculoLst;
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            UserDialogs.Instance.ShowLoading("Enviando...");
            try
            {
                var regraCarroceria = TipoCarroceriaFactory.create();
                _VeiculoLst.ItemsSource = await regraCarroceria.listar();
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }
        }

        private void inicializarComponente()
        {
            _VeiculoLst = new ListView();
            _VeiculoLst.HasUnevenRows = true;
            _VeiculoLst.RowHeight = -1;
            _VeiculoLst.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _VeiculoLst.ItemTemplate = new DataTemplate(typeof(TipoCarroceriaCell));
            _VeiculoLst.SeparatorVisibility = SeparatorVisibility.Default;
            /*
            _Veiculos = new List<TipoCaminhaoInfo>()
            {
                new TipoCaminhaoInfo(){ Descricao = "Baú", ImgStr = "Carroceria_01.png", Codigo = 1 },
                new TipoCaminhaoInfo(){ Descricao = "Baú Frigorífico", ImgStr = "Carroceria_02.png", Codigo = 2 },
                new TipoCaminhaoInfo(){ Descricao = "Boiadeiro", ImgStr = "Carroceria_03.png", Codigo = 3 },
                new TipoCaminhaoInfo(){ Descricao = "Bug Porta Container", ImgStr = "Carroceria_04.png", Codigo = 4 },
                new TipoCaminhaoInfo(){ Descricao = "Caçamba", ImgStr = "Carroceria_05.png", Codigo = 5 },
                new TipoCaminhaoInfo(){ Descricao = "Gaiola", ImgStr = "Carroceria_06.png", Codigo = 6 },
                new TipoCaminhaoInfo(){ Descricao = "Grade Baixa", ImgStr = "Carroceria_07.png", Codigo = 7 },
                new TipoCaminhaoInfo(){ Descricao = "Graneleiro", ImgStr = "Carroceria_08.png", Codigo = 8 },
                new TipoCaminhaoInfo(){ Descricao = "Munk", ImgStr = "Carroceria_09.png", Codigo = 9 },
                new TipoCaminhaoInfo(){ Descricao = "Passageiros", ImgStr = "Carroceria_10.png", Codigo = 10 },
                new TipoCaminhaoInfo(){ Descricao = "Prancha", ImgStr = "Carroceria_11.png", Codigo = 11 },
                new TipoCaminhaoInfo(){ Descricao = "Sider", ImgStr = "Carroceria_12.png", Codigo = 12 },
                new TipoCaminhaoInfo(){ Descricao = "Silo", ImgStr = "Carroceria_13.png", Codigo = 13 },
                new TipoCaminhaoInfo(){ Descricao = "Tanque", ImgStr = "Carroceria_14.png", Codigo = 14 },
                new TipoCaminhaoInfo(){ Descricao = "Para Tora", ImgStr = "Carroceria_15.png", Codigo = 15 },
            };
            _VeiculoLst.ItemsSource = _Veiculos;
            */
            _VeiculoLst.ItemTapped += (sender, e) =>
            {
                if (e == null)
                    return;
                var item = (TipoCarroceriaInfo)((ListView)sender).SelectedItem;
                AoSelecionar?.Invoke(this, item);
                //Navigation.PopAsync();
            };

        }
    }
}

