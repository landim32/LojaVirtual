using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using FormsPlugin.Iconize;
using Xamarin.Forms;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Frete.Pages;
using Emagine.Base.Views;
using Emagine.Frete.BLL;

namespace Emagine.Frete.Cells
{
    public class FreteEmpresaCell : ViewCell
    {
        private Label _StatusLabel;
        private Label _OrigemDestinoLabel;
        private Label _Caminhoneiro;
        private Label _DataCriacao;
        private Label _DataRetirada;
        private Label _DataConclusao;
        private Label _Fretista;
        private Label _ContatoFretista;

        private Label _Definicao;
        private Label _Carga;
        private Label _Valor;
        private Label _AvaliacaoTitulo;


        private Button _VerHistorico;
        private Button _Avaliar;
        private AvaliacaoView _AvaliacaoView;
        private Label _Editar;

        private BindableProperty _Nota;
        private BindableProperty _IdFrete;

        public FreteEmpresaCell()
        {
            inicializarComponente();
            _Nota = BindableProperty.Create("_Nota", typeof(int), typeof(FreteEmpresaCell), default(int));
            this.SetBinding(_Nota, new Binding("NotaFrete"));

            _IdFrete = BindableProperty.Create("_IdFrete", typeof(int), typeof(FreteEmpresaCell), default(int));
            this.SetBinding(_IdFrete, new Binding("Id"));

            View = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = 10,
                Children = {
                    _StatusLabel,
                    _OrigemDestinoLabel,
                    _Caminhoneiro,
                    _DataCriacao,
                    _DataRetirada,
                    _DataConclusao,
                    _Fretista,
                    _ContatoFretista,
                    _VerHistorico,
                    _Definicao,
                    _Carga,
                    _Valor,
                    _Avaliar,
                    _AvaliacaoTitulo,
                    _AvaliacaoView,
                    _Editar
                }
            };
        }

		protected override void OnBindingContextChanged()
		{
            base.OnBindingContextChanged();
            _AvaliacaoView.setAvaliacao((int)GetValue(_Nota));
		}

		private void inicializarComponente()
        {
            _StatusLabel = new Label
            {
                Style = Estilo.Current[Estilo.TITULO2],
                Margin = 10,
                TextColor = Color.White,
                HeightRequest = 28,
                VerticalTextAlignment = TextAlignment.Center
            };
            _OrigemDestinoLabel = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _Caminhoneiro = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _DataCriacao = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _DataRetirada = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _DataConclusao = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL],
            };
            _Fretista = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL],
                Text = "Fretista: ***************"
            };
            _ContatoFretista = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL],
                Text = "Número fretista: (**) ***** - ****"
            };
            _Definicao = new Label
            {
                Style = Estilo.Current[Estilo.TITULO2],
                Text = "Deginição da carga"
            };
            _AvaliacaoTitulo = new Label
            {
                Style = Estilo.Current[Estilo.TITULO2],
                Text = "Sua avaliação sobre o frete:"
            };
            _Carga = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _Valor = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };

            _StatusLabel.SetBinding(Label.TextProperty, new Binding("SituacaoLblMaisCargas", stringFormat: " Status: {0} "));
            _StatusLabel.SetBinding(Label.BackgroundColorProperty, new Binding("CorSituacao"));
            _OrigemDestinoLabel.SetBinding(Label.TextProperty, new Binding("OrigemDestinoStr"));
            _Caminhoneiro.SetBinding(Label.TextProperty, new Binding("NomeMotorista", stringFormat: "Motorista: {0}"));
            _DataCriacao.SetBinding(Label.TextProperty, new Binding("DataInclusao", stringFormat: "Data criação: {0}"));
            _DataRetirada.SetBinding(Label.TextProperty, new Binding("DataRetiradaStr", stringFormat: "Data retirada: {0}"));
            _DataConclusao.SetBinding(Label.TextProperty, new Binding("DataEntregaLbl", stringFormat: "Data entrega: {0}"));
            _Carga.SetBinding(Label.TextProperty, new Binding("TituloFreteMotoristaLbl"));
            _Valor.SetBinding(Label.TextProperty, new Binding("Preco", stringFormat: "Valor pago: R$ {0:N}"));


            _VerHistorico = new Button()
            {
                Text = "Ver Histórico de posicionamento",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                FontSize = 12
            };
            _VerHistorico.Clicked += (sender, e) =>
            {
                ((RootPage)App.Current.MainPage).PushAsync(new FreteHistoricoPage((int)GetValue(_IdFrete)));
            };

            _AvaliacaoView = new AvaliacaoView(0);

            _Avaliar = new Button()
            {
                Text = "Avaliar o frete",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                FontSize = 12
            };
            _Avaliar.Clicked += (sender, e) =>
            {
                var avaliacaoPage = new AvaliarPage("Avalie como foi a sua experiência com este frete e o caminhoneiro que lhe atendeu o frete:");
                avaliacaoPage.Confirmed += async (object sender2, int e2) => {
                    //await new FreteBLL().avaliar((int)GetValue(_IdFrete), e2);
                    _AvaliacaoView.setAvaliacao(e2);
                };
                ((RootPage)App.Current.MainPage).PushAsync(avaliacaoPage);
            };

            _Editar = new Label
            {
                Text = "Toque para editar",
                TextColor = Color.LightGray,
                HorizontalOptions = LayoutOptions.Fill,
                HorizontalTextAlignment = TextAlignment.Center,
                Margin = new Thickness(0, 0, 0, 10)
            };
            _Editar.SetBinding(Label.IsVisibleProperty, new Binding("PodeEditar"));
        }
    }
}
