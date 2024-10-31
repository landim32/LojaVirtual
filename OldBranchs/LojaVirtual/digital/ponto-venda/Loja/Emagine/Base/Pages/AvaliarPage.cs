using System;
using Emagine.Base.Estilo;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Base.Pages
{
    [Obsolete("Use AvaliePage")]
    public class AvaliarPage : ContentPage
    {
        private Label _Descricao;
        private StackLayout _ContainerEstrelas;
        private IconImage _1Estrela;
        private IconImage _2Estrela;
        private IconImage _3Estrela;
        private IconImage _4Estrela;
        private IconImage _5Estrela;
        private Button _Confirmar;
        private int _Avaliacao { get; set; }
        public EventHandler<int> Confirmed;

        public AvaliarPage(string descricao)
        {
            Title = "Avaliação";
            inicializarComponente(descricao);
            Content = new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(10, 20),
                Children = {
                    _Descricao,
                    _ContainerEstrelas,
                    _Confirmar
                }
            };
        }

        private void setEstrelas(int posicao){
            var i = 0;
            _Avaliacao = posicao;
            while (i < posicao)
            {
                ((IconImage)_ContainerEstrelas.Children[i]).Icon = "fa-star";
                i++;
            }
            while (i < 5)
            {
                ((IconImage)_ContainerEstrelas.Children[i]).Icon = "fa-star-o";
                i++;
            }
        }

        private void inicializarComponente(string descricao)
        {
            _Descricao = new Label{
                Style = Estilo.Estilo.Current[Estilo.Estilo.TITULO3],
                Text = descricao,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center
            };

            _1Estrela = new IconImage
            {
                Icon = "fa-star-o",
                IconColor = Color.Gold,
                IconSize = 28
            };
            var tapGestureRecognizer1 = new TapGestureRecognizer();
            tapGestureRecognizer1.Tapped += (sender, e) => {
                setEstrelas(1);
            };
            _1Estrela.GestureRecognizers.Add(tapGestureRecognizer1);

            _2Estrela = new IconImage
            {
                Icon = "fa-star-o",
                IconColor = Color.Gold,
                IconSize = 28
            };
            var tapGestureRecognizer2 = new TapGestureRecognizer();
            tapGestureRecognizer2.Tapped += (sender, e) => {
                setEstrelas(2);
            };
            _2Estrela.GestureRecognizers.Add(tapGestureRecognizer2);

            _3Estrela = new IconImage
            {
                Icon = "fa-star-o",
                IconColor = Color.Gold,
                IconSize = 28
            };
            var tapGestureRecognizer3 = new TapGestureRecognizer();
            tapGestureRecognizer3.Tapped += (sender, e) => {
                setEstrelas(3);
            };
            _3Estrela.GestureRecognizers.Add(tapGestureRecognizer3);

            _4Estrela = new IconImage
            {
                Icon = "fa-star-o",
                IconColor = Color.Gold,
                IconSize = 28
            };
            var tapGestureRecognizer4 = new TapGestureRecognizer();
            tapGestureRecognizer4.Tapped += (sender, e) => {
                setEstrelas(4);
            };
            _4Estrela.GestureRecognizers.Add(tapGestureRecognizer4);

            _5Estrela = new IconImage
            {
                Icon = "fa-star-o",
                IconColor = Color.Gold,
                IconSize = 28
            };
            var tapGestureRecognizer5 = new TapGestureRecognizer();
            tapGestureRecognizer5.Tapped += (sender, e) => {
                setEstrelas(5);
            };
            _5Estrela.GestureRecognizers.Add(tapGestureRecognizer5);

            _ContainerEstrelas = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 10, 0, 0),
                Children = {
                    _1Estrela,
                    _2Estrela,
                    _3Estrela,
                    _4Estrela,
                    _5Estrela
                }
            };

            _Confirmar = new Button()
            {
                Text = "Confirmar",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Estilo.Current[Estilo.Estilo.BTN_SUCESSO]
            };
            _Confirmar.Clicked += (sender, e) =>
            {
                if (Confirmed != null)
                    Confirmed(this, _Avaliacao);
                Navigation.PopAsync();
            };

        }
    }
}

