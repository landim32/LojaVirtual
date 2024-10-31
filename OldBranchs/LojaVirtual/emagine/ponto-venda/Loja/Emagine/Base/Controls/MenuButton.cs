using Emagine.Base.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Base.Controls
{
    public class MenuButton : ContentView
    {
        private Frame _borda;
        private StackLayout _Fundo;
        private IconImage _Icone;
        private Label _Texto;
        private EventHandler _AoClicar;

        public string Icon
        {
            get
            {
                return (string)GetValue(IconProperty);
            }
            set
            {
                SetValue(IconProperty, value);
                _Icone.Icon = value;
            }
        }

        public int IconSize
        {
            get
            {
                return (int)GetValue(IconSizeProperty);
            }
            set
            {
                SetValue(IconSizeProperty, value);
                _Icone.IconSize = value;
            }
        }

        public string Text
        {
            get
            {
                return (string)GetValue(TextProperty);
            }
            set
            {
                SetValue(TextProperty, value);
                _Texto.Text = value;
            }
        }

        public string FontFamily
        {
            get
            {
                return (string)GetValue(FontFamilyProperty);
            }
            set
            {
                SetValue(FontFamilyProperty, value);
                _Texto.FontFamily = value;
            }
        }

        public FontAttributes FontAttributes
        {
            get
            {
                return (FontAttributes)GetValue(FontAttributesProperty);
            }
            set
            {
                SetValue(FontAttributesProperty, value);
                _Texto.FontAttributes = value;
            }
        }

        public int FontSize
        {
            get
            {
                return (int)GetValue(FontSizeProperty);
            }
            set
            {
                SetValue(FontSizeProperty, value);
                _Texto.FontSize = value;
            }
        }

        public Color TextColor
        {
            get
            {
                return (Color)GetValue(TextColorProperty);
            }
            set
            {
                SetValue(TextColorProperty, value);
                _Icone.IconColor = value;
                _Texto.TextColor = value;
            }
        }

        public int CornerRadius {
            get {
                return (int)GetValue(CornerRadiusProperty);
            }
            set
            {
                SetValue(CornerRadiusProperty, value);
                _borda.CornerRadius = value;
            }
        }

        public Color FrameColor
        {
            get
            {
                return (Color)GetValue(FrameColorProperty);
            }
            set
            {
                SetValue(FrameColorProperty, value);
                _borda.BackgroundColor = value;
            }
        }

        public Thickness FramePadding {
            get
            {
                return (Thickness)GetValue(FramePaddingProperty);
            }
            set
            {
                SetValue(FramePaddingProperty, value);
                _borda.Padding = value;
            }
        }

        public EventHandler Click
        {
            get
            {
                return _AoClicar;
            }
            set
            {
                _AoClicar = value;
                if (_AoClicar != null)
                {
                    var tapGestureRecognizer = new TapGestureRecognizer();
                    tapGestureRecognizer.Tapped += _AoClicar;
                    _Fundo.GestureRecognizers.Add(tapGestureRecognizer);
                    _Icone.GestureRecognizers.Add(tapGestureRecognizer);
                    _Texto.GestureRecognizers.Add(tapGestureRecognizer);
                }
            }
        }

        private void inicializarComponente()
        {
            _Icone = new IconImage
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 0, 10, 0),
                IconSize = IconSize,
                IconColor = TextColor
            };
            _Texto = new Label
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center,
                FontSize = FontSize,
                TextColor = TextColor
            };
        }

        public MenuButton()
        {
            inicializarComponente();
            _Fundo = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Padding = FramePadding,
                Children = {
                    _Icone,
                    _Texto
                }
            };
            _borda = new Frame {
                CornerRadius = CornerRadius,
                BackgroundColor = FrameColor,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = _Fundo
            };
            Content = _borda;
            /*
            IconSize = 54;
            CornerRadius = 5;
            FontSize = 18;
            FramePadding = new Thickness(10, 20);
            */
        }

        public static readonly BindableProperty IconProperty = BindableProperty.Create(
            nameof(Icon), typeof(string), typeof(MenuButton), default(string),
            propertyChanged: IconPropertyChanged
        );

        private static void IconPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.Icon = newValue.ToString();
        }

        public static readonly BindableProperty IconSizeProperty = BindableProperty.Create(
           nameof(IconSize), typeof(int), typeof(MenuButton), 54,
           propertyChanged: IconSizePropertyChanged
        );

        private static void IconSizePropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.IconSize = (int)newValue;
        }

        public static readonly BindableProperty TextProperty = BindableProperty.Create(
            nameof(Text), typeof(string), typeof(MenuButton), default(string),
            propertyChanged: TextPropertyChanged
        );

        private static void TextPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.Text = newValue.ToString();
        }

        public static readonly BindableProperty FrameColorProperty = BindableProperty.Create(
            nameof(FrameColor), typeof(Color), typeof(MenuButton), default(Color),
            propertyChanged: FrameColorPropertyChanged
        );

        private static void FrameColorPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.FrameColor = (Color)newValue;
        }

        public static readonly BindableProperty FontFamilyProperty = BindableProperty.Create(
            nameof(FontFamily), typeof(string), typeof(MenuButton), default(string),
            propertyChanged: FontFamilyPropertyChanged
        );

        private static void FontFamilyPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.FontFamily = newValue.ToString();
        }

        public static readonly BindableProperty FontAttributesProperty = BindableProperty.Create(
            nameof(FontAttributes), typeof(FontAttributes), typeof(MenuButton), default(FontAttributes),
            propertyChanged: FontAttributesPropertyChanged
        );

        private static void FontAttributesPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.FontFamily = newValue.ToString();
        }

        public static readonly BindableProperty FontSizeProperty = BindableProperty.Create(
            nameof(FontSize), typeof(int), typeof(MenuButton), 18,
            propertyChanged: FontSizePropertyChanged
        );

        private static void FontSizePropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.FontSize = (int)newValue;
        }

        public static readonly BindableProperty CornerRadiusProperty = BindableProperty.Create(
            nameof(CornerRadius), typeof(int), typeof(MenuButton), default(int),
            propertyChanged: CornerRadiusPropertyChanged
        );

        private static void CornerRadiusPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.CornerRadius = (int)newValue;
        }

        public static readonly BindableProperty TextColorProperty = BindableProperty.Create(
            nameof(TextColor), typeof(Color), typeof(MenuButton), Color.Black,
            propertyChanged: TextColorPropertyChanged
        );

        private static void TextColorPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.TextColor = (Color)newValue;
        }

        public static readonly BindableProperty FramePaddingProperty = BindableProperty.Create(
            nameof(FramePadding), typeof(Thickness), typeof(MenuButton), new Thickness(10, 20),
            propertyChanged: FramePaddingPropertyChanged
        );

        private static void FramePaddingPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (MenuButton)bindable;
            control.FramePadding = (Thickness)newValue;
        }
    }
}