using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.IDAL
{
    public interface IRadarDAL
    {
        IList<RadarInfo> listar();
		IList<RadarInfo> listarEnderecoNulo();
        IList<RadarInfo> listarUsuario();
        IList<RadarInfo> listarInativo();
        IList<RadarInfo> listar(RadarBuscaInfo busca);
        IList<RadarInfo> listar(double latitude, double longitude, double latitudeDelta, double longitudeDelta, IList<RadarTipoEnum> filtro);
        RadarInfo pegar(int idRadar);
        int gravar(RadarInfo radar);
        void excluir(int idLocal);
    }
}
