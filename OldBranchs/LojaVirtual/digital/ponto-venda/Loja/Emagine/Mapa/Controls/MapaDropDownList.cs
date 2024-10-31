using System;
using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Mapa.Model;
using Emagine.Mapa.Pages;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Mapa.Controls
{
    public class MapaDropDownList : BaseDropDownList
    {
        public event EventHandler<MapaLocalInfo> Selected;
        public bool CanEdit { get; set; } = true;

        public MapaLocalInfo Value
        {
            get
            {
                return (MapaLocalInfo)_value;
            }
            set
            {
                _value = value;
                if (_value != null)
                {
                    var local = (MapaLocalInfo)_value;
                    _textoLabel.Text = local.Endereco;
                }
                atualizarPlaceholder();
            }
        }

        public string TituloPadrao { get; set; }

        protected override void OnClicked(object sender, EventArgs e)
        {
            /*
            if(CanEdit)
            {
                var mapaPage = new MapaLocalPage
                {
                    TituloPadrao = TituloPadrao
                };
                mapaPage.Local = Value;
                mapaPage.AoSelecionar += MapaPage_AoSelecionar;   
                Navigation.PushModalAsync(mapaPage);
            }
            else 
            {
            */
            if (Value != null) {
                var mapaPage = new MapaPontoPage(Value.Titulo, Value.Endereco, Value.Latitude, Value.Longitude);
                Navigation.PushAsync(mapaPage);
            }
            else {
                UserDialogs.Instance.Alert("Preencha o CEP antes", "Erro", "Entendi");
            }
            //}
        }

        private void MapaPage_AoSelecionar(object sender, MapaLocalInfo local)
        {
            Value = local;
            Selected?.Invoke(sender, local);
        }
    }
}

