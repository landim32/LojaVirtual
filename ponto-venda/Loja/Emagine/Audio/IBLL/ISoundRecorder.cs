using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.IBLL
{
    public interface ISoundRecorder
    {
        void PlayRecord();
        void Stop();
        void Pause();
        void deleteRecord();
    }
}
