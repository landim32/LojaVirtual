using Radar.DALFactory;
using Radar.IDAL;
using Radar.Model;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.BLL
{
    public class GastoBLL
    {
        private IGastoDAL _db;

        public GastoBLL() {
            _db = GastoDALFactory.create();
        }

        public IList<GastoInfo> listar(int idPercurso) {
            return _db.listar(idPercurso);
        }

        public IList<GastoInfo> listar()
        {
            return _db.listar();
        }

        public GastoInfo pegar(int id) {
            return _db.pegar(id);
        }

        public int gravar(GastoInfo gasto) {
            var percurso = PercursoUtils.PercursoAtual;
            var local = GPSUtils.UltimaLocalizacao;
            if (percurso == null)
                throw new Exception("Nenhum percurso sendo gravado no momento.");
            if (local == null)
                throw new Exception("Nenhuma posição gravada.");
            gasto.IdPercurso = percurso.Id;
            gasto.DataInclusao = local.Tempo;
            return _db.gravar(gasto);
        }

        public void excluir(int id) {
            _db.excluir(id);
        }
    }
}
