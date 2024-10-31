using Emagine.CRM.Factory;
using Emagine.CRM.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.CRM.Bot
{
    public class WorkanaPropostaBot : WorkanaBaseBot
    {

        private string getMensagemPadrao()
        {
            var sb = new StringBuilder();
            sb.Append("Me interessei pelo seu projeto.");
            return sb.ToString();
        }

        private bool enviarProposta()
        {
            double valorProposta = pegarValorProposta(getInnerHtml("h3.budget"));

            if (!exist("#BidContent"))
            {
                adicionarLog("Campo para preencher os dados da proposta não encontrado.");
                return false;
            }
            if (!exist("#Amount"))
            {
                adicionarLog("Campo do 'valor líquido a cobrar' não encontrado.");
                return false;
            }
            if (!exist(".wk-submit-block input[type=submit]"))
            {
                adicionarLog("Botão 'Enviar Orçamento' não encontrado.");
                return false;
            }
            setValueByID("BidContent", getMensagemPadrao());
            setValueByID("Amount", valorProposta.ToString());
            click(".wk-submit-block input[type=submit]");
            aguardandoCarregamento();

            return true;
        }

        public override Task<bool> processarPagina()
        {
            return Task.Run(() => { return false; });
        }

        protected async override Task<bool> processar()
        {
            if (!logar())
            {
                return false;
            }
            adicionarLog("Buscando URLs...");
            var regraProjeto = ProjetoFactory.create();
            string url = await regraProjeto.pegarProximaUrl(WORKANA_URL);
            adicionarLog(string.Format("URL {0} captada.", url));

            navegar(url);
            aguardandoCarregamento();

            //AtendimentoInfo atendimento = null;

            var regraAtendimento = AtendimentoFactory.create();
            AtendimentoInfo atendimento = await regraAtendimento.pegarPorUrl(url);

            if (atendimento == null)
            {
                //int id_atendimento = inserirAtendimento(url);

                /*
                if (enviarProposta()) {
                    atendimento = regraAtendimento.pegar(id_atendimento);

                    double valorProposta = pegarValorProposta(getInnerHtml("h3.budget"));

                    var andamento = new AndamentoInfo();
                    andamento.IdUsuario = 1;
                    andamento.Mensagem = getMensagemPadrao();
                    andamento.ValorProposta = valorProposta;
                    andamento.CodSituacao = 2;
                    atendimento.Andamentos.Add(andamento);

                    regraAtendimento.alterar(atendimento);
                }
                */

                return true;
            }
            await regraProjeto.excluirUrl(url);
            adicionarLog(string.Format("URL {0} excluída.", url));

            return true;
        }
    }
}
