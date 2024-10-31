using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Base.IBLL
{
    public interface IImagemBLL
    {
        byte[] redimencionar(byte[] data, float width, float height);
    }
}
