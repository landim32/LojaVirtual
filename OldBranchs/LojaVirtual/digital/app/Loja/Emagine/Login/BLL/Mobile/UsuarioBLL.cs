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

namespace Emagine.Login.BLL.Mobile
{
    public class UsuarioBLL : Base.UsuarioBLL
    {
        public override void gravarAtual(UsuarioInfo usuario)
        {
            _usuario = usuario;
            App.Current.Properties["usuario"] = JsonConvert.SerializeObject(_usuario);
            App.Current.SavePropertiesAsync();
        }

        public override async Task limparAtual()
        {
            _usuario = null;
            App.Current.Properties.Clear();
            await App.Current.SavePropertiesAsync();
        }

        public override UsuarioInfo pegarAtual()
        {
            if (_usuario != null) {
                return _usuario;
            }
            if (App.Current.Properties.ContainsKey("usuario"))
            {
                string usuarioStr = App.Current.Properties["usuario"].ToString();
                _usuario = JsonConvert.DeserializeObject<UsuarioInfo>(usuarioStr);
                return _usuario;
            }
            return null;
        }
    }
}
