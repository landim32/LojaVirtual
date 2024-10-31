using Emagine.Endereco.Model;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Model
{
    public class UsuarioEnderecoInfo: EnderecoInfo
    {
        [JsonProperty("id_endereco")]
        public int Id { get; set; }
        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }

        public static UsuarioEnderecoInfo clonar(EnderecoInfo endereco) {
            return new UsuarioEnderecoInfo
            {
                Cep = endereco.Cep,
                Logradouro = endereco.Logradouro,
                Complemento = endereco.Complemento,
                Numero = endereco.Numero,
                Bairro = endereco.Bairro,
                Cidade = endereco.Cidade,
                Uf = endereco.Uf,
                Latitude = endereco.Latitude,
                Longitude = endereco.Longitude
            };
        }

        public string EnderecoLbl
        {
            get 
            {
                return String.Format("{0}, {1} - {2}\n{3} - {4}\n{5},{6} - Brasil", Logradouro, Numero, Complemento, Bairro, Cep, Cidade, Uf);
            }
        }

	}
}
