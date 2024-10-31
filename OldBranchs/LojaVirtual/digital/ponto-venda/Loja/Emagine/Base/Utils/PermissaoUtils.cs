using Emagine.Base.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Utils
{
    public static class PermissaoUtils
    {
        private static IPermissao _permissao;

        public static void pedirPermissao()
        {
            if(Device.OS == TargetPlatform.Android){
                if (_permissao == null)
                {
                    _permissao = DependencyService.Get<IPermissao>();
                }
                _permissao.pedirPermissao();   
            }
        }
    }
}
