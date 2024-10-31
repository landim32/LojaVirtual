using Emagine.Base.Model;
using System;

namespace Emagine.Base.Utils
{
    public static class GlobalUtils
    {
        private static AplicacaoEnum _AplicacaoAtual;
        private static string _URLAplicacao;

        public static AplicacaoEnum getAplicacaoAtual(){
            return _AplicacaoAtual;
        }

        public static void setAplicacaoAtual(AplicacaoEnum value){
            _AplicacaoAtual = value;
        }

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

