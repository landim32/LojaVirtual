using CarouselView.FormsPlugin.Abstractions;
using Emagine.Banner.BLL;
using Emagine.Banner.Cells;
using Emagine.Banner.Model;
using Emagine.Pedido.Factory;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Runtime.CompilerServices;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Banner.Controls
{
    public class BannerView: CarouselViewControl
    {
        //private CancellationTokenSource _cancellation;
        private bool _rotacionando = false;
        private DateTime _ultimaRotacao;

        public int Duracao { get; set; } = 4000;

        public BannerView() {
            VerticalOptions = LayoutOptions.Start;
            HorizontalOptions = LayoutOptions.Fill;

            Position = 0;
            InterPageSpacing = 10;
            HeightRequest = 160;
            ShowIndicators = true;
            Margin = new Thickness(0, 1, 0, 0);
            //ShowArrows = true;
            Orientation = CarouselViewOrientation.Horizontal;
            ItemTemplate = new DataTemplate(typeof(BannerCell));

            //_cancellation = new CancellationTokenSource();
            this.PositionSelected += BannerPositionSelected;

            //inicializarRotacao();
        }

        private void BannerPositionSelected(object sender, PositionSelectedEventArgs e)
        {
            //throw new NotImplementedException();
            _ultimaRotacao = DateTime.Now;
        }

        public bool rotacionar()
        {
            if (_rotacionando) {
                return false;
            }
            _rotacionando = true;
            try
            {
                var dataAtual = DateTime.Now;
                var tempo = dataAtual.Subtract(_ultimaRotacao).TotalMilliseconds;
                if (tempo <= Duracao)
                {
                    return false;
                }

                if (ItemsSource == null || ItemsSource.GetCount() == 0)
                {
                    return false;
                }
                var posicaoAtual = this.Position;
                posicaoAtual++;
                if (posicaoAtual >= ItemsSource.GetCount())
                {
                    posicaoAtual = 0;
                }
                _ultimaRotacao = DateTime.Now;
                this.Position = posicaoAtual;
                var banner = (BannerPecaInfo)ItemsSource.GetItem(posicaoAtual);
                var mensagem = string.Format("Banner: {0}, {1}, posição {2}", 
                    banner.Banner.Slug, dataAtual.ToString("HH:mm:ss"), posicaoAtual
                );
                System.Diagnostics.Debug.WriteLine(mensagem);
            }
            finally {
                _rotacionando = false;
            }
            return true;
        }

        public void inicializarRotacao() {
            System.Diagnostics.Debug.WriteLine("Banner: Iniciando rotação...");
            var regraServico = ServicoFactory.create();
            if (regraServico is BannerServicoBLL) {
                ((BannerServicoBLL)regraServico).BannerAtual = this;
            }
            /**
            //CancellationTokenSource cancelamento = _cancellation;
            Device.StartTimer(TimeSpan.FromSeconds(3), () => {
                if (_cancellation.IsCancellationRequested) {
                    return false;
                }
                if (ItemsSource == null || ItemsSource.GetCount() == 0) {
                    return false;
                }
                var posicaoAtual = this.Position;
                posicaoAtual++;
                if (posicaoAtual >= ItemsSource.GetCount()) {
                    posicaoAtual = 0;
                }
                Device.BeginInvokeOnMainThread(() => {
                    this.Position = posicaoAtual;
                    System.Diagnostics.Debug.WriteLine(
                        string.Format(
                            "Banner: {0}, Posição {1}",
                            DateTime.Now.ToString("HH:mm:ss"),
                            posicaoAtual
                        )
                    );
                });
                return true;
            });
    */
        }

        public void finalizarRotacao() {
            //Interlocked.Exchange(ref _cancellation, new CancellationTokenSource()).Cancel();
            var regraServico = ServicoFactory.create();
            if (regraServico is BannerServicoBLL) {
                ((BannerServicoBLL)regraServico).BannerAtual = null;
            }
            System.Diagnostics.Debug.WriteLine("Banner: Finalizando rotação...");
        }
    }
}
