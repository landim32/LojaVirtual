using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Frete.Controls;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace Emagine.Frete.Pages
{
    public class FretePage: ContentPage
    {
        private bool _executandoDuracao = false;
        private int _duracao = 0;

        private StackLayout _mainLayout;
        private CustomMap _CustomMap;
        private Label _duracaoLabel;
        private FreteAcaoView _acaoView;

        public FreteInfo Frete {
            get {
                return (FreteInfo)BindingContext;
            }
            set {
                BindingContext = value;
                var frete = (FreteInfo)BindingContext;
                if (BindingContext != null) {
                    Title = frete.SituacaoStr;
                    atualizarTela(frete);
                }
            }
        }

        public FretePage()
        {
            inicializarComponente();
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            Padding = 0;

            _mainLayout = new StackLayout()
            {
                Orientation = StackOrientation.Vertical,
                Margin = 0,
                Padding = new Thickness(0, 0, 0, 10),
                Spacing = 0,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
            };

            Content = new ScrollView {
                Margin = 0,
                Padding = 0,
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Content = _mainLayout
            };
        }

        private void atualizarTela(FreteInfo frete) {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            _mainLayout.Children.Clear();
            if (frete.RotaEncontrada)
            {
                if (!string.IsNullOrEmpty(frete.Polyline))
                {
                    _mainLayout.Children.Insert(0, _CustomMap);
                    var posicoes = MapaUtils.decodePolyline(frete.Polyline);
                    _CustomMap.Polyline = posicoes;
                }
                else
                {
                    if (frete.Locais.Count() > 0)
                    {
                        _mainLayout.Children.Insert(0, _CustomMap);
                        var posicoes = new List<Position>();
                        foreach (var local in frete.Locais)
                        {
                            posicoes.Add(new Position(local.Latitude, local.Longitude));
                        }
                        _CustomMap.Polyline = posicoes;
                    }
                }
            }
            else {
                if (frete.Locais.Count() > 0) {
                    _mainLayout.Children.Insert(0, _CustomMap);
                    var posicoes = new List<Position>();
                    foreach (var local in frete.Locais) {
                        posicoes.Add(new Position(local.Latitude, local.Longitude));
                    }
                    _CustomMap.Polyline = posicoes;
                }
            }
            if (frete.NotaFrete > 0 && frete.NotaMotorista > 0)
            {
            }
            else if (frete.NotaFrete > 0) {
                var nota = gerarNota(frete.NotaFrete);
                nota.Margin = new Thickness(3, 6, 3, 0);
                _mainLayout.Children.Add(nota);
            }
            else if (frete.NotaMotorista > 0)
            {
                var nota = gerarNota(frete.NotaMotorista);
                nota.Margin = new Thickness(3, 6, 3, 0);
                _mainLayout.Children.Add(nota);
            }

            if (!(string.IsNullOrEmpty(frete.EnderecoOrigem) && string.IsNullOrEmpty(frete.EnderecoDestino)))
            {
                _mainLayout.Children.Add(gerarLocalizacao(frete));
            }
            _mainLayout.Children.Add(gerarBasico(frete));
            _mainLayout.Children.Add(gerarData(frete));
            var situacoes = new List<FreteSituacaoEnum>() {
                FreteSituacaoEnum.EntregaConfirmada,
                FreteSituacaoEnum.PegandoEncomenda,
                FreteSituacaoEnum.Entregando,
                FreteSituacaoEnum.Entregue
            };
            if (situacoes.Contains(frete.Situacao))
            {
                _mainLayout.Children.Add(gerarHorario(frete));
            }
            if (frete.IdUsuario != usuario.Id)
            {
                _mainLayout.Children.Add(gerarUsuario(frete.Usuario, "RESPONSÁVEL"));
            }
            if (frete.Motorista != null && frete.Motorista.Usuario != null && frete.IdMotorista != usuario.Id) {
                _mainLayout.Children.Add(gerarUsuario(frete.Motorista.Usuario, "MOTORISTA"));
            }
            _mainLayout.Children.Add(_acaoView);
            _acaoView.Frete = frete;
        }

        private StackLayout gerarNota(int nota) {
            var notaStackLayout = new StackLayout {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 3
            };
            for (var i = 1; i <= 5; i++)
            {
                notaStackLayout.Children.Add(new IconImage
                {
                    Icon = (nota >= i) ? "fa-star" : "fa-star-o",
                    IconColor = Color.Gold,
                    IconSize = 28
                });
            }
            return notaStackLayout;
        }

        private void gridAdicionarTitulo(Grid gridLayout, string titulo)
        {
            var tituloLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.TITULO3],
                HorizontalTextAlignment = TextAlignment.Center,
                Text = titulo
            };
            gridLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(80, GridUnitType.Absolute) });
            gridLayout.Children.Add(tituloLabel, 0, 0);
            Grid.SetColumnSpan(tituloLabel, 2);
        }

        private void gridAdicionarLinha(Grid gridLayout, string titulo, Label labelControl, ref int linha) {
            gridLayout.Children.Add(new Label
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.LABEL_CONTROL],
                HorizontalTextAlignment = TextAlignment.End,
                VerticalTextAlignment = TextAlignment.End,
                Text = titulo
            }, 0, linha);
            gridLayout.Children.Add(labelControl, 1, linha);
            linha++;
        }

        private void gridAdicionarLinha(Grid gridLayout, string titulo, string valor, ref int linha, TextAlignment alinhamento = TextAlignment.End) {
            gridAdicionarLinha(gridLayout, titulo, new Label
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                HorizontalTextAlignment = TextAlignment.End,
                VerticalTextAlignment = TextAlignment.End,
                Text = valor
            }, ref linha);
        }

        private Frame gerarLocalizacao(FreteInfo frete)
        {
            int linha = 1;
            var gridLayout = new Grid
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                RowSpacing = 0
            };
            gridAdicionarTitulo(gridLayout, "LOCALIZAÇÃO");
            if (!string.IsNullOrEmpty(frete.EnderecoOrigem))
            {
                gridAdicionarLinha(gridLayout, "Origem:", frete.EnderecoOrigem, ref linha, TextAlignment.Start);
            }
            if (!string.IsNullOrEmpty(frete.EnderecoDestino)) {
                gridAdicionarLinha(gridLayout, "Destino:", frete.EnderecoDestino, ref linha, TextAlignment.Start);
            }
            return new Frame
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
                Margin = new Thickness(3, 6, 3, 0),
                Content = gridLayout
            };
        }

        private Frame gerarBasico(FreteInfo frete)
        {
            int linha = 1;
            var gridLayout = new Grid
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                RowSpacing = 0
            };
            gridAdicionarTitulo(gridLayout, "DADOS BÁSICOS");
            if (frete.Preco > 0)
            {
                gridAdicionarLinha(gridLayout, "Preco:", "R$" + frete.Preco.ToString("N2"), ref linha);
            }
            if (frete.Distancia > 0)
            {
                gridAdicionarLinha(gridLayout, "Distância:", frete.DistanciaStr, ref linha);
            }
            if (frete.Tempo > 0)
            {
                gridAdicionarLinha(gridLayout, "Previsão:", frete.TempoStr, ref linha);
            }
            return new Frame
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
                Margin = new Thickness(3, 6, 3, 0),
                Content = gridLayout
            };
        }

        private Frame gerarData(FreteInfo frete) {
            int linha = 1;
            var gridLayout = new Grid {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                RowSpacing = 0
            };
            gridAdicionarTitulo(gridLayout, "AGENDAMENTO");
            gridAdicionarLinha(gridLayout, "Criação:", frete.DataInclusaoStr, ref linha);
            gridAdicionarLinha(gridLayout, "Alteração:", frete.UltimaAlteracaoStr, ref linha);
            if (frete.DataRetirada.HasValue) {
                gridAdicionarLinha(gridLayout, "Retirada:", frete.DataRetiradaStr, ref linha);
            }
            if (frete.DataEntrega.HasValue)
            {
                gridAdicionarLinha(gridLayout, "Entrega:", frete.DataEntregaStr, ref linha);
            }

            return new Frame {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
                Margin = new Thickness(3, 6, 3, 0),
                Content = gridLayout
            };
        }

        private Frame gerarHorario(FreteInfo frete)
        {
            int linha = 1;
            var gridLayout = new Grid
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                RowSpacing = 0
            };
            gridAdicionarTitulo(gridLayout, "HORÁRIO");
            if (frete.DataRetiradaExecutada.HasValue)
            {
                gridAdicionarLinha(gridLayout, "Retirada:", frete.DataRetiradaExecutadaStr, ref linha);
            }
            if (frete.DataEntregaExecutada.HasValue)
            {
                gridAdicionarLinha(gridLayout, "Entregue:", frete.DataEntregaExecutadaStr, ref linha);
            }
            var situacoes = new List<FreteSituacaoEnum>() {
                FreteSituacaoEnum.PegandoEncomenda,
                FreteSituacaoEnum.Entregando
            };
            if (situacoes.Contains(frete.Situacao))
            {
                gridAdicionarLinha(gridLayout, "Duração:", _duracaoLabel, ref linha);
            }

            return new Frame
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
                Margin = new Thickness(3, 6, 3, 0),
                Content = gridLayout
            };
        }

        private Frame gerarUsuario(UsuarioInfo usuario, string titulo) {
            int linha = 1;
            var gridLayout = new Grid
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                RowSpacing = 0
            };
            gridAdicionarTitulo(gridLayout, titulo);
            gridAdicionarLinha(gridLayout, "Nome:", usuario.Nome, ref linha, TextAlignment.Start);
            if (!string.IsNullOrEmpty(usuario.Email))
            {
                gridAdicionarLinha(gridLayout, "Email:", usuario.Email, ref linha, TextAlignment.Start);
            }
            if (!string.IsNullOrEmpty(usuario.Telefone))
            {
                gridAdicionarLinha(gridLayout, "Telefone:", usuario.TelefoneStr, ref linha, TextAlignment.Start);
            }
            return new Frame
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
                Margin = new Thickness(3, 6, 3, 0),
                Content = gridLayout
            };
        }

        private Frame gerarCarga(FreteInfo frete)
        {
            int linha = 1;
            var gridLayout = new Grid
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                RowSpacing = 0
            };
            gridAdicionarTitulo(gridLayout, "CARGA");
            if (frete.Peso > 0)
            {
                gridAdicionarLinha(gridLayout, "Peso:", frete.Peso.ToString("N2") + "Kg", ref linha);
            }
            if (string.IsNullOrEmpty(frete.Dimensao))
            {
                gridAdicionarLinha(gridLayout, "Dimensões:", frete.Dimensao, ref linha);
            }

            return new Frame
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
                Margin = new Thickness(3, 6, 3, 0),
                Content = gridLayout
            };
        }

        private void inicializarComponente()
        {
            _CustomMap = new CustomMap
            {
                MapType = MapType.Street,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                HeightRequest = 180,
                IsShowingUser = false,
                //HasZoomEnabled = false
            };
            _duracaoLabel = new Label
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                HorizontalTextAlignment = TextAlignment.End,
                VerticalTextAlignment = TextAlignment.End,
                Text = "00:00:00"
            };
            _CustomMap.aoInicializarMapa += (sender, e) => {
                Device.StartTimer(new TimeSpan(1000), () => {
                    try
                    {
                        ((CustomMap)sender).zoomPolyline();
                    }
                    catch (Exception erro) {
                        UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                    }
                    return false;
                });
            };

            _acaoView = new FreteAcaoView {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(3, 6, 3, 0)
            };
            _acaoView.AoAtualizarTela += async (sender, frete) => {
                //atualizarTela(e);
                var fretePage = new FretePage {
                    Frete = frete
                };
                await Navigation.PushAsync(fretePage);
                Navigation.RemovePage(this);
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            _duracao = 0;
            _executandoDuracao = true;
            Device.StartTimer(new TimeSpan(0, 0, 1), () => {
                var frete = Frete;
                if (frete != null) {
                    var situacoes = new List<FreteSituacaoEnum>() {
                        FreteSituacaoEnum.PegandoEncomenda,
                        FreteSituacaoEnum.Entregando
                    };
                    if (situacoes.Contains(frete.Situacao))
                    {
                        _duracaoLabel.Text = TimeSpan.FromSeconds(frete.Duracao + _duracao).ToString();
                        _duracao++;
                    }
                }
                return _executandoDuracao;
            });
        }

        protected override void OnDisappearing()
        {
            base.OnDisappearing();
            _executandoDuracao = false;
        }
    }
}
