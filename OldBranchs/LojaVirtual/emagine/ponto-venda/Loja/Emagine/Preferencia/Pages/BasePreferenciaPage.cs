using Radar.Estilo;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public abstract class BasePreferenciaPage: ContentPage
    {
        private IList<View> _Itens = new List<View>();

        protected abstract string Titulo { get; }
        protected abstract void inicializarComponente();
        protected abstract void inicializarTela();

        public BasePreferenciaPage() {
            Title = Titulo;
            inicializarComponente();
            inicializarTela();

            var stackLayout = new StackLayout {
                Style = EstiloUtils.Preferencia.MainStackLayout
            };
            foreach (var item in _Itens)
                stackLayout.Children.Add(item);

            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Content = stackLayout
            };
        }

        protected void adicionarItem(View item) {
            _Itens.Add(item);
        }

        protected void adicionarSwitch(Switch campo, string titulo, string descricao = "") {
            _Itens.Add(criarSwitch(campo, titulo, descricao));
        }

        protected void adicionarLabel(Label campo, string titulo, string descricao = "")
        {
            _Itens.Add(criarLabel(campo, titulo, descricao));
        }

        protected void adicionarBotao(string titulo, Action acao, string descricao = "")
        {
            _Itens.Add(criarBotao(titulo, acao, descricao));
        }

        protected View criarSwitch(Switch campo, string titulo, string descricao = "")
        {
            /*
            var gridLayout = new Grid {
                HorizontalOptions = LayoutOptions.StartAndExpand
            };
            var label = new Label
            {
                Text = titulo,
                Style = EstiloUtils.PreferenciaTitulo
            };
            campo.HorizontalOptions = LayoutOptions.EndAndExpand;
            gridLayout.Children.Add(label, 0, 0);
            gridLayout.Children.Add(campo, 1, 0);
            */

            var stackLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        Children = {
                            new Label {
                                Text = titulo,
                                Style = EstiloUtils.Preferencia.Titulo
                            },
                            campo
                        }
                    }
                }
            };
            if (!string.IsNullOrEmpty(descricao)) {
                stackLayout.Children.Add(new Label
                {
                    Text = descricao,
                    Style = EstiloUtils.Preferencia.Descricao
                });
            }

            return new Frame
            {
                Style = EstiloUtils.Preferencia.MainFrame,
                Content = stackLayout
            };
        }

        protected View criarLabel(Label campo, string titulo, string descricao = "")
        {
            var stackLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        Children = {
                            new Label {
                                Text = titulo,
                                Style = EstiloUtils.Preferencia.Titulo
                            },
                            campo
                        }
                    }
                }
            };
            if (!string.IsNullOrEmpty(descricao))
            {
                stackLayout.Children.Add(new Label
                {
                    Text = descricao,
                    Style = EstiloUtils.Preferencia.Descricao
                });
            }

            return new Frame
            {
                Style = EstiloUtils.Preferencia.MainFrame,
                Content = stackLayout
            };
        }

        protected View criarBotao(string titulo, Action acao, string descricao = "")
        {
            var stackLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                Children = {
                    new Label {
                        Style = EstiloUtils.Preferencia.Titulo,
                        Text = titulo
                    }
                }
            };
            if (!string.IsNullOrEmpty(descricao))
            {
                stackLayout.Children.Add(new Label
                {
                    Text = descricao,
                    Style = EstiloUtils.Preferencia.Descricao
                });
            }
            var frame = new Frame
            {
                Style = EstiloUtils.Preferencia.MainFrame,
                Content = stackLayout
            };
            frame.GestureRecognizers.Add(new TapGestureRecognizer() {
                Command = new Command(acao)
            });
            return frame;
        }
    }
}
