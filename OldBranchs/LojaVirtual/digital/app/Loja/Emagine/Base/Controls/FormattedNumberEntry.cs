using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;
using Xfx;

namespace Emagine.Base.Controls
{
    public class FormattedNumberEntry : XfxEntry
    {
        public static readonly BindableProperty ValueProperty =
            BindableProperty.Create(nameof(Value), typeof(double), typeof(FormattedNumberEntry), default(double));

        public double Value
        {
            get
            {
                return (double)GetValue(ValueProperty);
            }
            set
            {
                SetValue(ValueProperty, value);
            }
        }

        public bool ShouldReactToTextChanges { get; set; }

        public FormattedNumberEntry()
        {
            ShouldReactToTextChanges = true;
        }

        public static double DumbParse(string input)
        {
            if (string.IsNullOrEmpty(input))
            {
                return 0;
            }
            string retorno = string.Empty;
            foreach (var c in input.ToCharArray())
            {
                if (char.IsNumber(c))
                {
                    retorno += c;
                }
            }
            double numero = 0;
            if (double.TryParse(retorno, out numero))
            {
                return numero / 100;
            }
            return 0;

            /*
            if (input == null) return 0;

            int number = 0;
            int multiply = 1;

            for (int i = input.Length - 1; i >= 0; i--)
            {
                if (Char.IsDigit(input[i]))
                {
                    number += (input[i] - '0') * (multiply);
                    multiply *= 10;
                }
            }
            return number / 100;
            */
        }
    }
}