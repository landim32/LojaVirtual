using Radar.Model;
using Radar.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Utils
{
    public static class PercursoUtils
    {
        private static PercursoPage _paginaAtual;
        private static PercursoInfo _percursoAtual;

        public static bool Gravando { get; set; }
        public static float Latitude { get; set; }
        public static float Longitude { get; set; }
        public static DateTime UltimoMovimento { get; set; }

        public static PercursoInfo PercursoAtual
        {
            get {
                return _percursoAtual;
            }
            set {
                _percursoAtual = value;
            }
        }

        public static PercursoPage PaginaAtual {
            get {
                return _paginaAtual;
            }
            set {
                _paginaAtual = value;
            }
        }
    }
}
