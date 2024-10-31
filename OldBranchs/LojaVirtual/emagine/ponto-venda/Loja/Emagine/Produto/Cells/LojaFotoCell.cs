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
                Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
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
                                //_EnderecoLabel,
                                //gerarNota(0),
                                _notaControl,
                                _DistanciaLabel
                            }
                        },
                    }
                }
            };
        }

        /*
        private StackLayout gerarNota(int nota)
        {
            return new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new IconImage {
                        Icon = (nota >= 1) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    },
                    new IconImage {
                        Icon = (nota >= 2) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    },
                    new IconImage {
                        Icon = (nota >= 3) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    },
                    new IconImage {
                        Icon = (nota >= 4) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    },
                    new IconImage {
                        Icon = (nota >= 5) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    }
                }
            };
        }
        */

        private void inicilizarComponente() {
            _FotoImage = new Image {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Aspect = Aspect.AspectFit,
                WidthRequest = 80,
                HeightRequest = 80
                /*
                WidthRequest = 120,
                HeightRequest = 120
                */
                //Style = Estilo.Current[Estilo.LISTA_ITEM]
            };
            _FotoImage.SetBinding(Image.SourceProperty, new Binding("FotoUrl"));
            _NomeLabel = new Label {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                FontAttributes = FontAttributes.Bold,
                TextColor = Estilo.Current.BarBackgroundColor,
                FontSize = 18,
            };
            _NomeLabel.SetBinding(Label.TextProperty, new Binding("Nome"));
            _EnderecoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Italic,
                TextColor = Color.FromHex("#7c7c7c"),
                FontSize = 13,
            };
            _EnderecoLabel.SetBinding(Label.TextProperty, new Binding("EnderecoCompleto"));

            _notaControl = new NotaControl {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                IconSize = 24
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
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                TextColor = Color.FromHex("#7c7c7c"),
                FontSize = 14,
            };
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr"));
        }
    }
}
