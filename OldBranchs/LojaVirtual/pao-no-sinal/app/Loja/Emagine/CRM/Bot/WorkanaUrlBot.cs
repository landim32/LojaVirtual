using Emagine.CRM.Factory;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;
using System.Web;
using System.Windows.Forms;

namespace Emagine.CRM.Bot
{
    public class WorkanaUrlBot : WorkanaBaseBot
    {
        public string Skill { get; set; }

        private const int MAX_POR_PAGINA = 50;

        private List<string> _urls =  new List<string>();

        protected async Task<int> captarUrl(string html) {
            int quantidade = 0;
            adicionarLog("Captando novas urls...");
            string pattern = @"\/messages\/bid\/([a-z,A-Z,0-9,\-]+)\?ref=projects";
            var matches = Regex.Matches(html, pattern);
            foreach (Match match in matches)
            {
                string url = WORKANA_URL + match.Value;
                if (!_urls.Contains(url)) { 
                    //adicionarLog(string.Format("Adicionando nova URL '{0}'...", url));
                    _urls.Add(url);
                    var regraProjeto = ProjetoFactory.create();
                    int id_projeto = await regraProjeto.inserirUrl(url);
                    if (id_projeto > 0) {
                        adicionarLog(string.Format("URL adicionada com sucesso '{0}'!", url));
                    }
                    quantidade++;
                }
            }
            return quantidade;
        }

        protected async Task<bool> executarBusca(string skills) {
            const string url = WORKANA_URL + "/jobs?publication=any&language=en%2Cpt";
            //jobs?ref=menu_projects_index&publication=any&language=en%2Cpt&category=it-programming&page=2
            navegar(url + "&skills=" + HttpUtility.UrlEncode(skills));
            aguardandoCarregamento();
            click(".js-pagination-next");
            aguardar(5000);
            await captarUrl(getHtml());
            int c = 0;
            //while (exist(".js-pagination-next"))
            for (var i = 0; i < MAX_POR_PAGINA; i++) {
                if (await captarUrl(getHtml()) == 0) {
                    c++;
                }
                click(".js-pagination-next");
                aguardar(5000);
                if (c >= 5) {
                    break;
                }

            }
            await captarUrl(getHtml());
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
            await executarBusca(Skill);
            /*
            executarBusca("phonegap");
            executarBusca("xamarin");
            executarBusca("android");
            executarBusca("swift");
            executarBusca("mobile-development");
            */
            //executarBusca("javascript,jquery,html5,web-development");
            //executarBusca("c-1,net,sql,sql-server");
            //executarBusca("php,mysql,less-sass-scss,web-development");
            MessageBox.Show("Finalizado!");
            return true;
        }

        public static IDictionary<string,string> Skills {
            get {
                var skills = new Dictionary<string,string>();
                skills.Add("phonegap,xamarin", "Phonegap e Xamarin");
                skills.Add("mobile-development,android,swift", "Mobile (Android e Swift)");
                skills.Add("php", "PHP");
                skills.Add("c-1", "C#");
                skills.Add("net", ".NET");
                return skills;
            }
        }
    }
}
