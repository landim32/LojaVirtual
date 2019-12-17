using Emagine.Base.BLL;
using Emagine.Login.Model;
using Emagine;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Emagine.Base.Utils;
using Newtonsoft.Json;

namespace Emagine.Login.IBLL
{
    public interface IUsuarioBLL
    {
        Task<int> logar(string email, string senha);
        Task<UsuarioInfo> pegar(int id_usuario);
        Task<bool> recuperarSenha(UsuarioNovaSenhaInfo info);
        Task<bool> resetarSenha(UsuarioNovaSenhaInfo info);
        Task<int> inserir(UsuarioInfo usuario);
        Task<int> alterar(UsuarioInfo usuario);
        void gravarAtual(UsuarioInfo usuario);
        Task limparAtual();
        UsuarioInfo pegarAtual();
    }
}
