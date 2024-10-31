using Emagine.Base.Model;
using System;

namespace Emagine.Base.Utils
{
    public static class GlobalUtils
    {
        private static AplicacaoEnum _AplicacaoAtual;
        private static string _URLAplicacao;

        [Obsolete]
        public static AplicacaoEnum getAplicacaoAtual(){
            return _AplicacaoAtual;
        }

        [Obsolete]
        public static void setAplicacaoAtual(AplicacaoEnum value){
            _AplicacaoAtual = value;
        }

        public static bool Demonstracao { get; set; } = false;

        public static string URLAplicacao
        {
            get {
                return _URLAplicacao;
            }
            set {
                _URLAplicacao = value;
            }
        }
    }
}

