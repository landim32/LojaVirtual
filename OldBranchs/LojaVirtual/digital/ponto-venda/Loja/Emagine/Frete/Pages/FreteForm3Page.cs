using System;
using System.IO;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.BLL;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Plugin.Media;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class FreteForm3Page : ContentPage
    {

        private Image _Miniatura;
        private Button _FotoProdutoButton;
        private Entry _Observacao;
        private FreteInfo _FreteInfo;
        private Button _EnviarButton;
        public Stream _Imagem;

        public FreteForm3Page(FreteInfo FreteInfo)
        {
            _FreteInfo = FreteInfo;
            Title = "Foto do produto";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    _FotoProdutoButton,
                    _Observacao,
                    _EnviarButton
                }
            };
        }

        private async Task setImageMiniaturaAsync()
        {
            await CrossMedia.Current.Initialize();

            if (!CrossMedia.Current.IsCameraAvailable || !CrossMedia.Current.IsTakePhotoSupported)
            {
                UserDialogs.Instance.Alert("No Camera", ":( No camera available.", "OK");
                return;
            }

            var file = await CrossMedia.Current.TakePhotoAsync(new Plugin.Media.Abstractions.StoreCameraMediaOptions
            {
                Directory = "EmagineFrete",
                Name = "foto_temp.jpg"
            });
            if (file == null)
                return;
            _Imagem = file.GetStream();
            _Miniatura.Source = ImageSource.FromStream(() => {
                var stream = file.GetStream();
                file.Dispose();
                return stream;
            });
            ((StackLayout)this.Content).Children.Insert(0, _Miniatura);
        }

        public void inicializarComponente(){
            _Miniatura = new Image()
            {
                Aspect = Aspect.AspectFit,
                HeightRequest = 250,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Margin = new Thickness(15)
            };
            _FotoProdutoButton = new Button()
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Adicionar foto do produto"
            };
            _FotoProdutoButton.Pressed += (sender, e) => {
                setImageMiniaturaAsync();
            };
            _Observacao = new Entry()
            {
                Placeholder = "Qual a carga ?",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                FontSize = 14,
                HorizontalTextAlignment = TextAlignment.End,
            };
            _EnviarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Próximo"
            };
            _EnviarButton.Clicked += (sender, e) => {
                continuaPedidoAsync();
            };
        }

        public async Task continuaPedidoAsync()
        {
            UserDialogs.Instance.ShowLoading("Enviando...");
            try{
                if (validaPedido())
                {
                    var reader = new StreamReader(this._Imagem);
                    byte[] bytedata = System.Text.Encoding.UTF8.GetBytes(reader.ReadToEnd());
                    _FreteInfo.FotoBase64 = Convert.ToBase64String(bytedata);
                    _FreteInfo.Observacao = _Observacao.Text;
                    var idProduto = await FreteFactory.create().inserir(_FreteInfo);
                    var retInfo = await FreteFactory.create().pegar(idProduto);
                    UserDialogs.Instance.HideLoading();
                    Navigation.PushAsync(new FreteForm4Page(retInfo));
                }
                else
                {
                    UserDialogs.Instance.HideLoading();
                    DisplayAlert("Atenção", "Dados inválidos, verifique todas as entradas.", "Entendi");
                }
            } catch(Exception e){
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(e.Message);
            }

        }

        private bool validaPedido()
        {
            if(_Imagem == null){
                return false;
            }
            return true;
        }

    }
}

