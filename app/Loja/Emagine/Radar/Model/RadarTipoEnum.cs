using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Model
{
    public enum RadarTipoEnum
    {
        /*
        1- radar fixo
        2- semáforo com radar
        3- semáforo com câmera
        5- radar móvel
        7 - polícia rodoviária
        8 - lombada
        14 - pedágio
        */

        RadarFixo = 1,
        SemaforoComRadar = 2,
        SemaforoComCamera = 3,
        RadarMovel = 5,
        PoliciaRodoviaria = 7,
        Lombada = 8,
        Pedagio = 14
    }
}
