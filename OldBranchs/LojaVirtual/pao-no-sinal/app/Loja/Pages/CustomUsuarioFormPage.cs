using Emagine.Base.Pages;
using Emagine.Login.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Loja.Pages
{
    public class CustomUsuarioFormPage : UsuarioFormPage
    {
        protected override void CadastroClicked(object sender, EventArgs e) {
            var termoConcordanciaPage = new DocumentoPage
            {
                Title = "TERMO DE CONCORDÂNCIA",
                NomeArquivo = "termo_concordancia.html"
            };
            termoConcordanciaPage.AoConfirmar += (s2, e2) => {
                base.CadastroClicked(sender, e);
            };
            termoConcordanciaPage.AoNegar += (s3, e3) => {
                ((Page)s3).Navigation.PopAsync();
            };
            Navigation.PushAsync(termoConcordanciaPage);
        }
    }
}