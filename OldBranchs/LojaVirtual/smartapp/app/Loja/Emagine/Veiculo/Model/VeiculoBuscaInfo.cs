using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Veiculo.Model
{
    public class VeiculoBuscaInfo
    {
        private int _IdCidadeOrigem;
        public int _IdCidadeDestino;
        public DateTime _DataInicio;
        public DateTime _DataFim;

        public int IdCidadeOrigem
        {
            get {
                return _IdCidadeOrigem;
            }
            set
            {
                if (value != _IdCidadeOrigem)
                {
                    _IdCidadeOrigem = value;
                }
            }
        }

        public int IdCidadeDestino
        {
            get
            {
                return _IdCidadeDestino;
            }
            set
            {
                if (value != _IdCidadeDestino)
                {
                    _IdCidadeDestino = value;
                }
            }
        }

        public DateTime DataInicio
        {
            get
            {
                return _DataInicio;
            }
            set
            {
                if (value != _DataInicio)
                {
                    _DataInicio = value;
                }
            }
        }

        public DateTime DataFim
        {
            get
            {
                return _DataFim;
            }
            set
            {
                if (value != _DataFim)
                {
                    _DataFim = value;
                }
            }
        }
    }
}
