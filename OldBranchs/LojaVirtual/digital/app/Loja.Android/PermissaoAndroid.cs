using System;
using Android;
using Android.App;
using Android.Content;
using Android.Content.PM;
using Android.OS;
using Android.Support.V4.App;
using Emagine.Base.Model;
using Loja.Droid;
using Xamarin.Forms;

[assembly: Dependency(typeof(PermissaoAndroid))]

namespace Loja.Droid
{
    public class PermissaoAndroid: IPermissao
    {
        private bool verificarPermissao(string[] permissoes) {
            var context = Android.App.Application.Context;
            var retorno = true;
            foreach (string permissao in permissoes) {
                if (context.CheckSelfPermission(permissao) != (int)Permission.Granted) {
                    retorno = false;
                    break;
                }
            }
            return retorno;
        }

        public void pedirPermissao()
        {
            if ((int)Build.VERSION.SdkInt >= 23)
            {
                string[] permissoes = {
                    Manifest.Permission.AccessCoarseLocation,
                    Manifest.Permission.AccessFineLocation
                };

                if (!verificarPermissao(permissoes))
                {
                    int code = 32;
                    var activity = (MainActivity)Forms.Context;
                    ActivityCompat.RequestPermissions(activity, permissoes, code);
                }
            }
        }
    }
}
