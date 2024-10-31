using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Model
{
    public class CartaoInfo
    {

        [JsonProperty("id_cartao")]
        public int Id { get; set; }

        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }

        [JsonIgnore]
        public BandeiraCartaoEnum Bandeira { get; set; }

        [JsonProperty("bandeira")]
        public int _CodBandeira
        {
            get
            {
                return (int)Bandeira;
            }
            set
            {
                Bandeira = (BandeiraCartaoEnum)value;
            }
        }

        [JsonProperty("nome")]
        public string Nome { get; set; }

        [JsonProperty("token")]
        public string Token { get; set; }

        [JsonProperty("cvv")]
        public string CVV { get; set; }

        [JsonIgnore]
        public string BandeiraStr
        {
            get
            {
                var retorno = "";
                switch (Bandeira)
                {
                    case BandeiraCartaoEnum.Visa:
                        retorno = "VISA";
                        break;
                    case BandeiraCartaoEnum.Amex:
                        retorno = "AMEX";
                        break;
                    case BandeiraCartaoEnum.Aura:
                        retorno = "AURA";
                        break;
                    case BandeiraCartaoEnum.Diners:
                        retorno = "DINERS";
                        break;
                    case BandeiraCartaoEnum.Discover:
                        retorno = "DISCOVER";
                        break;
                    case BandeiraCartaoEnum.Elo:
                        retorno = "ELO";
                        break;
                    case BandeiraCartaoEnum.Hipercard:
                        retorno = "HIPERCARD";
                        break;
                    case BandeiraCartaoEnum.Jcb:
                        retorno = "JCB";
                        break;
                    case BandeiraCartaoEnum.Mastercard:
                        retorno = "MASTERCARD";
                        break;
                }
                return retorno;
            }
        }

        [JsonIgnore]
        public string BandeiraIcone {
            get {
                var retorno = "";
                switch (Bandeira) {
                    case BandeiraCartaoEnum.Visa:
                        retorno = "fa-cc-visa";
                        break;
                    case BandeiraCartaoEnum.Mastercard:
                        retorno = "fa-cc-mastercard";
                        break;
                    case BandeiraCartaoEnum.Diners:
                        retorno = "fa-cc-diners-club";
                        break;
                    case BandeiraCartaoEnum.Jcb:
                        retorno = "fa-cc-jcb";
                        break;
                    case BandeiraCartaoEnum.Discover:
                        retorno = "fa-cc-discover";
                        break;
                    case BandeiraCartaoEnum.Amex:
                        retorno = "fa-cc-amex";
                        break;
                    default:
                        retorno = "fa-credit-card";
                        break;
                }
                return retorno;
            }
        }

    }
}
