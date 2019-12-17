using Emagine.CRM.Factory;
using Emagine.CRM.Model;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using HtmlAgilityPack;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;

namespace Emagine.CRM.Bot
{
    public class WorkanaBot : WorkanaBaseBot
    {
        const string REGEX_TITULO = "<h2\\s+class\\=\\\"h2 project\\-title\\\">\\s+<a href\\=\\\".*\\\"><span title\\=\\\"(.*?)\\\">";
        const string REGEX_DATA = "<span\\s+class=\"date\"\\s+title=\"(.*?)\">";
        const string REGEX_DESCRICAO1 = "<div class\\=\\\"html\\-desc project\\-details\\\">\\s+<div class=\\\"expander js\\-expander\\-passed\\\"\\s+data\\-text\\-expand\\=\\\"Ver mais detalhes\\\".*?>(.*?)<span class\\=\\\"expander\\-more\\\">";
        //const string REGEX_DESCRICAO2 = "<div class\\=\\\"html\\-desc project\\-details\\\">.*?<span class=\"expander\\-details\" style=\"display: none; \">(.*?)<span class\\=\\\"expander\\-less\\\">";
        //const string REGEX_DESCRICAO2 = "<span\\s+class=\"expander\\-details\"\\s+style=\"display:\\s+none;\\s+\">(.*?)<span\\s+class\\=\\\"expander\\-less\\\">";
        const string REGEX_DESCRICAO2 = "<span class=\"expander-details\" style=\"display: none;\">(.*?)<span class=\"expander-less\">";
        const string REGEX_DESCRICAO3 = "<div class\\=\\\"html\\-desc project\\-details\\\">\\s+<div class=\\\"expander js\\-expander\\-passed\\\"\\s+data\\-text\\-expand\\=\\\"Ver mais detalhes\\\".*?>(.*?)<\\/div>";
        const string REGEX_TAG = "<a href\\=\\\".*?\\\" class\\=\\\"skill label label\\-info\\\" title\\=\\\".*?\\\">(.*?)</a>";
        const string REGEX_URL = "<h2 class\\=\\\"h2 project\\-title\\\">\\s+<a href\\=\\\"(.*?)\\\"><span title\\=\\\".*?\\\">.*?</a>\\s+</h2>";
        const string REGEX_PRECO1 = "<h4\\s+class=\"budget\"><span\\s+class=\"value\">(.*?)<a href=\"javascript:void\\(0\\);\"";
        const string REGEX_PRECO2 = "(R\\$\\s+[0-9,\\.]+\\s+\\-|Menos\\s+de\\s+R\\$|Mais\\s+de\\s+R\\$)\\s+([0-9,\\.]+)";

        protected override async Task<bool> processar()
        {
            if (!logar())
            {
                return false;
            }
            adicionarLog("Buscando URLs...");
            int pg = 1;
            do
            {
                string url = "https://www.workana.com/jobs?ref=menu_projects_index&publication=any&language=en%2Cpt&category=it-programming";
                if (pg > 1) {
                    url += "&page=" + pg.ToString();
                }
                navegar(url);
                aguardandoCarregamento();
                if (!_emExecucao) {
                    break;
                }
                pg++;
            }
            while (await processarPagina());
            adicionarLog("* WorkanaBot processado com sucesso!");

            /*
            var regraProjeto = ProjetoFactory.create();
            for (int i = 0; i < 1000; i++)
            {
                string url = await regraProjeto.pegarProximaUrl(WORKANA_URL);
                if (!string.IsNullOrEmpty(url))
                {
                    adicionarLog(string.Format("URL {0} captada.", url));
                    await processarProjeto(url);
                }
            }
            */
            return true;
        }

        private async Task<UsuarioInfo> pegarUsuario() {
            var regraUsuario = UsuarioFactory.create();
            var usuarios = await regraUsuario.listar();
            if (usuarios != null && usuarios.Count() > 0) {
                return usuarios.FirstOrDefault();
            }
            return null;
        }

        private async Task<int> pegarIdCliente(UsuarioInfo usuario)
        {
            var regraCliente = ClienteFactory.create();
            var cliente = await regraCliente.pegarPorEmail(WORKANA_EMAIL);
            if (cliente != null)
            {
                //adicionarLog("Cliente 'Workana' carregado da API!");
                return cliente.IdCliente;
            }
            else
            {
                cliente = new ClienteInfo();
                cliente.IdUsuario = usuario.Id;
                cliente.Nome = "Workana";
                cliente.Email1 = WORKANA_EMAIL;
                cliente.CodSituacao = 1;

                var idCliente = await regraCliente.inserir(cliente);
                adicionarLog("Cliente 'Workana' incluído com sucesso!");
                return idCliente;
            }
        }

        protected override double pegarValorProposta(string html) {
            double valorProposta = 0;
            if (Regex.IsMatch(html, REGEX_PRECO1, RegexOptions.Singleline | RegexOptions.IgnoreCase))
            {
                var match = Regex.Match(html, REGEX_PRECO1, RegexOptions.Singleline | RegexOptions.IgnoreCase);
                var precoStr = match.Groups[1].Value;
                var m = Regex.Match(precoStr, REGEX_PRECO2, RegexOptions.Singleline | RegexOptions.IgnoreCase);
                if (m.Success)
                {
                    precoStr = m.Groups[2].Value;
                    precoStr = precoStr.Replace(".", "");
                    double preco = 0;
                    if (double.TryParse(precoStr, out preco)) {
                        valorProposta = preco;
                    }
                }
            }
            return valorProposta;
        }

