using System;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Base.Views
{
    public class AvaliacaoView : ContentView
    {
        public AvaliacaoView(int? nota)
        {
            if (nota == null)
                nota = 0;
            Content = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                Spacing = 2
            };
            for (var i = 0; i < 5; i++)
            {
                if (i < nota)
                    ((StackLayout)Content).Children.Add(new IconImage
                    {
                        Icon = "fa-star",
                        IconColor = Color.Gold,
                        IconSize = 20
                    });
                else
                    ((StackLayout)Content).Children.Add(new IconImage
                    {
                        Icon = "fa-star-o",
                        IconColor = Color.Gold,
                        IconSize = 20
                    });
            }
        }

        public void setAvaliacao(int nota){
            for (var i = 0; i < 5; i++)
            {
                if (i < nota)
                    ((IconImage)((StackLayout)Content).Children[i]).Icon = "fa-star";
                else
                    ((IconImage)((StackLayout)Content).Children[i]).Icon = "fa-star-o";
            } 
        }

    }
}

