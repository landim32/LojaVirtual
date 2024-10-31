using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.IBLL
{
    public interface IThread
    {
        void RunOnUiThread(Action acao);
        void closeApplication();
    }
}