        protected async Task<bool> processarProjeto(string html) {

            string titulo = string.Empty, url = string.Empty;
            DateTime dataInclusao = DateTime.Now;
            if (Regex.IsMatch(html, REGEX_URL))
            {
                var match = Regex.Match(html, REGEX_URL);
                url = "https://www.workana.com" +  match.Groups[1].Value;
                //adicionarLog(url);
            }
            if (Regex.IsMatch(html, REGEX_TITULO))
            {
                var match = Regex.Match(html, REGEX_TITULO);
                titulo = match.Groups[1].Value;
                //adicionarLog(titulo);
            }

            if (string.IsNullOrEmpty(titulo)) {
                return false;
            }

            AtendimentoInfo atendimento = null;
            var regraAtendimento = AtendimentoFactory.create();
            atendimento = await regraAtendimento.pegarPorUrl(url);
            if (atendimento != null) {
                adicionarLog(string.Format("Já existe um atendimento para a url {0}.", url));
                return true;
            }
            var usuario = await pegarUsuario();
            if (usuario == null)
            {
                adicionarLog("Nenhum usuário encontrado para o cadastro.");
                return false;
            }

            atendimento = new AtendimentoInfo();
            atendimento.Url = url;
            atendimento.IdUsuario = usuario.Id;
            atendimento.IdCliente = await pegarIdCliente(usuario);
            atendimento.Titulo = titulo;
            atendimento.Andamentos = new List<AndamentoInfo>();
            atendimento.Tags = new List<TagInfo>();

            if (Regex.IsMatch(html, REGEX_DATA, RegexOptions.Singleline | RegexOptions.IgnoreCase)) {
                var match = Regex.Match(html, REGEX_DATA, RegexOptions.Singleline | RegexOptions.IgnoreCase);
                string data = match.Groups[1].Value;
                dataInclusao = DateTime.Parse(data, new CultureInfo("pt-BR", true));
            }
            var descricao = string.Empty;
            if (Regex.IsMatch(html, REGEX_DESCRICAO1, RegexOptions.Singleline | RegexOptions.IgnoreCase)) {
                var match = Regex.Match(html, REGEX_DESCRICAO1, RegexOptions.Singleline | RegexOptions.IgnoreCase);
                descricao = match.Groups[1].Value;
            }
            if (Regex.IsMatch(html, REGEX_DESCRICAO2, RegexOptions.Singleline | RegexOptions.IgnoreCase)) {
                var match = Regex.Match(html, REGEX_DESCRICAO2, RegexOptions.Singleline | RegexOptions.IgnoreCase);
                descricao += " " + match.Groups[1].Value;
            }
            if (string.IsNullOrEmpty(descricao) && Regex.IsMatch(html, REGEX_DESCRICAO3, RegexOptions.Singleline | RegexOptions.IgnoreCase)) {
                var match = Regex.Match(html, REGEX_DESCRICAO3, RegexOptions.Singleline | RegexOptions.IgnoreCase);
                descricao = match.Groups[1].Value;
            }
            if (Regex.IsMatch(html, REGEX_TAG)) {
                var tags = Regex.Matches(html, REGEX_TAG);
                foreach (Match tag in tags) {
                    if (!string.IsNullOrEmpty(tag.Groups[1].Value)) {
                        atendimento.Tags.Add(new TagInfo(tag.Groups[1].Value));
                    }
                }
            }
            if (string.IsNullOrEmpty(descricao)) {
                return false;
            }
            var andamento = new AndamentoInfo();
            andamento.IdCliente = atendimento.IdCliente;
            andamento.DataInclusao = dataInclusao;
            andamento.Mensagem = descricao;
            andamento.ValorProposta = pegarValorProposta(html);
            andamento.CodSituacao = 1;
            atendimento.Andamentos.Add(andamento);
            int id_atendimento = await regraAtendimento.inserir(atendimento);
            adicionarLog(string.Format("{0} - OK!", atendimento.Titulo));
            return true;
        }

        public async override Task<bool> processarPagina() {
            if (!exist("#projects"))
            {
                return false;
            }
            var paginaHtml = getHtml();

            var htmlDocument = new HtmlDocument();
            htmlDocument.LoadHtml(paginaHtml);
            var projects = htmlDocument.GetElementbyId("projects");
            if (projects == null) {
                adicionarLog("GetElementbyId(\"#projects\") retornou null!");
                return false;
            }

            if (projects.ChildNodes.Count == 0) {
                return false;
            }

            int projetoProcessado = 0;
            foreach (var node in projects.ChildNodes) {
                var html = node.OuterHtml;
                try
                {
                    if (await processarProjeto(html)) {
                        projetoProcessado++;
                    }
                }
                catch (Exception erro) {
                    adicionarLog(erro.Message);
                }
            }

            return (projetoProcessado > 0);
        }
    }
}
