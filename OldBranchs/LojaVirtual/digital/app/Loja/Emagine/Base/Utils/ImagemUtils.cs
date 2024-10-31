using Emagine.Base.IBLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Utils
{
    public static class ImagemUtils
    {
        private static IImagemBLL _imagem;

        public static byte[] redimencionar(byte[] data, float width = 0, float height = 0)
        {
            if (_imagem == null) {
                _imagem = DependencyService.Get<IImagemBLL>();
            }
            return _imagem.redimencionar(data, width, height);
        }
    }
}
