using Emagine.CRM.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Emagine.CRM.Factory
{
    public static class ClienteFactory
    {
        private static ClienteBLL _Cliente;

        public static ClienteBLL create()
        {
            if (_Cliente == null)
            {
                _Cliente = new ClienteBLL();
            }
            return _Cliente;
        }
    }
}
