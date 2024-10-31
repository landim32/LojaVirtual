using System;
using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Frete.BLL;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Pagamento.BLL;
using Xamarin.Forms;

namespace Emagine.Pagamento.Pages
{
    public class SelecionaMetodoPagamento : ContentPage
    {
        private FreteInfo _Info;
        private Grid _mainLayout;
        private PrincipalButton _EnviarProdutoButton;
        private PrincipalButton _RastrearMercadoriaButton;

        public SelecionaMetodoPagamento(FreteInfo info)
        {
            _Info = info;
            inicializarComponente();
            Title = "Método de pagamento";
            _mainLayout = new Grid
            {
                Margin = new Thickness(10, 10),
                RowSpacing = 10,
                ColumnSpacing = 10
            };

            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _mainLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _mainLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });

            _mainLayout.Children.Add(_EnviarProdutoButton, 0, 0);
            _mainLayout.Children.Add(_RastrearMercadoriaButton, 1, 0);

            Content = _mainLayout;
        }

        private void inicializarComponente()
        {
            _EnviarProdutoButton = new PrincipalButton("fa-credit-card", "Pagar com\nCartão");
            _EnviarProdutoButton.AoClicar += async (sender, e) =>
            {
                var ret = await new PagamentoBLL().listarCartao();
                if(ret.Count > 0){
                    // Rodrigo Landim - 16/03
                    //Navigation.PushAsync(new CartaoListaPage(_Info, ret));    
                }
                else {
                    // Rodrigo Landim - 16/03
                    //Navigation.PushAsync(new CartaoPage(_Info));    
                }
            };

            _RastrearMercadoriaButton = new PrincipalButton("fa-money", "Pagar em\nDinheiro");
            _RastrearMercadoriaButton.AoClicar += async (sender, e) =>
            {
                UserDialogs.Instance.ShowLoading("Enviando...");
                _Info.Situacao = FreteSituacaoEnum.ProcurandoMotorista;
                await FreteFactory.create().alterar(_Info);
                UserDialogs.Instance.HideLoading();
                Navigation.PopToRootAsync();
            };
        }
    }
}

