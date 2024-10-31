using Acr.UserDialogs;
using Emagine;
using Emagine.Base.Pages;
using Emagine.Frete.Factory;
using Emagine.Frete.Pages;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Frete.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Frete.Utils
{
    public static class LoginUtils
    {
        //private const string CLIENTE = "Preciso de um barco";
        //private const string MOTORISTA = "Sou marinheiro";

        public static LoginPage gerarLogin(Action aoLogar) {
            var loginPage = new LoginPage
            {
                Title = "Entrar"
            };
            loginPage.AoLogar += async (sender, usuario) => {
                if (usuario == null) {
                    
                    await ((Page)sender).DisplayAlert("Erro", "Usuário não informado.", "Fechar");
                    return;
                }
                var regraMotorista = MotoristaFactory.create();
                var motorista = await regraMotorista.pegar(usuario.Id);
                if (motorista != null) {
                    regraMotorista.gravarAtual(motorista);
                }
                aoLogar?.Invoke();
                //App.Current.MainPage = App.gerarRootPage(new PrincipalPage());
            };
            return loginPage;
        }

        public static async Task criarUsuario(Action aoCriar) {
            var atualPage = App.Current.MainPage;
            var cadastroPage = UsuarioFormPageFactory.create();
            if (!(cadastroPage is FreteUsuarioFormPage)) {
                throw new Exception("A página de cadastro precisa ser do tipo FreteUsuarioFormPage.");
            }
            ((FreteUsuarioFormPage)cadastroPage).AoCadastrarMotorista += (s1, m1) => {
                var motoristaPage = CadastroMotoristaPageFactory.create();
                motoristaPage.Usuario = m1;
                motoristaPage.AoCompletar += (s2, motorista) =>
                {
                    aoCriar?.Invoke();
                };
                ((Page)s1).Navigation.PushAsync(motoristaPage);
            };
            ((FreteUsuarioFormPage)cadastroPage).AoCadastrarEmpresa += (s2, u2) =>
            {
                /*
                var empresaPage = new CadastroEmpresaPage(u2);
                empresaPage.AoCompletar += (s3, usuario) =>
                {
                    aoCriar?.Invoke();
                };
                ((Page)s2).Navigation.PushAsync(empresaPage);
                */
                aoCriar?.Invoke();
            };
            await atualPage.Navigation.PushAsync(cadastroPage);
        }
    }
}
