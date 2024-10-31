using Radar.BLL;

namespace Radar.Factory
{
    public static class PercursoFactory
    {
        private static PercursoBLL _Percurso;

        public static PercursoBLL create() {
            if (_Percurso == null)
                _Percurso = new PercursoBLL();
            return _Percurso;
        }
    }
}
