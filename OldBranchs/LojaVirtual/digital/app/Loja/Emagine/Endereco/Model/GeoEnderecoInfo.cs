using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Model
{
    public class GeoEnderecoInfo
    {
        public string Logradouro { get; set; }
        public string Complemento { get; set; }
        public string Bairro { get; set; }
        public string Cidade { get; set; }
        public string Uf { get; set; }
        public string CEP { get; set; }
        public string Via { get; set; }

        public override string ToString()
        {
            var endereco = new List<string>();
            if (!string.IsNullOrEmpty(Logradouro))
                endereco.Add(Logradouro);
            if (!string.IsNullOrEmpty(Complemento))
                endereco.Add(Complemento);
            if (!string.IsNullOrEmpty(Bairro))
                endereco.Add(Bairro);
            if (!string.IsNullOrEmpty(Cidade))
                endereco.Add(Cidade);
            if (!string.IsNullOrEmpty(Uf))
                endereco.Add(Uf);
            return string.Join(", ", endereco.ToArray());
        }
    }
}
