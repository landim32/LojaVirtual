using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Text;

namespace Emagine.Banner.Model
{
    public class BannerPecaInfo
    {
        [JsonProperty("id_peca")]
        public int Id { get; set; }

        [JsonProperty("id_banner")]
        public int IdBanner { get; set; }

        [JsonProperty("id_loja")]
        public int IdLoja { get; set; }

        [JsonProperty("id_produto")]
        public int? IdProduto { get; set; }

        [JsonProperty("cod_destino")]
        public int? _CodDestino {
            get {
                return (int)Destino;
            }
            set {
                if (value.HasValue) {
                    switch (value.Value) {
                        case 1:
                            Destino = BannerDestinoEnum.Loja;
                            break;
                        case 2:
                            Destino = BannerDestinoEnum.Url;
                            break;
                        default:
                            Destino = BannerDestinoEnum.Produto;
                            break;
                    }
                }
                else {
                    Destino = BannerDestinoEnum.Produto;
                }
            }
        }

        [JsonIgnore]
        public BannerDestinoEnum Destino { get; set; }

        [JsonProperty("banner")]
        public BannerInfo Banner { get; set; }

        [JsonProperty("nome")]
        public string Nome { get; set; }

        [JsonProperty("nome_arquivo")]
        public string NomeArquivo { get; set; }

        [JsonProperty("url")]
        public string Url { get; set; }

        [JsonProperty("ordem")]
        public int Ordem { get; set; }

        [JsonProperty("imagem_url")]
        public string ImagemUrl { get; set; }

        [JsonProperty("data_inclusao")]
        public string _DataInclusao {
            get {
                return DataInclusao.ToString("yyyy-MM-dd HH:mm:ss");
            }
            set {
                DateTime data = DateTime.MinValue;
                if (DateTime.TryParseExact(value, "yyyy-MM-dd HH:mm:ss", new CultureInfo("pt-BR"), DateTimeStyles.None, out data)) {
                    DataInclusao = data;
                }
            }
        }

        [JsonProperty("ultima_alteracao")]
        public string _UltimaAlteracao {
            get {
                return UltimaAlteracao.ToString("yyyy-MM-dd HH:mm:ss");
            }
            set {
                DateTime data = DateTime.MinValue;
                if (DateTime.TryParseExact(value, "yyyy-MM-dd HH:mm:ss", new CultureInfo("pt-BR"), DateTimeStyles.None, out data)) {
                    UltimaAlteracao = data;
                }
            }
        }

        [JsonIgnore]
        public DateTime DataInclusao { get; set; }

        [JsonIgnore]
        public DateTime UltimaAlteracao { get; set; }
    }
}
