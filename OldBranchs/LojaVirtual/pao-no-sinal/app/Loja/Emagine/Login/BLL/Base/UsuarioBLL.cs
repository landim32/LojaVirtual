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
using Emagine.Login.IBLL;

namespace Emagine.Login.BLL.Base
{
    public class UsuarioBLL : RestAPIBase, IUsuarioBLL
    {
        protected UsuarioInfo _usuario;

        public async Task<int> logar(string email, string senha)
        {
            var args = new List<object>();
            args.Add(new LoginInfo
            {
                Email = email,
                Senha = senha
            });
            return await queryPut<int>(GlobalUtils.URLAplicacao + "/api/usuario/logar", args.ToArray());
        }

        public async Task<UsuarioInfo> pegar(int id_usuario)
        {
            return await queryGet<UsuarioInfo>(GlobalUtils.URLAplicacao + "/api/usuario/pegar/" + id_usuario.ToString());
        }

        public async Task<bool> recuperarSenha(UsuarioNovaSenhaInfo info)
        {
            try
            {
                var args = new List<object>();
                args.Add(info);
                var ret = await queryPut(GlobalUtils.URLAplicacao + "/api/usuario/resetar-senha", args.ToArray());
                return true;
            }
            catch
            {
                return false;
            }
        }

        public async Task trocarSenha(UsuarioTrocaSenhaInfo info)
        {
            await queryPut(GlobalUtils.URLAplicacao + "/api/usuario/trocar-senha", new UsuarioTrocaSenhaInfo[1] { info });
            return;
        }

        [Obsolete("Use alterarSenha")]
        public async Task<bool> resetarSenha(UsuarioNovaSenhaInfo info)
        {
            try
            {
                var args = new List<object>();
                args.Add(info);
                var ret = await queryPut(GlobalUtils.URLAplicacao + "/api/usuario/alterar-senha", args.ToArray());
                return true;
            }
            catch
            {
                return false;
            }
        }

        public async Task<int> inserir(UsuarioInfo usuario)
        {
            var args = new List<object>() { usuario };
            string str = await queryPut<string>(GlobalUtils.URLAplicacao + "/api/usuario/inserir", args.ToArray());
            int id_usuario = 0;
            int.TryParse(str, out id_usuario);
            return id_usuario;
        }

        public async Task<int> alterar(UsuarioInfo usuario)
        {
            var args = new List<object>() { usuario };
            await execPut(GlobalUtils.URLAplicacao + "/api/usuario/alterar", args.ToArray());
            return usuario.Id;
        }

        public virtual void gravarAtual(UsuarioInfo usuario)
        {
            // nada
        }

        public virtual Task limparAtual()
        {
            _usuario = null;
            return new Task(() => { });
        }

        public virtual UsuarioInfo pegarAtual()
        {
            if (_usuario != null)
            {
                return _usuario;
            }
            return null;
        }
    }
}
