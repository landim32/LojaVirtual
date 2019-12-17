using Emagine.CRM.Factory;
using Emagine.CRM.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.CRM.Bot
{
    public class WorkanaProjetoBot : WorkanaBaseBot
    {
        //private const string WORKANA_EMAIL = "contato@workana.com";

        private async Task<int> pegarIdCliente() {
            var regraCliente = ClienteFactory.create();
            var cliente = await regraCliente.pegarPorEmail(WORKANA_EMAIL);
            if (cliente != null)
            {
                adicionarLog("Cliente 'Workana' carregado da API!");
                return cliente.IdCliente;
            }
            else {
                cliente = new ClienteInfo();
                cliente.Nome = "Workana";
                cliente.Email1 = WORKANA_EMAIL;
                cliente.CodSituacao = 1;

                adicionarLog("Cliente 'Workana' incluído com sucesso!");
                return await regraCliente.inserir(cliente);
            }
        }

        private async Task<int> inserirAtendimento(string url) {

            double valorProposta = pegarValorProposta(getInnerHtml("h3.budget"));

            var atendimento = new AtendimentoInfo();
            atendimento.IdCliente = await pegarIdCliente();
            atendimento.IdUsuario = 1;
            atendimento.Titulo = getInnerText("h1");
            atendimento.Url = url;
            atendimento.Andamentos = new List<AndamentoInfo>();


            var andamento = new AndamentoInfo();
            andamento.IdCliente = atendimento.IdCliente;
            andamento.Mensagem = getInnerHtml("div.expander");
            andamento.DataInclusao = pegarData(getInnerText(".date strong.text-primary"));
            andamento.ValorProposta = valorProposta;
            andamento.CodSituacao = 1;
            atendimento.Andamentos.Add(andamento);

            /*
            var andamentoResposta = new AndamentoInfo();
            andamentoResposta.IdUsuario = 1;
            andamento.Mensagem = getMensagemPadrao();
            andamento.ValorProposta = valorProposta;
            andamento.CodSituacao = 1;
            atendimento.Andamentos.Add(andamentoResposta);
            */

            var regraAtendimento = AtendimentoFactory.create();
            int id_atendimento = await regraAtendimento.inserir(atendimento);
            adicionarLog("Novo projeto cadastrado com sucesso!");
            return id_atendimento;
        }

        private async Task<bool> processarProjeto(string url) {
            navegar(url);
            aguardandoCarregamento();

            AtendimentoInfo atendimento = null;

            var regraAtendimento = AtendimentoFactory.create();
            atendimento = await regraAtendimento.pegarPorUrl(url);

            if (atendimento == null) {
                await inserirAtendimento(url);
            }
            var regraProjeto = ProjetoFactory.create();
            await regraProjeto.excluirUrl(url);
            adicionarLog(string.Format("URL {0} excluída.", url));

            return false;
        }

        protected override async Task<bool> processar()
        {
            if (!logar())
            {
                return false;
            }
            adicionarLog("Buscando URLs...");
            var regraProjeto = ProjetoFactory.create();
            for (int i = 0; i < 1000; i++) {
                string url = await regraProjeto.pegarProximaUrl(WORKANA_URL);
                if (!string.IsNullOrEmpty(url))
                {
                    adicionarLog(string.Format("URL {0} captada.", url));
                    await processarProjeto(url);
                }
            }

            return true;
        }

        public override Task<bool> processarPagina() {
            return Task.Run(() => { return false; });
        }
    }
}
