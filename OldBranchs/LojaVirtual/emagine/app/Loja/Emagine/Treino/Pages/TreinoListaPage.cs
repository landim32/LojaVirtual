using Emagine.Base.Estilo;
using Emagine.Treino.Cells;
using Emagine.Treino.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Treino.Pages
{
    public class TreinoListaPage : ContentPage
    {
        private IList<TreinoInfo> _treinos;

        private ListView _treinoListView;

        public event EventHandler<TreinoInfo> AoSelecionar;

        public TreinoListaPage()
        {
            Title = "Meus Treinos";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            _treinos = new List<TreinoInfo>();

            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _treinoListView
                }
            };
        }

        public IList<TreinoInfo> Treinos
        {
            get
            {
                return _treinos;
            }
            set
            {
                _treinoListView.ItemsSource = null;
                _treinos = value;
                _treinoListView.ItemsSource = _treinos;
            }
        }

        private void inicializarComponente()
        {
            _treinoListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                ItemTemplate = new DataTemplate(typeof(TreinoCell))
            };
            _treinoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _treinoListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var treino = (TreinoInfo)((ListView)sender).SelectedItem;
                _treinoListView.SelectedItem = null;

                AoSelecionar?.Invoke(this, treino);
            };
        }
    }
}