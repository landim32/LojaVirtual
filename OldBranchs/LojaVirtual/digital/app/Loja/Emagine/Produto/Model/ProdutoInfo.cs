using Emagine.Login.Model;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Model
{
    public class ProdutoInfo
    {
        [JsonProperty("id_produto")]
        public int Id { get; set; }

        [JsonProperty("id_loja")]
        public int IdLoja { get; set; }

        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }

        [JsonProperty("usuario")]
        public UsuarioInfo Usuario { get; set; }

        [JsonProperty("id_categoria")]
        public int IdCategoria { get; set; }

        [JsonProperty("id_unidade")]
        public int? IdUnidade { get; set; }

        [JsonProperty("categoria")]
        public CategoriaInfo Categoria { get; set; }

        [JsonProperty("unidade")]
        public UnidadeInfo Unidade { get; set; }

        [JsonProperty("slug")]
        public string Slug { get; set; }

        [JsonProperty("codigo")]
        public string Codigo { get; set; }

        [JsonProperty("foto")]
        public string Foto { get; set; }

        [JsonProperty("foto_base64")]
        public string FotoBase64 { get; set; }

        [JsonProperty("foto_url")]
        public string FotoUrl { get; set; }

        [JsonProperty("nome")]
        public string Nome { get; set; }

        [JsonProperty("valor")]
        public double Valor { get; set; }

        [JsonProperty("valor_promocao")]
        public double ValorPromocao { get; set; }

        [JsonProperty("volume")]
        public double Volume { get; set; }

        [JsonProperty("volume_str")]
        public string VolumeStr { get; set; }

        [JsonProperty("destaque")]
        public bool Destaque { get; set; }

        [JsonProperty("descricao")]
        public string Descricao { get; set; }

        [JsonProperty("quantidade")]
        public int Quantidade { get; set; }

        [JsonProperty("quantidade_vendido")]
        public int QuantidadeVendido { get; set; }

        [JsonIgnore]
        public int QuantidadeCarrinho { get; set; }

        [JsonProperty("url")]
        public string Url { get; set; }

        [JsonProperty("cod_situacao")]
        public int _CodSituacao {
            get {
                return (int)Situacao;
            }
            set {
                Situacao = (SituacaoEnum)value;
            }
        }

        [JsonIgnore]
        public SituacaoEnum Situacao { get; set; }

        [JsonIgnore]
        public double ValorFinal {
            get {
                if (ValorPromocao > 0) {
                    return ValorPromocao;
                }
                else {
                    return Valor;
                }
            }
        }

        [JsonIgnore]
        public bool EmPromocao {
            get {
                return (ValorPromocao > 0);
            }
        }

        /*
        [JsonIgnore]
        public Color PromocaoCor {
            get {
                return (EmPromocao) ? Estilo.Current.DangerColor : Estilo.Current.DefaultColor;
            }
        }
        */
    }
}
