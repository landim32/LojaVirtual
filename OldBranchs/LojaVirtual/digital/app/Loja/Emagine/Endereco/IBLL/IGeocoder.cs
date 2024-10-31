using Emagine.Helpers;
using Emagine.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.IBLL
{
    public interface IGeocoder
    {
        void pegarAsync(float latitude, float logradouro, GeoEnderecoEventHandler callback);
    }
}
