
using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Mapa.Model;
using Emagine.Produto.Model;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.BLL.Mobile
{
    public class LojaBLL : Base.LojaBLL
    {

        public override bool podeMudarLoja()
        {
            if (App.Current.Properties.ContainsKey("pode_mudar_loja"))
            {
                return (bool)App.Current.Properties["pode_mudar_loja"];
            }
            return true;
        }

        public override async Task<IList<LojaInfo>> listar(int? idUsuario = null)
        {
            if (_lojas == null)
            {
                _lojas = await base.listar(idUsuario);
                App.Current.Properties["pode_mudar_loja"] = (_lojas.Count > 1);
                await App.Current.SavePropertiesAsync();
            }
            return _lojas;
        }

        public override async Task<IList<LojaInfo>> buscar(double latitude, double longitude)
        {
            var args = new List<object>();
            args.Add(new LocalInfo
            {
                Latitude = latitude,
                Longitude = longitude
            });
            _lojas = await queryPut<IList<LojaInfo>>(GlobalUtils.URLAplicacao + "/api/loja/buscar", args.ToArray());
            App.Current.Properties["pode_mudar_loja"] = (_lojas.Count > 1);
            await App.Current.SavePropertiesAsync();
            return _lojas;
        }

        public override void gravarAtual(LojaInfo loja)
        {
            _loja = loja;
            App.Current.Properties["loja"] = JsonConvert.SerializeObject(loja);
            App.Current.SavePropertiesAsync();
        }

        public override async Task limparAtual()
        {
            _loja = null;
            _lojas = null;
            App.Current.Properties.Remove("loja");
            App.Current.Properties.Remove("pode_mudar_loja");
            await App.Current.SavePropertiesAsync();
        }

        private LojaInfo pegarDePropridadeApp()
        {
            if (App.Current.Properties.ContainsKey("loja"))
            {
                string lojaStr = App.Current.Properties["loja"].ToString();
                var usuario = JsonConvert.DeserializeObject<LojaInfo>(lojaStr);
                return usuario;
            }
            return null;
        }

        public override LojaInfo pegarAtual()
        {
            if (_loja == null)
            {
                _loja = pegarDePropridadeApp();
            }
            return _loja;
        }
    }
}
