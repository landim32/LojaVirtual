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
    public class CartaoBLL : RestAPIBase
    {
        public async Task<List<CartaoInfo>> listar(int idUsuario)
        {
            string url = GlobalUtils.URLAplicacao + "/api/cartao/listar/" + idUsuario.ToString();
            return await queryGet<List<CartaoInfo>>(url);
        }

        public Task excluir(int idCartao)
        {
            string url = GlobalUtils.URLAplicacao + "/api/cartao/excluir/" + idCartao.ToString();
            return execGet(url);
        }

        public BandeiraCartaoEnum? pegarBandeiraPorNumeroCartao(string numero)
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
            return null;
        }

        public List<string> listarValidadeCartao()
        {
            var ret = new List<string>();
            for (var i = DateTime.Now.Year; i <= DateTime.Now.Year + 10; i++)
            {
                for (var j = DateTime.Now.Month; j <= 12; j++)
                {
                    ret.Add(j.ToString("d2") + "/" + i);
                }
            }
            return ret;
        }

        public DateTime pegarDataExpiracao(string texto)
        {
            DateTime data = DateTime.MinValue;
            if (DateTime.TryParseExact("01/" + texto, "dd/MM/yyyy", new CultureInfo("pt-BR"), DateTimeStyles.None, out data))
            {
                return data;
            }
            return data;
        }
    }
}
