using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Login.Factory;
using Emagine.Pagamento.Model;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;

namespace Emagine.Pagamento.BLL
{
    public class PagamentoBLL : RestAPIBase
    {
        public async Task<PagamentoInfo> pegar(int idPagamento)
        {
            string url = GlobalUtils.URLAplicacao + "/api/pagamento/pegar/" + idPagamento.ToString();
            return await queryGet<PagamentoInfo>(url);
        }

        public async Task<PagamentoRetornoInfo> pagar(PagamentoInfo pagamento)
        {
            string url = GlobalUtils.URLAplicacao + "/api/pagamento/pagar";
            var args = new List<object>();
            args.Add(pagamento);
            return await queryPut<PagamentoRetornoInfo>(url, args.ToArray());
        }

        public async Task<PagamentoRetornoInfo> pagarComToken(PagamentoInfo pagamento)
        {
            string url = GlobalUtils.URLAplicacao + "/api/pagamento/pagar-com-token";
            var args = new List<object>();
            args.Add(pagamento);
            return await queryPut<PagamentoRetornoInfo>(url, args.ToArray());
        }

        public BandeiraCartaoEnum pegarBandeiraPorNumeroCartao(string numero)
        {
            if (new Regex(@"^4[0-9]{12}(?:[0-9]{3})", RegexOptions.IgnoreCase).Match(numero).Success)
            {
                return BandeiraCartaoEnum.Visa;
            }
            if (new Regex(@"^5[1-5][0-9]{14}", RegexOptions.IgnoreCase).Match(numero).Success)
            {
                return BandeiraCartaoEnum.Mastercard;
            }
            if (new Regex(@"^3[47][0-9]{13}", RegexOptions.IgnoreCase).Match(numero).Success)
            {
                return BandeiraCartaoEnum.Amex;
            }
            if (new Regex(@"^3(?:0[0-5]|[68][0-9])[0-9]{11}", RegexOptions.IgnoreCase).Match(numero).Success)
            {
                return BandeiraCartaoEnum.Diners;
            }
            if (new Regex(@"^6(?:011|5[0-9]{2})[0-9]{12}", RegexOptions.IgnoreCase).Match(numero).Success)
            {
                return BandeiraCartaoEnum.Discover;
            }
            if (new Regex(@"^(?:2131|1800|35\d{3})\d{11}", RegexOptions.IgnoreCase).Match(numero).Success)
            {
                return BandeiraCartaoEnum.Jcb;
            }
            if (new Regex(@"^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})$", RegexOptions.IgnoreCase).Match(numero).Success)
            {
                return BandeiraCartaoEnum.Elo;
            }
            if (new Regex(@"^(606282\d{10}(\d{3})?)|(3841\d{15})$", RegexOptions.IgnoreCase).Match(numero).Success)
            {
                return BandeiraCartaoEnum.Hipercard;
            }
            if (new Regex(@"^50\d{14}", RegexOptions.IgnoreCase).Match(numero).Success)
            {
                return BandeiraCartaoEnum.Aura;
            }
            return BandeiraCartaoEnum.Visa;
        }
    }
}
