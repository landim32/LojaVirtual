using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Emagine.CRM.Bot
{
    public abstract class BaseBot
    {
        //private int _passoAtual = -1;
        protected bool _paginaCarregada = false;
        protected bool _emExecucao = false;
        protected Thread _thread = null;

        protected abstract string executarScript(string script);
        protected abstract void executarNavegacao(string url);
        protected abstract string getUrl();
        protected abstract string getHtml();
        protected abstract void adicionarLog(string mensagem);

        public event EventHandler AoFinalizar;

        protected void navegar(string url) {
            if (_emExecucao) {
                _paginaCarregada = false;
                adicionarLog(string.Format("Navegando na página '{0}'...", url));
                executarNavegacao(url);
            }
        }

        /*
        protected bool elementExistByID(string id) {
            string js = "(document.getElementById('" + id + "')) ? 'true' : 'false';";
            return (executarScript(js) == "true");
        }

        protected bool elementExistByClass(string classe)
        {
            string js = "(document.getElementByClassName('" + classe + "').length > 0) ? 'true' : 'false';";
            return (executarScript(js) == "true");
        }
        */

        protected int count(string selector)
        {
            string js = "document.querySelectorAll('" + selector + "').length;";
            string str = executarScript(js);
            int retorno = 0;
            if (int.TryParse(str, out retorno)) {
                return retorno;
            }
            adicionarLog(string.Format("Buscando '{0}' no HTML, {1} item(ns) encontrato(s).", selector, retorno));
            return 0;
        }

        protected bool exist(string selector)
        {
            string js = "(document.querySelectorAll('" + selector + "').length > 0) ? 'true' : 'false';";
            bool existe = (executarScript(js) == "true");
            if (existe) {
                adicionarLog(string.Format("Item encontrado '{0}' no HTML.", selector));
            }
            else {
                adicionarLog(string.Format("Item não encontrado '{0}' no HTML.", selector));
            }
            return existe;
        }

        /*
        protected void errorIfNotExistByID(string selector) {
            if (!exist(selector)) {
                throw new Exception(string.Format("O ID '{0}' não encontrado.", selector));
            }
        }
        */

        protected string getValueByID(string id)
        {
            StringBuilder js = new StringBuilder();
            js.AppendLine("document.getElementById('" + id + "').value; ");
            return executarScript(js.ToString());
        }

        protected void setValueByID(string id, string value)
        {
            StringBuilder js = new StringBuilder();
            js.AppendLine("document.getElementById('" + id + "').value = '" + value + "'; ");
            executarScript(js.ToString());
        }

        protected void setValueBySelector(string selector, string value)
        {
            StringBuilder js = new StringBuilder();
            js.AppendLine("document.querySelectorAll('" + selector + "')[0].value = '" + value + "'; ");
            executarScript(js.ToString());
        }

        private string filtrarValor(string selector, string texto) {
            string retorno = texto;
            retorno = retorno.Replace("<br>", "\n");
            retorno = retorno.Replace("<br/>", "\n");
            retorno = retorno.Replace("<br />", "\n");
            retorno = retorno.Replace("<BR>", "\n");
            retorno = retorno.Replace("<BR/>", "\n");
            retorno = retorno.Replace("<BR />", "\n");
            Regex regHtml = new Regex("<[^>]*>");
            retorno = regHtml.Replace(retorno, "").Trim();
            retorno = new Regex("[ ]{2,}", RegexOptions.None).Replace(retorno, " ");
            retorno = new Regex("[\n]{2,}", RegexOptions.None).Replace(retorno, "\n");
            string resumo = retorno.Trim();
            if (resumo.Length > 30)
            {
                resumo = resumo.Substring(0, 30) + "...";
            }
            adicionarLog(string.Format("Captando $('{0}') -> '{1}'!", selector, resumo));
            return retorno.Trim();
        }

        protected string getInnerHtml(string selector)
        {
            StringBuilder js = new StringBuilder();
            js.AppendLine("document.querySelectorAll('" + selector + "')[0].innerHTML; ");
            return filtrarValor(selector, executarScript(js.ToString()));
        }

        protected string getInnerText(string selector)
        {
            StringBuilder js = new StringBuilder();
            js.AppendLine("document.querySelectorAll('" + selector + "')[0].innerText; ");
            return filtrarValor(selector, executarScript(js.ToString()));
        }

        protected void clickByID(string id) {
            var js = "document.getElementById('" + id + "').click();";
            executarScript(js);
        }

        protected void click(string selector)
        {
            adicionarLog(string.Format("Clicando no botão '{0}' no HTML.", selector));
            executarScript("document.querySelectorAll('" + selector + "')[0].click();");
        }

        /*
        protected void clickByClass(string classe)
        {
            StringBuilder js = new StringBuilder();
            js.AppendLine("document.querySelectorAll('." + classe + "')[0].click();");
            executarScript(js.ToString());
        }

        protected void formSubmit()
        {
            var js = "document.getElementsByTagName('form').submit();";
            executarScript(js);
        }
        */

        public void carregarPagina() {
            _paginaCarregada = true;
            adicionarLog("Página carregada com sucesso!");
        }

        protected void aguardar(int milesegundo) {
            if (_emExecucao)
            {
                adicionarLog(string.Format("Aguardando {0:N1} segundos...", milesegundo / 1000));
                Thread.Sleep(milesegundo);
                adicionarLog("Prosseguindo...");
            }
        }

        protected bool aguardandoCarregamento() {
            if (_emExecucao)
            {
                adicionarLog("Aguardando carregamento da página...");
                while (!_paginaCarregada)
                {
                    if (!_emExecucao)
                    {
                        break;
                    }
                    Thread.Sleep(1000);
                }
                _paginaCarregada = false;
                return false;
            }
            return true;
        }

        protected abstract Task<bool> processar();
        public abstract Task<bool> processarPagina();

        public void parar() {
            /*
            if (_thread != null) {
                //_thread.Suspend();
                _thread.Abort();
            }
            */
            _emExecucao = false;
            adicionarLog("* Finalizando execução do bot...");
        }

        public void executar() {
            _thread = new Thread(() => {
                //try {
                _emExecucao = true;
                processar();
                /*
                }
                catch (Exception e) {
                    adicionarLog(e.Message);
                    MessageBox.Show(e.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    Thread.CurrentThread.Abort();
                }
                */
                _emExecucao = false;
                //adicionarLog("* Bot finalizado!");
                AoFinalizar?.Invoke(this, new EventArgs());
            });
            //try {
            _thread.Start();
            /*
            }
            catch (Exception e) {
                adicionarLog(e.Message);
                MessageBox.Show(e.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            */
        }


    }
}
