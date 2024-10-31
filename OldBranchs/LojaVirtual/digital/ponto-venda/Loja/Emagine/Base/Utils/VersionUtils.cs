using Emagine.Base.IBLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Utils
{
    public static class VersionUtils
    {
        private static IAppVersion _versao;

        public static string getVersion()
        {
            if (_versao == null) {
                _versao = DependencyService.Get<IAppVersion>();
            }
            return _versao.GetVersion();
        }

        public static int getBuild()
        {
            if (_versao == null) {
                _versao = DependencyService.Get<IAppVersion>();
            }
            return _versao.GetBuild();
        }
    }
}
