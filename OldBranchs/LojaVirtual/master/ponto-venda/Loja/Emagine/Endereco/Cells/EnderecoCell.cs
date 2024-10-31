using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using Emagine.Endereco.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Endereco.Cells
{
    public class EnderecoCell: ViewCell
    {
        private Label _CepLabel;
        private Label _LogradouroLabel;
        private Label _ComplementoLabel;
        private Label _NumeroLabel;
        private Label _BairroLabel;
        private Label _CidadeLabel;
        private Label _UfLabel;
        private Label _PosicaoLabel;

        public EnderecoCell() {
            inicializarComponente();
            inicializarMenu();
            View = new Frame
            {
                Style = Estilo.Current[Estilo.ENDERECO_FRAME],
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Vertical,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    Children = {
                        _LogradouroLabel,
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            Spacing = 5,
                            Children = {
                                _ComplementoLabel,
                                new Label {
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[Estilo.ENDERECO_LABEL],
                                    Text = ", "
                                },
                                _NumeroLabel
                            }
                        },
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            Spacing = 5,
                            Children = {
                                new Label {
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[Estilo.ENDERECO_LABEL],
                                    Text = "Bairro: "
                                },
                                _BairroLabel,
                                new Label {
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[Estilo.ENDERECO_LABEL],
                                    Text = "Cidade: "
                                },
                                _CidadeLabel,
                                new Label {
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[Estilo.ENDERECO_LABEL],
                                    Text = " / "
                                },
                                _UfLabel
                            }
                        },
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            Spacing = 5,
                            Children = {
                                new Label {
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[Estilo.ENDERECO_LABEL],
                                    Text = "Posição: "
                                },
                                _PosicaoLabel,
                                new Label {
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[Estilo.ENDERECO_LABEL],
                                    Text = "CEP: "
                                },
                                _CepLabel
                            }
                        },
                    }
                }
            };
        }

        private EnderecoListaPage buscarPagina(Element elemento) {
            if (elemento == null) {
                return null;
            }
            if (elemento is EnderecoListaPage) {
                return (EnderecoListaPage)elemento;
            }
            else {
                return buscarPagina(elemento.Parent);
            }
        }

        private void inicializarMenu() {
            var removerButton = new MenuItem
            {
                Text = "Remover",
                //Icon = "fa-remove",
                IsDestructive = true,
                //IconColor = Estilo.Current.BarTitleColor,
            };
            removerButton.SetBinding(MenuItem.CommandParameterProperty, new Binding("."));
            removerButton.Clicked += (sender, e) =>
            {
                var menu = (MenuItem)sender;
                var listaPage = buscarPagina(this.Parent);
                if (listaPage != null) {
                    var endereco = (EnderecoInfo)menu.CommandParameter;
                    listaPage.excluir(endereco);
                }
            };
            ContextActions.Add(removerButton);
        }

        private void inicializarComponente() {

            _CepLabel = new Label
            {
                LineBreakMode = LineBreakMode.TailTruncation,
                Style = Estilo.Current[Estilo.ENDERECO_CAMPO],
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
            };
            _CepLabel.SetBinding(Label.TextProperty, new Binding("Cep"));
            _LogradouroLabel = new Label {
                LineBreakMode = LineBreakMode.TailTruncation,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENDERECO_TITULO],
            };
            _LogradouroLabel.SetBinding(Label.TextProperty, new Binding("Logradouro"));
            _ComplementoLabel = new Label
            {
                LineBreakMode = LineBreakMode.TailTruncation,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENDERECO_TITULO]
            };
            _ComplementoLabel.SetBinding(Label.TextProperty, new Binding("Complemento"));
            _NumeroLabel = new Label
            {
                LineBreakMode = LineBreakMode.TailTruncation,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENDERECO_TITULO]
            };
            _NumeroLabel.SetBinding(Label.TextProperty, new Binding("Numero"));
            _BairroLabel = new Label
            {
                LineBreakMode = LineBreakMode.TailTruncation,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENDERECO_CAMPO]
            };
            _BairroLabel.SetBinding(Label.TextProperty, new Binding("Bairro"));
            _CidadeLabel = new Label
            {
                LineBreakMode = LineBreakMode.TailTruncation,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENDERECO_CAMPO]
            };
            _CidadeLabel.SetBinding(Label.TextProperty, new Binding("Cidade"));
            _UfLabel = new Label
            {
                LineBreakMode = LineBreakMode.TailTruncation,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENDERECO_CAMPO]
            };
            _UfLabel.SetBinding(Label.TextProperty, new Binding("Uf"));
            _PosicaoLabel = new Label
            {
                LineBreakMode = LineBreakMode.TailTruncation,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENDERECO_CAMPO]
            };
            _PosicaoLabel.SetBinding(Label.TextProperty, new Binding("Posicao"));
        }
    }
}
