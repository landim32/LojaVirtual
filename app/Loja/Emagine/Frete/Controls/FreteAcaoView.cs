using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Pages;
using Emagine.Frete.Utils;
using Emagine.Login.Factory;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Plugin.LocalNotifications;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Frete.Controls
{
    public class FreteAcaoView: ContentView
    {
        private StackLayout _mainLayout;
        private Button _acompanharButton;
        private Button _abrirRotaButton;
        private Button _avaliarButton;
        private Button _acaoButton;
        private Button _cancelarButton;

        public event EventHandler<FreteInfo> AoAtualizarTela;

        public FreteAcaoView() {
            inicializarComponente();
            Content = _mainLayout;
        }

        public FreteInfo Frete {
            get {
                return (FreteInfo)BindingContext;
            }
            set {
                BindingContext = value;
                if (BindingContext != null) {
                    var frete = (FreteInfo)BindingContext;
                    atualizarTela(frete);
                }
            }
        }

        private void inicializarComponente() {
            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5
            };

            _acompanharButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_DANGER],
                Text = "Acompanhar"
            };
            _acompanharButton.Clicked += acompanharClicked;

            _avaliarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_AVISO],
                Text = "Avaliar"
            };
            _avaliarButton.Clicked += _avaliarClicked;

            _abrirRotaButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Ver rota externamente"
            };
            _abrirRotaButton.Clicked += abrirRotaClicked;

            _acaoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                Text = "Quero essa viagem"
            };
            _acaoButton.Clicked += acaoClicked;

            _cancelarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_DANGER],
                Text = "Cancelar"
            };
            _cancelarButton.Clicked += cancelarClicked;
        }

        private void acompanharClicked(object sender, EventArgs e)
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            if (motorista != null)
            {
                var acompanhaPage = CaronaUtils.acompanharComoMotorista(Frete);
                Navigation.PushAsync(acompanhaPage);
            }
            else {
                var acompanhaPage = CaronaUtils.acompanharComoCliente(Frete);
                Navigation.PushAsync(acompanhaPage);
            }
        }

        private void atualizarTela(FreteInfo frete) {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            switch (frete.Situacao)
            {
                case FreteSituacaoEnum.ProcurandoMotorista:
                    if (motorista != null && frete.IdUsuario != motorista.Id) {
                        _acaoButton.Text = "Quero essa viagem";
                        _acaoButton.Style = Estilo.Current[Estilo.BTN_SUCESSO];
                        _mainLayout.Children.Add(_acaoButton);
                    }
                    break;
                //case FreteSituacaoEnum.AprovandoMotorista:
                case FreteSituacaoEnum.AprovandoMotorista:
                    if (frete.IdUsuario == usuario.Id)
                    {
                        _acaoButton.Text = "Aprovar Marinheiro";
                        _acaoButton.Style = Estilo.Current[Estilo.BTN_SUCESSO];
                        _mainLayout.Children.Add(_acaoButton);
                    }
                    break;
                case FreteSituacaoEnum.Aguardando:
                    if (motorista != null && frete.IdUsuario != motorista.Id) {
                        _acaoButton.Text = "Iniciar";
                        _acaoButton.Style = Estilo.Current[Estilo.BTN_SUCESSO];
                        _mainLayout.Children.Add(_acaoButton);
                    }
                    break;
                case FreteSituacaoEnum.PegandoEncomenda:
                    if (motorista != null && frete.IdMotorista == motorista.Id)
                    {
                        _acaoButton.Text = "Peguei a encomenda";
                        _acaoButton.Style = Estilo.Current[Estilo.BTN_SUCESSO];
                        _mainLayout.Children.Add(_acaoButton);
                    }
                    break;
                case FreteSituacaoEnum.Entregando:
                    if (motorista != null && frete.IdMotorista == motorista.Id)
                    {
                        _acaoButton.Text = "Entreguei!";
                        _acaoButton.Style = Estilo.Current[Estilo.BTN_SUCESSO];
                        _mainLayout.Children.Add(_acaoButton);
                    }
                    break;
                case FreteSituacaoEnum.Entregue:
                    if (usuario != null && frete.IdUsuario == usuario.Id)
                    {
                        //_acaoButton.Text = "Confirmar entrega!";
                        _acaoButton.Text = "Finalizar atendimento!";
                        _acaoButton.Style = Estilo.Current[Estilo.BTN_SUCESSO];
                        _mainLayout.Children.Add(_acaoButton);
                    }
                    break;
            }

            switch (frete.Situacao)
            {
                case FreteSituacaoEnum.ProcurandoMotorista:
                    if (usuario.Id == frete.IdUsuario)
                    {
                        _cancelarButton.Text = "Cancelar";
                        _mainLayout.Children.Add(_cancelarButton);
                    }
                    break;
                case FreteSituacaoEnum.AprovandoMotorista:
                    if (motorista != null && frete.IdMotorista == motorista.Id)
                    {
                        _cancelarButton.Text = "Desistir";
                        _mainLayout.Children.Add(_cancelarButton);
                    }
                    else if (frete.IdUsuario == usuario.Id)
                    {
                        _cancelarButton.Text = "Quero outro marinheiro";
                        _mainLayout.Children.Add(_cancelarButton);
                    }
                    break;
            }

            var situacoes = new List<FreteSituacaoEnum>() {
                FreteSituacaoEnum.PegandoEncomenda,
                FreteSituacaoEnum.Entregando
            };
            if (situacoes.Contains(frete.Situacao)) {
                _mainLayout.Children.Add(_acompanharButton);
            }

            _mainLayout.Children.Add(_abrirRotaButton);
            situacoes = new List<FreteSituacaoEnum>() {
                FreteSituacaoEnum.EntregaConfirmada,
                FreteSituacaoEnum.Entregue
            };
            if (situacoes.Contains(frete.Situacao))
            {
                if (frete.IdUsuario == usuario.Id && !(frete.NotaMotorista > 0))
                {
                    _avaliarButton.Text = "Avaliar Marinheiro";
                    _mainLayout.Children.Add(_avaliarButton);
                }
                else if (frete.IdMotorista == usuario.Id && !(frete.NotaFrete > 0))
                {
                    _avaliarButton.Text = "Avaliar Cliente";
                    _mainLayout.Children.Add(_avaliarButton);
                }
            }
        }

        private void acaoClicked(object sender, EventArgs e)
        {
            var frete = this.Frete;
            switch (this.Frete.Situacao)
            {
                case FreteSituacaoEnum.ProcurandoMotorista:
                    aceitar(frete);
                    break;
                case FreteSituacaoEnum.AprovandoMotorista:
                    frete.Situacao = FreteSituacaoEnum.Aguardando;
                    alterarSituacao(frete);
                    break;
                case FreteSituacaoEnum.Aguardando:
                    var regraMotorista = MotoristaFactory.create();
                    var motorista = regraMotorista.pegarAtual();
                    if (motorista != null) {
                        PagamentoUtils.efetuarPagamento(motorista, frete, (novoFrete) =>
                        {
                            novoFrete.Situacao = FreteSituacaoEnum.PegandoEncomenda;
                            alterarSituacao(novoFrete);
                        });
                    }
                    break;
                case FreteSituacaoEnum.PegandoEncomenda:
                    frete.Situacao = FreteSituacaoEnum.Entregando;
                    alterarSituacao(frete);
                    break;
                case FreteSituacaoEnum.Entregando:
                    frete.Situacao = FreteSituacaoEnum.Entregue;
                    alterarSituacao(frete);
                    break;
                case FreteSituacaoEnum.Entregue:
                    frete.Situacao = FreteSituacaoEnum.EntregaConfirmada;
                    alterarSituacao(frete);
                    break;
                default:
                    string mensagem = string.Format("Nenhuma ação para a situação {0}.", this.Frete.SituacaoStr);
                    UserDialogs.Instance.AlertAsync(mensagem, "Erro", "Entendi");
                    break;
            }
        }

        private async void excluirFrete(int idFrete) {
            UserDialogs.Instance.ShowLoading("cancelando...");
            try
            {
                var regraFrete = FreteFactory.create();
                await regraFrete.excluir(idFrete);
                UserDialogs.Instance.HideLoading();
                AoAtualizarTela?.Invoke(this, null);
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }
        }

        private void cancelarClicked(object sender, EventArgs e) {
            var frete = this.Frete;

            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            switch (frete.Situacao)
            {
                case FreteSituacaoEnum.ProcurandoMotorista:
                    if (usuario.Id == frete.IdUsuario) {
                        excluirFrete(frete.Id);
                    }
                    break;
                case FreteSituacaoEnum.AprovandoMotorista:
                    if ((motorista != null && frete.IdMotorista == motorista.Id) || frete.IdUsuario == usuario.Id) {
                        frete.IdMotorista = null;
                        frete.Situacao = FreteSituacaoEnum.ProcurandoMotorista;
                        alterar(frete);
                    }
                    break;
            }
        }

        private void _avaliarClicked(object sender, EventArgs e)
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var descricao = "";
            var frete = this.Frete;
            if (frete.IdUsuario == usuario.Id)
            {
                descricao = "Como você avalia o motorista?";
            }
            else if (frete.IdMotorista == usuario.Id)
            {
                descricao = "Como você avalia o cliente?";
            }
            var avaliePage = new AvaliePage
            {
                Descricao = descricao
            };
            //avaliePage.AoAvaliar += async (s1, nota) =>
            avaliePage.AoAvaliar += async (s1, avaliacao) =>
            {
                UserDialogs.Instance.ShowLoading("carregando...");
                try
                {
                    var regraFrete = FreteFactory.create();
                    frete = await regraFrete.pegar(this.Frete.Id);
                    if (frete.IdUsuario == usuario.Id)
                    {
                        //frete.NotaMotorista = nota;
                        frete.NotaMotorista = avaliacao.Nota;
                    }
                    else if (frete.IdMotorista == usuario.Id)
                    {
                        //frete.NotaFrete = nota;
                        frete.NotaFrete = avaliacao.Nota;
                    }
                    await regraFrete.alterar(frete);
                    UserDialogs.Instance.HideLoading();
                    await Navigation.PopAsync();
                    AoAtualizarTela?.Invoke(this, frete);
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            };
            Navigation.PushAsync(avaliePage);
        }

        private async void abrirRotaClicked(object sender, EventArgs e)
        {
            try
            {
                var frete = this.Frete;
                if (frete == null)
                {
                    await UserDialogs.Instance.AlertAsync("Erro", "Destino não encontrado.", "Entendi");
                    return;
                }
                var opcao = await UserDialogs.Instance.ActionSheetAsync("Enviar rota para", "Cancelar", "Fechar", null, "Waze", "Google Maps");
                if (!string.IsNullOrEmpty(opcao))
                {
                    var local = (
                        from l in frete.Locais
                        where l.Tipo == FreteLocalTipoEnum.Destino
                        select l
                    ).LastOrDefault();
                    if (local != null)
                    {
                        string posicao = local.Latitude.ToString() + "," + local.Longitude.ToString();
                        switch (opcao)
                        {
                            case "Maps":
                                Device.OpenUri(new Uri("http://maps.google.com/maps?daddr=" + posicao));
                                break;
                            case "Waze":
                                Device.OpenUri(new Uri("waze://?q=" + posicao));
                                break;
                        }
                    }
                }
            }
            catch (Exception erro)
            {
                await UserDialogs.Instance.AlertAsync("Erro", erro.Message, "Entendi");
            }
        }

        private async void aceitar(FreteInfo frete)
        {
            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            if (motorista == null)
            {
                await UserDialogs.Instance.AlertAsync("Você não é um motorista.", "Erro", "Entendi");
                return;
            }

            var aceitePage = new AceitePage {
                Frete = frete
            };
            aceitePage.AoAceitar += async (sender, f) => {
                UserDialogs.Instance.ShowLoading("aceitando...");
                try
                {
                    var regraFrete = FreteFactory.create();
                    var retorno = await regraFrete.aceitar(new AceiteEnvioInfo
                    {
                        IdFrete = frete.Id,
                        IdMotorista = motorista.Id,
                        Aceite = true
                    });
                    if (retorno != null)
                    {
                        if (retorno.Aceite)
                        {
                            var novoFrete = await regraFrete.pegar(retorno.IdFrete);
                            UserDialogs.Instance.HideLoading();
                            AoAtualizarTela?.Invoke(this, novoFrete);
                        }
                        else
                        {
                            UserDialogs.Instance.HideLoading();
                            await UserDialogs.Instance.AlertAsync(retorno.Mensagem, "Erro", "Entendi");
                        }
                    }
                    else
                    {
                        UserDialogs.Instance.HideLoading();
                    }
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            };
            await Navigation.PushAsync(aceitePage);
        }

        private async void alterar(FreteInfo frete)
        {
            UserDialogs.Instance.ShowLoading("carregando...");
            try
            {
                var regraFrete = FreteFactory.create();
                await regraFrete.alterar(frete);
                BindingContext = await regraFrete.pegar(frete.Id);
                UserDialogs.Instance.HideLoading();
                AoAtualizarTela?.Invoke(this, frete);
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }
        }

        private async void alterarSituacao(FreteInfo frete)
        {
            UserDialogs.Instance.ShowLoading("carregando...");
            try
            {
                var regraFrete = FreteFactory.create();
                await regraFrete.alterarSituacao(frete.Id, frete.Situacao);
                BindingContext = await regraFrete.pegar(frete.Id);
                UserDialogs.Instance.HideLoading();
                AoAtualizarTela?.Invoke(this, frete);
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }
        }
    }
}
