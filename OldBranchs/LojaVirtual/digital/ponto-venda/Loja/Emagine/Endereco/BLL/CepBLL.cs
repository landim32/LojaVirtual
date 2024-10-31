using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Endereco.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Endereco.BLL
{
    public class CepBLL : RestAPIBase
    {
        private string URL_CEP = "http://emagine.com.br/endereco";

        public CepBLL() {
            if (!string.IsNullOrEmpty(GlobalUtils.URLAplicacao))
            {
                URL_CEP = GlobalUtils.URLAplicacao;
            }
        }

        public async Task<EnderecoInfo> pegarPorCep(string cep)
        {
            return await queryGet<EnderecoInfo>(URL_CEP + "/api/cep/pegar/" + cep);
        }

        public async Task<List<UfInfo>> listarUf()
        {
            return await queryGet<List<UfInfo>>(URL_CEP + "/api/cep/listar-uf");
        }

        public async Task<List<CidadeInfo>> buscarPorCidade(string palavraChave, string uf) {
            var args = new List<object>();
            args.Add(new CidadeBuscaInfo
            {
                PalavraChave = palavraChave,
                Uf = uf
            });
            return await queryPut<List<CidadeInfo>>(URL_CEP + "/api/cep/buscar-por-cidade", args.ToArray());
        }

        public async Task<List<BairroInfo>> buscarPorBairro(string palavraChave, int idCidade)
        {
            var args = new List<object>();
            args.Add(new BairroBuscaInfo
            {
                PalavraChave = palavraChave,
                IdCidade = idCidade
            });
            return await queryPut<List<BairroInfo>>(URL_CEP + "/api/cep/buscar-por-bairro", args.ToArray());
        }

        public async Task<List<EnderecoInfo>> buscarPorLogradouro(string palavraChave, int idBairro)
        {
            var args = new List<object>();
            args.Add(new EnderecoBuscaInfo
            {
                PalavraChave = palavraChave,
                IdBairro = idBairro
            });
            return await queryPut<List<EnderecoInfo>>(URL_CEP + "/api/cep/buscar-por-logradouro", args.ToArray());
        }
    }
}
