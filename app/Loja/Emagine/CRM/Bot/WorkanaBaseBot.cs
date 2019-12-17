using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;

namespace Emagine.CRM.Bot
{
    public abstract class WorkanaBaseBot: ChromiumBot
    {
        protected const string WORKANA_URL = "https://www.workana.com";
        protected const string WORKANA_EMAIL = "contato@emagine.com.br";
        protected const string WORKANA_SENHA = "h1r6mh3170r";

        public bool logar()
        {
            const string url_login = WORKANA_URL + "/login";
            const string url_dashboard = WORKANA_URL + "/dashboard";
            if (getUrl() != url_login)
            {
                navegar(url_login);
                aguardandoCarregamento();
            }
            if (getUrl() != url_dashboard)
            {
                if (!exist("#Email"))
                {
                    adicionarLog(string.Format("Item não encontrado '{0}' no HTML.", "#Email"));
                    return false;
                }
                if (!exist("#Password"))
                {
                    adicionarLog(string.Format("Item não encontrado '{0}' no HTML.", "#Password"));
                    return false;
                }
                setValueByID("Email", WORKANA_EMAIL);
                setValueByID("Password", WORKANA_SENHA);
                click(".btn-primary");
                aguardandoCarregamento();
            }
            return true;
        }

        protected DateTime pegarData(string data) {
            const string ONTEM = "ontem";
            const string REGEX_ANO = @"há (1|um) ano";
            const string REGEX_ANOS = @"há (\d+) anos";
            const string REGEX_MES = @"há (1|um) mês";
            const string REGEX_MESES = @"há (\d+) meses";
            const string REGEX_HORAS = @"há (\d+) hora(s|)";
            const string REGEX_DIAS = @"há (\d+) dia(s|)";
            const string REGEX_SEMANAS = @"há (\d+) semana(s|)";
            DateTime retorno = DateTime.Now;
            string dataStr = data.Trim().ToLower();
            if (string.Compare(dataStr, ONTEM) == 0) {
                retorno = retorno.AddDays(-1);
            }
            else if (Regex.IsMatch(dataStr, REGEX_ANO))
            {
                retorno = retorno.AddDays(-365);
            }
            else if (Regex.IsMatch(dataStr, REGEX_MES)) { 
                retorno = retorno.AddDays(-30);
            }
            else if (Regex.IsMatch(dataStr, REGEX_ANOS))
            {
                var match = Regex.Match(dataStr, REGEX_ANOS);
                string ano = match.Groups[1].Value;
                retorno = retorno.AddDays(-int.Parse(ano) * 365);
            }
            else if (Regex.IsMatch(dataStr, REGEX_MESES))
            {
                var match = Regex.Match(dataStr, REGEX_MESES);
                string mes = match.Groups[1].Value;
                retorno = retorno.AddDays(-int.Parse(mes) * 30);
            }
            else if (Regex.IsMatch(dataStr, REGEX_SEMANAS))
            {
                var match = Regex.Match(dataStr, REGEX_SEMANAS);
                string semana = match.Groups[1].Value;
                retorno = retorno.AddDays(-int.Parse(semana) * 7);
            }
            else if (Regex.IsMatch(dataStr, REGEX_DIAS))
            {
                var match = Regex.Match(dataStr, REGEX_DIAS);
                string dias = match.Groups[1].Value;
                retorno = retorno.AddDays(-int.Parse(dias));
            }
            else if (Regex.IsMatch(dataStr, REGEX_HORAS))
            {
                var match = Regex.Match(dataStr, REGEX_HORAS);
                string horas = match.Groups[1].Value;
                retorno = retorno.AddHours(-int.Parse(horas));
            }
            else {
                throw new Exception(string.Format("{0} não bate com nenhum texto esperado.", dataStr));
            }
            return retorno;
        }

        protected virtual double pegarValorProposta(string valorStr)
        {
            string str = valorStr;
            const string MENOS_DE = "Menos de R$ ";
            const string MAIS_DE = "Mais de R$ ";
            const string ABERTO = "Aberto";
            if (string.Compare(ABERTO, str, true) == 0) {
                str = "0";
            }
            else if (str.StartsWith(MENOS_DE))
            {
                str = str.Substring(MENOS_DE.Length);
            }
            else if (str.StartsWith(MAIS_DE))
            {
                str = str.Substring(MAIS_DE.Length);
            }
            else if (str.IndexOf('-') >= 0)
            {
                str = str.Substring(str.IndexOf('-') + 1);
            }
            else
            {
                throw new Exception(string.Format("Não encontrei um padrão de valor da proposta ({0}).", str));
            }
            double retorno = 0;
            if (!double.TryParse(str.Trim(), out retorno)) 
            {
                retorno = 0;
                //throw new Exception(string.Format("Não foi possível converter o valor da proposta ({0}).", str));
            }
            return retorno;
        }


    }
}
