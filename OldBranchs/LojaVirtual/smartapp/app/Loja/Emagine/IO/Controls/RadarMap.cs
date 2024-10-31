using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms.Maps;
using Radar.Model;
using Radar.BLL;
using Radar.Factory;

namespace Radar.Controls
{
    public class RadarMap: Map
    {
        private LocalizacaoInfo _localAtual;
		private int _percursoId;
        private Dictionary<string, RadarPin> _radares = new Dictionary<string, RadarPin>();

        public delegate void MapRotacaoEventHandler(object sender, LocalizacaoInfo local);
        public delegate void DesenharRadarEventHandler(object sender, RadarPin radar);
        public MapRotacaoEventHandler AoAtualizaPosicao;
        public DesenharRadarEventHandler AoDesenharRadar;

        public RadarMap() {
        }
        
        public RadarMap(int idPercurso) {
			PercursoId = idPercurso;
        }
		
 		public  int PercursoId
        {
            get
            {
                return _percursoId;
            }
            set
            {
                _percursoId = value;
            }
        }
        public Dictionary<string, RadarPin> Radares
        {
            get
            {
                return _radares;
            }
        }

        public void atualizarPosicao(LocalizacaoInfo local)
        {
            //if (AoRotacinar != null && _rotacao != value)
            _localAtual = local;
            if (AoAtualizaPosicao != null)
            {
                AoAtualizaPosicao(this, local);
                //this.VisibleRegion = new MapSpan(new Position(local.Latitude, local.Longitude), Configuracao.GPSDeltaPadrao, Configuracao.GPSDeltaPadrao);
            }
        }

        public void atualizarAreaVisivel(MapSpan region)
        {
            //var center = region.Center;
            var latitudeDelta = region.LatitudeDegrees / 2;
            var longitudeDelta = region.LongitudeDegrees / 2;

            if (!(latitudeDelta <= PreferenciaUtils.GPSDeltaMax && longitudeDelta <= PreferenciaUtils.GPSDeltaMax))
                return;

            RadarBLL regraRadar = RadarFactory.create();
            var radares = regraRadar.listar(region.Center.Latitude, region.Center.Longitude, latitudeDelta, longitudeDelta);
            //var radares = regraRadar.listar();
           
			foreach (RadarInfo radar in radares)
            {
				
					adicionarRadar(radar);
				
            }
        }

        
        private void adicionarRadar(RadarInfo radar)
        {
			RadarBLL radarBLL = new RadarBLL();
            string radarId = radar.Latitude.ToString() + "|" + radar.Longitude.ToString();
            if (!Radares.ContainsKey(radarId))
            {
				RadarPin ponto = new RadarPin()
				{
					Id = radar.Latitude.ToString() + "|" + radar.Longitude.ToString(),
					Pin = new Pin()
					{
						Type = PinType.Place,
						Position = new Position(radar.Latitude, radar.Longitude),
						Label = radar.Velocidade.ToString() + "km/h"
					},
					Sentido = radar.Direcao,
					Velocidade = radar.Velocidade,
					Imagem = radarBLL.imagemRadar(radar.Velocidade),
					Tipo = radar.Tipo
					
                };
                Radares.Add(ponto.Id, ponto);
                if (AoDesenharRadar != null)
                    AoDesenharRadar(this, ponto);
            }
        }
    }
}
