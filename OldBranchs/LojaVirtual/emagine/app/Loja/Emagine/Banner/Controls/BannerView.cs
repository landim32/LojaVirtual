using CarouselView.FormsPlugin.Abstractions;
using Emagine.Banner.Cells;
using Emagine.Banner.Model;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Banner.Controls
{
    public class BannerView: CarouselViewControl
    {
        private CancellationTokenSource _cancellation;

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

            _cancellation = new CancellationTokenSource();

            inicializarRotacao();
        }

        public void inicializarRotacao() {
            CancellationTokenSource cancelamento = _cancellation;
            Device.StartTimer(TimeSpan.FromSeconds(5), () => {
                if (cancelamento.IsCancellationRequested) {
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
                });
                return true;
            });
        }

        public void finalizarRotacao() {
            Interlocked.Exchange(ref _cancellation, new CancellationTokenSource()).Cancel();
        }
    }
}
