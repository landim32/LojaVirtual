using Acr.UserDialogs;
using Emagine;
using Emagine.Base.Pages;
using Emagine.Frete.Cells;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Pages;
using Emagine.GPS.Model;
using Emagine.GPS.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Pages;
using Emagine.Mapa.Model;
using Emagine.Mapa.Pages;
using Emagine.Mapa.Utils;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using FormsPlugin.Iconize;
using Frete.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace Emagine.Frete.Utils
{
    public static class CaronaUtils
    {
        public static MapaAcompanhaPage AcompanhaPageAtual { get; set; }
        public static bool Acompanhando { get; set; } = false;

        public async static void inicializarFrete() {
            UserDialogs.Instance.ShowLoading("carregando...");
            try
            {
                var regraVeiculo = TipoVeiculoFactory.create();
                var veiculos = await regraVeiculo.listar();
                UserDialogs.Instance.HideLoading();
                if (veiculos.Count > 1)
                {
                    var veiculoPage = new TipoVeiculoSelecionaPage
                    {
                        Title = "Selecione o tipo de veículo",
                        Veiculos = veiculos
                    };
                    veiculoPage.AoSelecionar += (sender, tipo) => {
                        var rotaPage = gerarRotaSeleciona(tipo);
                        veiculoPage.Navigation.PushAsync(rotaPage);
                    };
                    if (App.Current.MainPage is RootPage) {
                        ((RootPage)App.Current.MainPage).PaginaAtual = veiculoPage;
                    }
                    else {
                        App.Current.MainPage = new IconNavigationPage(veiculoPage);
                    }
                }
                else if (veiculos.Count == 1) {
                    var rotaPage = gerarRotaSeleciona(veiculos.FirstOrDefault());
                    if (App.Current.MainPage is RootPage) {
                        ((RootPage)App.Current.MainPage).PaginaAtual = rotaPage;
                    }
                    else {
                        App.Current.MainPage = new IconNavigationPage(rotaPage);
                    }
                }
                else {
                    await UserDialogs.Instance.AlertAsync("Nenhum tipo de veículo cadastrado.", "Erro", "Entendi");
                }
            }
            catch (Exception e)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
            }

        }

        public static Page gerarRotaSeleciona(TipoVeiculoInfo veiculo) {
            var frete = new FreteInfo();
            frete.Veiculos.Add(veiculo);
            var rotaPage = new RotaSelecionaPage
            {
                Title = "Selecione a rota",
                IniciarEmPosicaoAtual = true,
                Frete = frete
            };
            rotaPage.AoSolicitar += (sender, f2) => {
                processarFrete(f2, async (f3) => {
                    var fretePage = new FretePage
                    {
                        Title = "Carona",
                        Frete = f3
                    };
                    UserDialogs.Instance.HideLoading();
                    await rotaPage.Navigation.PushAsync(fretePage);
                });
            };
            rotaPage.AoAgendar += (sender, f2) => {
                var agendaPage = gerarAgendaFrete(f2);
                rotaPage.Navigation.PushAsync(agendaPage);
            };
            return rotaPage;
        }

        public static Page gerarAgendaFrete(FreteInfo frete) {
            var agendaPage = new FreteAgendaPage
            {
                Title = "Agenda carrona",
                Frete = frete
            };
            agendaPage.AoAgendar += (sender, f2) =>
            {
                processarFrete(f2, async (f3) => {
                    var fretePage = new FretePage
                    {
                        Title = "Carona",
                        Frete = f3
                    };
                    UserDialogs.Instance.HideLoading();
                    await agendaPage.Navigation.PushAsync(fretePage);
                });
            };
            return agendaPage;
        }

        public static void processarFrete(FreteInfo frete, Action<FreteInfo> aoProcessar) {
            Login.Utils.LoginUtils.carregarUsuario((usuario) =>
            {
                string descricaoPgto = string.Format("Viagem entre {0} e {1}", frete.EnderecoOrigem, frete.EnderecoDestino);
                var pagamento = new PagamentoInfo {
                    IdUsuario = usuario.Id,
                    Situacao = SituacaoPagamentoEnum.Aberto
                };
                pagamento.Itens.Add(new PagamentoItemInfo {
                    Descricao = descricaoPgto,
                    Quantidade = 1,
                    Valor = frete.Preco
                });
                var pagamentoMetodoPage = new PagamentoMetodoPage
                {
                    Title = "Forma de Pagamento",
                    Pagamento = pagamento,
                    UsaDebito = false
                };
                pagamentoMetodoPage.AoEfetuarPagamento += async (sender, pgtoEfetuado) =>
                {
                    frete.IdPagamento = pgtoEfetuado.IdPagamento;
                    UserDialogs.Instance.ShowLoading("Solicitando carona...");
                    try
                    {
                        frete.IdUsuario = usuario.Id;
                        var regraFrete = FreteFactory.create();
                        var id_frete = await regraFrete.inserir(frete);
                        var freteAtualizado = await regraFrete.pegar(id_frete);
                        UserDialogs.Instance.HideLoading();
                        aoProcessar?.Invoke(freteAtualizado);
                    }
                    catch (Exception e)
                    {
                        UserDialogs.Instance.HideLoading();
                        await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
                    }
                };
                if (App.Current.MainPage is RootPage) {
                    ((RootPage)App.Current.MainPage).PushAsync(pagamentoMetodoPage);
                }
                else {
                    App.Current.MainPage = new IconNavigationPage(pagamentoMetodoPage);
                }
            });
        }

        [Obsolete("Use inicializar")]
        public async static void inicializarOld() {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario == null) {
                return;
            }
            UserDialogs.Instance.ShowLoading("carregando...");
            try
            {
                var regraFrete = FreteFactory.create();
                var fretes = await regraFrete.listar(usuario.Id, usuario.Id);
                if (fretes.Count() > 0)
                {
                    var frete = regraFrete.pegarAtual();
                    var situacoes = new List<FreteSituacaoEnum>() {
                        FreteSituacaoEnum.PegandoEncomenda,
                        FreteSituacaoEnum.Entregando
                    };
                    var freteAtual = (
                        from f in fretes
                        where (frete == null || f.Id == frete.Id) && situacoes.Contains(f.Situacao)
                        select f
                    ).FirstOrDefault();
                    if (freteAtual != null)
                    {
                        regraFrete.gravarAtual(freteAtual);
                    }
                    else {
                        regraFrete.limparAtual();
                    }
                }
                else {
                    regraFrete.limparAtual();
                }
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();
                var freteInfo = regraFrete.pegarAtual();
                if (motorista != null) {
                    if (motorista.Situacao == MotoristaSituacaoEnum.AguardandoAprovacao) {
                        UserDialogs.Instance.Alert("Conta de motorista aguardando aprovação!", "Aviso", "Entendi");
                    }
                    else {
                        if (freteInfo != null) {
                            var acompanhaPage = CaronaUtils.acompanharComoMotorista(freteInfo);
                            ((RootPage)App.Current.MainPage).PaginaAtual = acompanhaPage;
                        }
                        else {
                            CaronaUtils.buscarFreteComoMotorista(false);
                        }
                    }
                }
                else {
                    if (freteInfo != null) {
                        var acompanhaPage = CaronaUtils.acompanharComoCliente(freteInfo);
                        ((RootPage)App.Current.MainPage).PaginaAtual = acompanhaPage;
                    }
                    else {
                        var mapaPage = CaronaUtils.criar();
                        ((RootPage)App.Current.MainPage).PaginaAtual = mapaPage;
                    }
                }
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception e)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
            }
        }

        public static MapaAcompanhaPage acompanharComoMotorista(FreteInfo frete) {
            var regraFrete = FreteFactory.create();
            regraFrete.gravarAtual(frete);

            var situacoes = new List<FreteSituacaoEnum>() {
                FreteSituacaoEnum.PegandoEncomenda,
                FreteSituacaoEnum.Entregando
            };

            var acompanhaPage = new MapaAcompanhaPage {
                Title = "Acompanhar viagem",
                Duracao = frete.Duracao,
                DuracaoVisivel = situacoes.Contains(frete.Situacao)
            };
            acompanhaPage.Appearing += (sender, e) => {
                CaronaUtils.AcompanhaPageAtual = (MapaAcompanhaPage)sender;
            };
            acompanhaPage.Disappearing += (sender, e) => {
                CaronaUtils.AcompanhaPageAtual = null;
            };
            //((RootPage)App.Current.MainPage).PushAsync(acompanhaPage);
            //((RootPage)App.Current.MainPage).PaginaAtual = acompanhaPage;
            return acompanhaPage;
        }

        public static MapaAcompanhaPage acompanharComoCliente(FreteInfo frete)
        {
            var regraFrete = FreteFactory.create();
            regraFrete.gravarAtual(frete);

            var situacoes = new List<FreteSituacaoEnum>() {
                FreteSituacaoEnum.PegandoEncomenda,
                FreteSituacaoEnum.Entregando
            };

            var acompanhaPage = new MapaAcompanhaPage
            {
                Title = "Acompanhar viagem",
                Duracao = frete.Duracao,
                DuracaoVisivel = situacoes.Contains(frete.Situacao)
            };
            acompanhaPage.Appearing += acompanhaAppearing;
            acompanhaPage.Disappearing += (sender, e) => {
                CaronaUtils.Acompanhando = false;
            };
            //((RootPage)App.Current.MainPage).PushAsync(acompanhaPage);
            //((RootPage)App.Current.MainPage).PaginaAtual = acompanhaPage;
            return acompanhaPage;
        }

        private static async void atualizarMapa(MapaAcompanhaPage acompanhaPage, FreteInfo frete) {
            var regraFrete = FreteFactory.create();
            var retorno = await regraFrete.atualizar(frete.Id);
            if (retorno != null)
            {
                Device.BeginInvokeOnMainThread(() =>
                {
                    var mapaRota = new MapaRotaInfo
                    {
                        Distancia = retorno.Distancia,
                        DistanciaStr = retorno.DistanciaStr,
                        Tempo = retorno.Tempo,
                        TempoStr = retorno.TempoStr,
                        PolylineStr = retorno.Polyline,
                        PosicaoAtual = new Mapa.Model.LocalInfo
                        {
                            Latitude = retorno.Latitude,
                            Longitude = retorno.Longitude
                        },
                        Polyline = MapaUtils.decodePolyline(retorno.Polyline)
                    };
                    if (string.IsNullOrEmpty(retorno.Polyline)) {
                        mapaRota.Polyline = new List<Position>();
                        foreach (var local in frete.Locais) {
                            mapaRota.Polyline.Add(new Position(local.Latitude.GetValueOrDefault(), local.Longitude.GetValueOrDefault()));
                        }
                    }
                    acompanhaPage.atualizarMapa(mapaRota);
                });
            }
        }

        private static bool executarAcompanhamento(MapaAcompanhaPage acompanhaPage) {
            var regraFrete = FreteFactory.create();
            var frete = regraFrete.pegarAtual();
            if (frete == null) {
                return false;
            }
            atualizarMapa(acompanhaPage, frete);
            return CaronaUtils.Acompanhando;
        }

        private static void acompanhaAppearing(object sender, EventArgs e)
        {
            CaronaUtils.Acompanhando = true;
            var acompanhaPage = (MapaAcompanhaPage)sender;
            if (executarAcompanhamento(acompanhaPage)) {
                Device.StartTimer(new TimeSpan(0, 0, GPSUtils.Current.TempoMinimo), () => {
                    return executarAcompanhamento(acompanhaPage);
                });
            }
        }

        public static async void atualizarPosicao(GPSLocalInfo local) {
            try
            {
                //var regraFrete = FreteFactory.create();
                //var frete = regraFrete.pegarAtual();
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();

                //if (motorista != null && frete != null)
                if (motorista != null)
                {
                    var retorno = await regraMotorista.atualizar(new MotoristaEnvioInfo
                    {
                        IdMotorista = motorista.Id,
                        Latitude = local.Latitude,
                        Longitude = local.Longitude,
                        CodDisponibilidade = 1
                    });
                    if (AcompanhaPageAtual != null)
                    {
                        var mapaRota = new MapaRotaInfo
                        {
                            Distancia = retorno.Distancia.HasValue ? retorno.Distancia.Value : 0,
                            DistanciaStr = retorno.DistanciaStr,
                            Tempo = retorno.Tempo.HasValue ? retorno.Tempo.Value : 0,
                            TempoStr = retorno.TempoStr,
                            PolylineStr = retorno.Polyline,
                            PosicaoAtual = new Mapa.Model.LocalInfo
                            {
                                Latitude = local.Latitude,
                                Longitude = local.Longitude
                            },
                            Polyline = MapaUtils.decodePolyline(retorno.Polyline)
                        };
                        if (string.IsNullOrEmpty(retorno.Polyline) && retorno.IdFrete.HasValue)
                        {
                            var regraFrete = FreteFactory.create();
                            var frete = await regraFrete.pegar(retorno.IdFrete.Value);
                            mapaRota.Polyline = new List<Position>();
                            foreach (var freteLocal in frete.Locais)
                            {
                                mapaRota.Polyline.Add(new Position(freteLocal.Latitude.GetValueOrDefault(), freteLocal.Longitude.GetValueOrDefault()));
                            }
                        }
                        if (AcompanhaPageAtual != null)
                        {
                            AcompanhaPageAtual.atualizarMapa(mapaRota);
                        }
                    }
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
            }
        }

        public static MapaRotaPage criar() {
            var mapaPage = new MapaRotaPage
            {
                Title = "Selecione a rota",
                TituloPadrao = "Parte do trageto",
                IniciarEmPosicaoAtual = true
            };
            mapaPage.AoSelecionar += (sender, posicoes) => {
                var frete = new FreteInfo();
                foreach (var posicao in posicoes) {
                    frete.Locais.Add(new FreteLocalInfo
                    {
                        Tipo = FreteLocalTipoEnum.Parada,
                        Latitude = posicao.Latitude,
                        Longitude = posicao.Longitude
                    });
                }
                var caronaPage = new CaronaFormPage
                {
                    Title = "Novo Atendimento",
                    AgendamentoObrigatorio = true,
                    TipoVeiculoExtra = false,
                    PrecoVisivel = false,
                    Frete = frete
                };
                caronaPage.AoCadastrar += async (s2, f) => {
                    await ((Page)s2).Navigation.PushAsync(new FretePage
                    {
                        Title = frete.SituacaoStr,
                        Frete = f
                    });
                };
                ((Page)sender).Navigation.PushAsync(caronaPage);
            };
            //((RootPage)App.Current.MainPage).PushAsync(mapaPage);
            return mapaPage;
        }

        public static async void listarMeuFreteComoCliente() {

            UserDialogs.Instance.ShowLoading("carregando...");
            try
            {
                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();
                var regraFrete = FreteFactory.create();
                var fretes = await regraFrete.listar(usuario.Id);
                var freteListaPage = new FreteListaPage {
                    Title = "Meus atendimentos",
                    Fretes = fretes,
                    FiltroBotao = false,
                    NovoBotao = false
                };
                UserDialogs.Instance.HideLoading();
                //((RootPage)App.Current.MainPage).PushAsync(freteListaPage);
                ((RootPage)App.Current.MainPage).PaginaAtual = freteListaPage;
            }
            catch (Exception e) {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
            }
        }

        public static async void listarMeuFreteComoMotorista()
        {

            UserDialogs.Instance.ShowLoading("carregando...");
            try
            {
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();
                var regraFrete = FreteFactory.create();
                var fretes = await regraFrete.listar(0, motorista.Id);
                var freteListaPage = new FreteListaPage
                {
                    Title = "Meus atendimentos",
                    Fretes = fretes,
                    FiltroBotao = false,
                    NovoBotao = false
                };
                freteListaPage.setItemTemplate(typeof(FreteMotoristaCell));
                UserDialogs.Instance.HideLoading();
                //((RootPage)App.Current.MainPage).PushAsync(freteListaPage);
                ((RootPage)App.Current.MainPage).PaginaAtual = freteListaPage;
            }
            catch (Exception e)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
            }
        }

        public static async void buscarFreteComoMotorista(bool carregando = true)
        {
            if (carregando) {
                UserDialogs.Instance.ShowLoading("carregando...");
            }
            try
            {
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();
                var regraFrete = FreteFactory.create();
                var fretes = await regraFrete.listar(0, 0, FreteSituacaoEnum.ProcurandoMotorista);
                regraFrete.atualizarPreco(fretes);
                var freteListaPage = new FreteListaPage
                {
                    Title = "Buscar atendimentos",
                    Fretes = fretes,
                    FiltroBotao = false,
                    NovoBotao = false
                };
                if (carregando) {
                    UserDialogs.Instance.HideLoading();
                }
                //((RootPage)App.Current.MainPage).PushAsync(freteListaPage);
                ((RootPage)App.Current.MainPage).PaginaAtual = freteListaPage;
            }
            catch (Exception e)
            {
                if (carregando) {
                    UserDialogs.Instance.HideLoading();
                }
                await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
            }
        }

        public static LoginPage gerarLogin(Action aoLogar)
        {
            var loginPage = new LoginPage
            {
                Title = "Entrar"
            };
            loginPage.AoLogar += async (sender, usuario) => {
                if (usuario == null)
                {

                    await ((Page)sender).DisplayAlert("Erro", "Usuário não informado.", "Fechar");
                    return;
                }
                var regraMotorista = MotoristaFactory.create();
                var motorista = await regraMotorista.pegar(usuario.Id);
                if (motorista != null)
                {
                    regraMotorista.gravarAtual(motorista);
                }
                aoLogar?.Invoke();
                //App.Current.MainPage = App.gerarRootPage(new PrincipalPage());
            };
            return loginPage;
        }

        public static void criarUsuario(Action aoCriar)
        {
            var cadastroPage = UsuarioFormPageFactory.create();
            if (!(cadastroPage is FreteUsuarioFormPage))
            {
                throw new Exception("A página de cadastro precisa ser do tipo FreteUsuarioFormPage.");
            }
            ((FreteUsuarioFormPage)cadastroPage).AoCadastrarMotorista += (s1, m1) => {
                var motoristaPage = CadastroMotoristaPageFactory.create();
                motoristaPage.Usuario = m1;
                motoristaPage.AoCompletar += (s2, motorista) =>
                {
                    aoCriar?.Invoke();
                };
                ((Page)s1).Navigation.PushAsync(motoristaPage);
            };
            ((FreteUsuarioFormPage)cadastroPage).AoCadastrarEmpresa += (s2, u2) =>
            {
                /*
                var empresaPage = new CadastroEmpresaPage(u2);
                empresaPage.AoCompletar += (s3, usuario) =>
                {
                    aoCriar?.Invoke();
                };
                ((Page)s2).Navigation.PushAsync(empresaPage);
                */
                aoCriar?.Invoke();
            };
            if (App.Current.MainPage is RootPage)
            {
                ((RootPage)App.Current.MainPage).PushAsync(cadastroPage);
            }
            else {
                App.Current.MainPage = new IconNavigationPage(cadastroPage);
            }
        }
    }
}
