using System;
namespace Emagine.Frete.Model
{
    public class PosicionamentoInfo
    {
        public DateTime DataHora { get; set; }
        public string Estado { get; set; }
        public string Cidade { get; set; }
        public double Latitude { get; set; }
        public double Longitude { get; set; }

        public string TextoCell { 
            get {
                return string.Format("{0} hrs - {1} - {2}", DataHora.ToString("dd/MM/yyyy HH:mm"), Estado, Cidade);
            }
        }
    }
}
