using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Pedido.Factory;
using Emagine.Pedido.Pages;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Cells
{
    public class LojaFotoCell: ViewCell
    {
        private Image _FotoImage;
        private Label _NomeLabel;
        private Label _EnderecoLabel;
        private NotaControl _notaControl;
        private Label _DistanciaLabel;

        public LojaInfo Loja {
            get {
                return (LojaInfo)BindingContext;
            }
        }

        public LojaFotoCell() {
            inicilizarComponente();
            View = new Frame
            {
                Style = Estilo.Current[EstiloLoja.LOJA_FRAME],
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    HorizontalOptions = LayoutOptions.FillAndExpand,
                    VerticalOptions = LayoutOptions.Start,
                    Margin = new Thickness(0, 7),
                    Children = {
                        _FotoImage,
                        new StackLayout {
                            Orientation = StackOrientation.Vertical,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            VerticalOptions = LayoutOptions.Start,
                            Margin = new Thickness(0, 1),
                            Spacing = 0,
                            Children = {
                                _NomeLabel,
                                _EnderecoLabel,
                                new StackLayout {
                                    Orientation = StackOrientation.Horizontal,
                                    HorizontalOptions = LayoutOptions.FillAndExpand,
                                    VerticalOptions = LayoutOptions.Start,
                                    Children = {
                                        _DistanciaLabel,
                                        _notaControl,
                                    }
                                },
                            }
                        },
                    }
                }
            };
        }

        private void inicilizarComponente() {
            _FotoImage = new Image {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[EstiloLoja.LOJA_FOTO]
            };
            _FotoImage.SetBinding(Image.SourceProperty, new Binding("FotoUrl"));
            _NomeLabel = new Label {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Style = Estilo.Current[EstiloLoja.LOJA_TITULO]
            };
            _NomeLabel.SetBinding(Label.TextProperty, new Binding("Nome"));
            _EnderecoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[EstiloLoja.LOJA_ENDERECO]
            };
            _EnderecoLabel.SetBinding(Label.TextProperty, new Binding("EnderecoCompleto"));

            _notaControl = new NotaControl {
                HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Start,
                IconSize = Estilo.Current.Loja.Icone.IconSize
            };
            _notaControl.SetBinding(NotaControl.NotaProperty, new Binding("Nota"));
            _notaControl.AoClicar += async (sender, nota) => {
                var loja = this.Loja;
                if (loja == null) {
                    UserDialogs.Instance.Alert("Nenhuma loja selecionada.", "Erro", "Fechar");
                    return;
                }
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    //var regraLoja = LojaFactory.create();
                    //_lojaListView.ItemsSource = await regraLoja.buscar(Local.Latitude, Local.Longitude);
                    var regraPedido = PedidoFactory.create();
                    var pedidos = await regraPedido.listarAvaliacao(loja.Id);

                    var avaliacaoPage = new LojaAvaliacaoPage() {
                        Title = "Avaliações",
                        Pedidos = pedidos
                    };
                    UserDialogs.Instance.HideLoading();
                    if (App.Current.MainPage is RootPage) {
                        ((RootPage)App.Current.MainPage).PushAsync(avaliacaoPage);
                    }
                    else {
                        await App.Current.MainPage.Navigation.PushAsync(avaliacaoPage);
                    }
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                }
            };

            _DistanciaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                VerticalTextAlignment = TextAlignment.Center,
                Style = Estilo.Current[EstiloLoja.LOJA_DISTANCIA]
            };
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr"));
        }
    }
}
