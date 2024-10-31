using Radar.Factory;
using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public class PercursoResumoPage: ContentPage
    {
        private PercursoInfo _Percurso;
        private ListView _ResumoListView;

        public PercursoResumoPage(PercursoInfo percurso) {
            _Percurso = percurso;
            inicializarComponente();
            atualizar();
            Content = _ResumoListView;
        }

        private void inicializarComponente() {
            _ResumoListView = new ListView {
                HasUnevenRows = true,
                RowHeight = -1,
                ItemTemplate = new DataTemplate(typeof(PercursoResumoCell)),
                Footer = new Label{ Text = "" }
            };
            _ResumoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
        }

        private void atualizar()
        {
            if (_Percurso == null)
                return;
            Title = _Percurso.Titulo;
            var regraPercurso = PercursoFactory.create();
            var resumos = regraPercurso.listarResumo(_Percurso.Id);
            /*
            resumos.Clear();
            resumos.Add(new PercursoResumoInfo
            {
                Data = new DateTime(2017, 1, 1, 2, 30, 0),
                Descricao = "Teste",
                Distancia = 2300,
                Icone = "",
                Latitude = 1,
                Longitude = 2,
                Tempo = TimeSpan.FromMinutes(130)
            });
            resumos.Add(new PercursoResumoInfo
            {
                Data = new DateTime(2017, 1, 2, 2, 30, 0),
                Descricao = "Teste2",
                Distancia = 4300,
                Icone = "",
                Latitude = 1,
                Longitude = 2,
                Tempo = TimeSpan.FromMinutes(330)
            });
            resumos.Add(new PercursoRadarInfo
            {
                Data = new DateTime(2017, 1, 4, 2, 30, 0),
                Descricao = "Teste3",
                Distancia = 6330,
                Icone = "",
                Latitude = 1,
                Longitude = 2,
                Tempo = TimeSpan.FromMinutes(560),
                Velocidade = 40,    
                MinhaVelocidade = 37,
                Tipo = RadarTipoEnum.RadarFixo
            });
            */
            _ResumoListView.BindingContext = resumos;
        }
    }
}
