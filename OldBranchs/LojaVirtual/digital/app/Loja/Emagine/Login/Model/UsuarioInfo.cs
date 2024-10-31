using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Model
{
    public class UsuarioInfo
    {
        private IList<UsuarioEnderecoInfo> _enderecos;
        private IList<UsuarioPreferenciaInfo> _preferencias;

        public UsuarioInfo() {
            _enderecos = new List<UsuarioEnderecoInfo>();
            _preferencias = new List<UsuarioPreferenciaInfo>();
        }

        [JsonProperty("id_usuario")]
        public int Id { get; set; }
        [JsonProperty("nome")]
        public string Nome { get; set; }
        [JsonProperty("cpf_cnpj")]
        public string CpfCnpj { get; set; }
        [JsonProperty("foto")]
        public string Foto { get; set; }
        [JsonProperty("email")]
        public string Email { get; set; }
        [JsonProperty("slug")]
        public string Slug { get; set; }
        [JsonProperty("senha")]
        public string Senha { get; set; }
        [JsonProperty("telefone")]
        public string Telefone { get; set; }
        [JsonProperty("telefone_str")]
        public string TelefoneStr { get; set; }

        [JsonIgnore]
        public SituacaoEnum Situacao { get; set; }

        [JsonProperty("enderecos")]
        public IList<UsuarioEnderecoInfo> Enderecos {
            get {
                return _enderecos;
            }
            set
            {
                _enderecos = value;
            }
        }

        [JsonProperty("preferencias")]
        public IList<UsuarioPreferenciaInfo> Preferencias {
            get {
                return _preferencias;
            }
            set {
                _preferencias = value;
            }
        }

        [JsonProperty("cod_situacao")]
        public int _CodSituacao
        {
            get
            {
                return (int)Situacao;
            }
            set
            {
                Situacao = (SituacaoEnum)value;
            }
        }
    }
}
