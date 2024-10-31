using Emagine.IBLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Utils
{
    public static class InternetUtils
    {
        private static IInternet _internet;

        public static bool estarConectado()
        {
            if (_internet == null)
                _internet = DependencyService.Get<IInternet>();
            return _internet.estarConectado();
        }
    }
}
