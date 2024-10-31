using CefSharp;
using CefSharp.WinForms;
using CefSharp.Internals;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Emagine.CRM.Bot
{
    public abstract class ChromiumBot: BaseBot, IDisposable
    {
        private string _url = string.Empty;
        private ChromiumWebBrowser _browser = null;
        public ListBox LogList { get; set; }

        public ChromiumWebBrowser Browser {
            get {
                return _browser;
            }
            set {
                if (_browser != null) {
                    desvincularBrowser(_browser);
                }
                _browser = value;
                if (_browser != null) {
                    vincularBrowser(_browser);
                }
            }
        }

        protected override void executarNavegacao(string url)
        {
            _url = url;
            if (Browser.InvokeRequired)
            {
                MethodInvoker metodo = delegate () { Browser.Load(url); };
                Browser.Invoke(metodo);
            }
            else
            {
                Browser.Load(url);
            }
        }

        protected override string getUrl() {
            string retorno = "";
            if (Browser.InvokeRequired) {
                retorno = (string)Browser.Invoke(
                   new Func<string>(() => {
                       return _url;
                   })
                );
            }
            else {
                retorno = _url;
            }
            return retorno;
        }

        protected override string getHtml()
        {
            return executarScript("document.documentElement.innerHTML;");
        }

        protected override string executarScript(string script)
        {
            string retorno = null;
            if (Browser.InvokeRequired)
            {
                retorno = (string)Browser.Invoke(
                   new Func<string>(() => {
                       try
                       {
                           //var js = Browser.GetScriptManager.EvaluateScript(script);
                           var task = Browser.EvaluateScriptAsync(script);
                           task.Wait();
                           JavascriptResponse response = task.Result;
                           if (response.Success) {
                               return (response.Result != null) ? response.Result.ToString() : "";
                           } 
                       }
                       catch (AccessViolationException e) {
                           return e.Message;
                       }
                       return "";

                   })
                );
            }
            else {
                //var js = Browser.GetScriptManager.EvaluateScript(script);
                var task = Browser.EvaluateScriptAsync(script);
                task.Wait();
                JavascriptResponse response = task.Result;
                if (response.Success) {
                    return (response.Result != null) ? response.Result.ToString() : "";
                }
            }
            return retorno;
        }

        private void vincularBrowser(ChromiumWebBrowser browser) {
            browser.AddressChanged += browserAddressChanged;
            browser.LoadingStateChanged += browserLoadingStateChanged;
        }

        private void desvincularBrowser(ChromiumWebBrowser browser) {
            browser.AddressChanged -= browserAddressChanged;
            browser.LoadingStateChanged -= browserLoadingStateChanged;
        }

        private void browserAddressChanged(object sender, CefSharp.AddressChangedEventArgs e) {
            _url = e.Address;
        }

        private void browserLoadingStateChanged(object sender, LoadingStateChangedEventArgs e)
        {
            if (!e.IsLoading)
            {
                carregarPagina();
            }
        }

        protected override void adicionarLog(string mensagem) {
            if (Browser.InvokeRequired)
            {
                MethodInvoker metodo = delegate () { LogList.TopIndex = LogList.Items.Add(mensagem); };
                Browser.Invoke(metodo);
            }
            else
            {
                LogList.TopIndex = LogList.Items.Add(mensagem);
            }
        }

        public void Dispose()
        {
            if (_browser != null) {
                desvincularBrowser(_browser);
            }
        }
    }
}
