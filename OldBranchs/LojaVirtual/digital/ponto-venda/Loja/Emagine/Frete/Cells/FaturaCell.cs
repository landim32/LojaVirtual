using System;
using Emagine;
using Emagine.Base.Estilo;
using Xamarin.Forms;

namespace Emagine.Frete.Cells
{
    public class FaturaCell : ViewCell
    {
        private Label _Valor;
        private Label _DataInclusao;
        private Label _DataVencimento;
        private Label _DataPagamento;
        private Label _DataConfirmacao;
        private Label _FormaPagamento;
        private Label _Observacao;

        public FaturaCell()
        {
            inicializarComponente();
            View = new StackLayout
            {
                Margin = 10,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _Valor,
                    _DataInclusao,
                    _DataVencimento,
                    _DataPagamento,
                    _DataConfirmacao,
                    _FormaPagamento,
                    _Observacao
                }
            };
        }

        private void inicializarComponente()
        {
            _Valor = new Label
            {
                Style = Estilo.Current[Estilo.TITULO2]
            };
            _Valor.SetBinding(Label.TextProperty, new Binding("Preco", stringFormat: "Preço: R$ {0:N2}"));

            _DataInclusao = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _DataInclusao.SetBinding(Label.TextProperty, new Binding("DataInclusaoLbl", stringFormat: "Data de inclusão: {0}"));

            _DataVencimento = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _DataVencimento.SetBinding(Label.TextProperty, new Binding("DataVencimentoLbl", stringFormat: "Data de vencimento: {0}"));

            _DataPagamento = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _DataPagamento.SetBinding(Label.TextProperty, new Binding("DataPagamentoLbl", stringFormat: "Data de pagamento: {0}"));

            _DataConfirmacao = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _DataConfirmacao.SetBinding(Label.TextProperty, new Binding("DataConfirmacaoLbl", stringFormat: "Data de confirmacao: {0}"));

            _FormaPagamento = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _FormaPagamento.SetBinding(Label.TextProperty, new Binding("FormaPagamento", stringFormat: "Forma de pagamento: {0}"));

            _Observacao = new Label
            {
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };
            _Observacao.SetBinding(Label.TextProperty, new Binding("Observacao", stringFormat: "Observacao: {0}"));


        }
    }
}
