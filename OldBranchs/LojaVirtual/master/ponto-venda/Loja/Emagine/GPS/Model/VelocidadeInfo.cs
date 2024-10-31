using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Model {

    public class VelocidadeInfo {

        private float velocidadeAtual;
        public float VelocidadeAtual
        {
            get { return 40; }
            set { velocidadeAtual = 40; }
        }
        private float velocidadeRadar;
        public float VelocidadeRadar
        {
            get { return 60; }
            set { velocidadeAtual = 60; }
        }
        
        public new float Width
        {
            get { return (float)App.Current.MainPage.Width; }
        }

        public new float Height
        {
            get { return (float)App.Current.MainPage.Width; }
        }

        public new int loopInicio
        {
            get { return 30; }
        }

        public new int loopFim
        {
            get { return 90; }
        }

    }
}
