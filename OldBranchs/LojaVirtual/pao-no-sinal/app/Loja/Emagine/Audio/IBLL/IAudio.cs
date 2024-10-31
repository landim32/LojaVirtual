using Emagine.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.IBLL
{
    public interface IAudio
    {
        AudioCanalEnum Canal { get; set; }
        float Volume { get; set; }
        bool CaixaSom { get; set; }
        void play(string[] arquivos);
        void play(string arquivo);
        void stop();
    }
}
