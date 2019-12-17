using Acr.UserDialogs;
using Emagine.Treino.Factory;
using Emagine.Treino.Pages;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Treino.Utils
{
    public static class TreinoUtils
    {
        public static Page gerarTreinoLista() {
            var treinoPage = new TreinoListaPage
            {
                Title = "Meus treinos",
                Treinos = TreinoFactory.create().listar()
            };
            treinoPage.AoSelecionar += (s2, e2) => {
                //treinoPage.Navigation.PopAsync();
                UserDialogs.Instance.AlertAsync("Treino selecionado!", "Aviso", "Entendi");
            };
            return treinoPage;
        }

        public static Page gerarTreinoInserir() {
            var treinoPage = new TreinoFormPage
            {
                Title = "Novo treino"
            };
            treinoPage.AoGravar += (s2, e2) => {
                treinoPage.Navigation.PopAsync();
                UserDialogs.Instance.AlertAsync("Treino incluído com sucesso!", "Aviso", "Entendi");
            };
            return treinoPage;
        }
    }
}
