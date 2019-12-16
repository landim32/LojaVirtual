using Emagine.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Helpers
{
    public class GeoEnderecoEventArgs: EventArgs
    {
        public GeoEnderecoEventArgs(GeoEnderecoInfo endereco) {
            Endereco = endereco;
        }
        public GeoEnderecoInfo Endereco { get; set; }
    }
}
