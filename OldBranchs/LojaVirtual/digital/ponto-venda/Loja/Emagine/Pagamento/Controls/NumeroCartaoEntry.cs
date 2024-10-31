using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Pagamento.Controls
{
    public class NumeroCartaoEntry: Entry
    {
        public NumeroCartaoEntry() {
            this.Keyboard = Keyboard.Numeric;
            this.TextChanged += NumeroCartaoTextChanged;
        }

        protected string formatarTexto(string texto) {
            string retorno = "";
            if (Text != null) {
                foreach (var c in texto.ToCharArray()) {
                    if (Char.IsNumber(c)) {
                        retorno += c;
                    }
                }
            }
            if (retorno.Length > 16) {
                retorno = retorno.Substring(0, 16);
            }

            if (!string.IsNullOrEmpty(retorno))
            {
                if (retorno.Length > 4) {
                    retorno = retorno.Substring(0, 4) + " " + retorno.Substring(4);
                }
                if (retorno.Length > 9)
                {
                    retorno = retorno.Substring(0, 9) + " " + retorno.Substring(9);
                }
                if (retorno.Length > 14)
                {
                    retorno = retorno.Substring(0, 14) + " " + retorno.Substring(14);
                }
                return retorno;
            }
            return string.Empty;
        }

        public string TextOnlyNumber {
            get {
                string retorno = "";
                if (Text != null)
                {
                    foreach (var c in Text.ToCharArray())
                    {
                        if (Char.IsNumber(c))
                        {
                            retorno += c;
                        }
                    }
                }
                return retorno;
            }
        }

        private void NumeroCartaoTextChanged(object sender, TextChangedEventArgs e)
        {
            ((NumeroCartaoEntry)sender).Text = formatarTexto(((NumeroCartaoEntry)sender).Text);
        }
    }
}
