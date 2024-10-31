using System;
using Emagine.Login.Model;
using Newtonsoft.Json;

namespace Emagine.Frete.Model
{
    public class MotoristaInfo
    {
        [JsonProperty("id_usuario")]
        public int Id { get; set; }

        [JsonProperty("usuario")]
        public UsuarioInfo Usuario { get; set; }

        [JsonProperty("id_tipo")]
        public int IdTipo { get; set; }

        [JsonProperty("tipo")]
        public TipoVeiculoInfo Tipo { get; set; }

        [JsonProperty("tipo_str")]
        public string TipoStr { get; set; }

        [JsonProperty("id_carroceria")]
        public int? IdCarroceria { get; set; }

        [JsonProperty("carroceria")]
        public TipoCarroceriaInfo Carroceria { get; set; }

        [JsonProperty("carroceria_str")]
        public string CarroceriaStr { get; set; }

        [JsonProperty("foto_carteira")]
        public string FotoCarteira { get; set; }

        [JsonProperty("foto_veiculo")]
        public string FotoVeiculo { get; set; }

        [JsonProperty("foto_endereco")]
        public string FotoEndereco { get; set; }

        [JsonProperty("foto_cpf")]
        public string FotoCpf { get; set; }

        [JsonProperty("foto_carteira_url")]
        public string FotoCarteiraUrl { get; set; }

        [JsonProperty("foto_veiculo_url")]
        public string FotoVeiculoUrl { get; set; }

        [JsonProperty("foto_endereco_url")]
        public string FotoEnderecoUrl { get; set; }

        [JsonProperty("foto_cpf_url")]
        public string FotoCpfUrl { get; set; }

        [JsonProperty("foto_carteira_base64")]
        public string FotoCarteiraBase64 { get; set; }

        [JsonProperty("foto_veiculo_base64")]
        public string FotoVeiculoBase64 { get; set; }

        [JsonProperty("foto_endereco_base64")]
        public string FotoEnderecoBase64 { get; set; }

        [JsonProperty("foto_cpf_base64")]
        public string FotoCpfBase64 { get; set; }

        [JsonProperty("cnh")]
        public string CNH { get; set; }

        [JsonProperty("antt")]
        public string ANTT { get; set; }

        [JsonProperty("placa")]
        public string Placa { get; set; }

        [JsonProperty("veiculo")]
        public string Veiculo { get; set; }

        [JsonProperty("latitude")]
        public float? Latitude { get; set; }

        [JsonProperty("longitude")]
        public float? Longitude { get; set; }

        [JsonProperty("direcao")]
        public float? Direcao { get; set; }

        [JsonProperty("valor_hora")]
        public double ValorHora { get; set; }

        [JsonIgnore]
        public MotoristaSituacaoEnum Situacao { get; set; }

        [JsonProperty("cod_situacao")]
        public int _CodSituacao {
            get {
                return (int) Situacao;
            }
            set {
                Situacao = (MotoristaSituacaoEnum) value;
            }
        }

        [JsonIgnore]
        public MotoristaDisponibilidadeEnum Disponibilidade { get; set; }

        [JsonProperty("cod_disponibilidade")]
        public int _CodDisponibilidade {
          get {
              return (int) Disponibilidade;
          }
          set {
              Disponibilidade = (MotoristaDisponibilidadeEnum) value;
          }
        }
    }
}
