using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xfx;

namespace Emagine.Endereco.Controls
{
    public class CepEntry: XfxEntry
    {
        public CepEntry() {
            this.Keyboard = Keyboard.Numeric;
            this.TextChanged += CepEntry_TextChanged;
            this.ErrorDisplay = ErrorDisplay.None;
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
            if (retorno.Length > 8) {
                retorno = retorno.Substring(0, 8);
            }
            if (retorno.Length > 5) {
                retorno = retorno.Substring(0, 5) + "-" + retorno.Substring(5, retorno.Length - 5);
            }
            return retorno;
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

        private void CepEntry_TextChanged(object sender, TextChangedEventArgs e)
        {
            ((CepEntry)sender).Text = formatarTexto(((CepEntry)sender).Text);

        }
    }
}
