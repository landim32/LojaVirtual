using Emagine.Base.Estilo;
using Emagine.Treino.Factory;
using Emagine.Treino.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;
using Xfx;

namespace Emagine.Treino.Pages
{
    public class TreinoFormPage : ContentPage
    {
        private StackLayout _mainLayout;
        private XfxEntry _nomeEntry;
        private Button _gravarButton;

        private TreinoInfo _treino = null;

        public event EventHandler<TreinoInfo> AoGravar;

        public TreinoInfo Treino {
            get {
                if (_treino == null) {
                    _treino = new TreinoInfo();
                }
                _treino.Nome = _nomeEntry.Text;
                return _treino;
            }
            set {
                _treino = value;
                if (_treino != null) {
                    _nomeEntry.Text = _treino.Nome;
                }
            }
        }

        public TreinoFormPage()
        {
            Title = "Novo treino";
            inicilizarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Padding = new Thickness(5, 5),
                Spacing = 3,
                Children = {
                    _nomeEntry,
                    _gravarButton
                }
            };
        }

        protected void inicilizarComponente()
        {
            _nomeEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Nome do Treino",
                Keyboard = Keyboard.Default,
                ErrorDisplay = ErrorDisplay.None
            };

            _gravarButton = new Button
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "SALVAR"
            };
            _gravarButton.Clicked += gravarButtonClicked;
        }

        private void gravarButtonClicked(object sender, EventArgs e)
        {
            //throw new NotImplementedException();
            var regraTreino = TreinoFactory.create();
            var treino = this.Treino;
            if (treino.Id > 0)
            {
                regraTreino.alterar(treino);
            }
            else {
                int idTreino = regraTreino.inserir(treino);
                treino = regraTreino.pegar(idTreino);
            }
            AoGravar?.Invoke(this, treino);
        }
    }
}