using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Base.Controls
{
    public class NotaControl: ContentView
    {
        private IconImage _estrela1Icon;
        private IconImage _estrela2Icon;
        private IconImage _estrela3Icon;
        private IconImage _estrela4Icon;
        private IconImage _estrela5Icon;

        public event EventHandler<int> AoClicar;

        public int Nota {
            get {
                return (int)GetValue(NotaProperty);
            }
            set {
                SetValue(NotaProperty, value);
                //alterarNota(value);
            }
        }

        public int IconSize
        {
            get {
                return (int)GetValue(IconSizeProperty);
            }
            set {
                SetValue(IconSizeProperty, value);
            }
        }

        public NotaControl() {
            inicializarComponente();

            this.Content = new StackLayout {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 10, 0, 10),
                Spacing = 3,
                Children = {
                    _estrela1Icon,
                    _estrela2Icon,
                    _estrela3Icon,
                    _estrela4Icon,
                    _estrela5Icon
                }
            };
        }

        private void inicializarComponente() {
            _estrela1Icon = criarEstrela((sender, e) => {
                if (AoClicar != null) { AoClicar(this, 1); } else { Nota = 1; } 
            });

            _estrela2Icon = criarEstrela((sender, e) => {
                if (AoClicar != null) { AoClicar(this, 2); } else { Nota = 2; }
            });

            _estrela3Icon = criarEstrela((sender, e) => {
                if (AoClicar != null) { AoClicar(this, 3); } else { Nota = 3; }
            });

            _estrela4Icon = criarEstrela((sender, e) => {
                if (AoClicar != null) { AoClicar(this, 4); } else { Nota = 4; }
            });

            _estrela5Icon = criarEstrela((sender, e) => {
                if (AoClicar != null) { AoClicar(this, 5); } else { Nota = 5; }
            });
        }

        private void alterarNota(int nota) {
            switch (nota) {
                case 1:
                    _estrela1Icon.Icon = "fa-star";
                    _estrela2Icon.Icon = "fa-star-o";
                    _estrela3Icon.Icon = "fa-star-o";
                    _estrela4Icon.Icon = "fa-star-o";
                    _estrela5Icon.Icon = "fa-star-o";
                    break;
                case 2:
                    _estrela1Icon.Icon = "fa-star";
                    _estrela2Icon.Icon = "fa-star";
                    _estrela3Icon.Icon = "fa-star-o";
                    _estrela4Icon.Icon = "fa-star-o";
                    _estrela5Icon.Icon = "fa-star-o";
                    break;
                case 3:
                    _estrela1Icon.Icon = "fa-star";
                    _estrela2Icon.Icon = "fa-star";
                    _estrela3Icon.Icon = "fa-star";
                    _estrela4Icon.Icon = "fa-star-o";
                    _estrela5Icon.Icon = "fa-star-o";
                    break;
                case 4:
                    _estrela1Icon.Icon = "fa-star";
                    _estrela2Icon.Icon = "fa-star";
                    _estrela3Icon.Icon = "fa-star";
                    _estrela4Icon.Icon = "fa-star";
                    _estrela5Icon.Icon = "fa-star-o";
                    break;
                case 5:
                    _estrela1Icon.Icon = "fa-star";
                    _estrela2Icon.Icon = "fa-star";
                    _estrela3Icon.Icon = "fa-star";
                    _estrela4Icon.Icon = "fa-star";
                    _estrela5Icon.Icon = "fa-star";
                    break;
                default:
                    _estrela1Icon.Icon = "fa-star-o";
                    _estrela2Icon.Icon = "fa-star-o";
                    _estrela3Icon.Icon = "fa-star-o";
                    _estrela4Icon.Icon = "fa-star-o";
                    _estrela5Icon.Icon = "fa-star-o";
                    break;
            }
        }

        private IconImage criarEstrela(EventHandler aoClicar)
        {
            var estrelaIcone = new IconImage
            {
                Icon = "fa-star-o",
                IconColor = Color.Gold,
                IconSize = 42
            };
            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += aoClicar;
            estrelaIcone.GestureRecognizers.Add(tapGestureRecognizer);
            return estrelaIcone;
        }

        public static readonly BindableProperty NotaProperty = BindableProperty.Create(
           nameof(Nota), typeof(int), typeof(NotaControl), default(int),
           propertyChanged: NotaPropertyChanged
        );

        private static void NotaPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (NotaControl)bindable;
            control.Nota = (int)newValue;
            control.alterarNota(control.Nota);
        }

        public static readonly BindableProperty IconSizeProperty = BindableProperty.Create(
           nameof(IconSize), typeof(int), typeof(NotaControl), 18,
           propertyChanged: IconSizePropertyChanged
        );

        private static void IconSizePropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (NotaControl)bindable;
            control.IconSize = (int)newValue;
            control._estrela1Icon.IconSize = control.IconSize;
            control._estrela2Icon.IconSize = control.IconSize;
            control._estrela3Icon.IconSize = control.IconSize;
            control._estrela4Icon.IconSize = control.IconSize;
            control._estrela5Icon.IconSize = control.IconSize;
            //control.alterarNota(control.Nota);
        }
    }
}
