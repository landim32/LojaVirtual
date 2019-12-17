using Emagine;
using Emagine.Base.Utils;
using Emagine.GPS.Model;
using Plugin.Geolocator;
using Plugin.Geolocator.Abstractions;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;
using System.Linq;
using Emagine.Endereco.Model;

namespace Emagine.GPS.BLL
{
    public class GPSBLL
    {
        private int _tempoMinimo = 0;
        private double _distanciaMinima = 0;
        private bool _includeHeading = false;
        private bool _processandoPosicao = false;

        public event EventHandler<GPSLocalInfo> aoAtualizarPosicao;

        public IGeolocator Current
        {
            get
            {
                return CrossGeolocator.Current;
            }
        }

        public int TempoMinimo
        {
            get
            {
                return _tempoMinimo;
            }
            set
            {
                _tempoMinimo = value;
            }
        }

        public double DistanciaMinima
        {
            get
            {
                return _distanciaMinima;
            }
            set
            {
                _distanciaMinima = value;
            }
        }

        public double DesiredAccuracy
        {
            get
            {
                var gps = CrossGeolocator.Current;
                return gps.DesiredAccuracy;
            }
            set
            {
                var gps = CrossGeolocator.Current;
                gps.DesiredAccuracy = value;
            }
        }

        public bool IncludeHeading
        {
            get
            {
                return _includeHeading;
            }
            set
            {
                var gps = CrossGeolocator.Current;
                if (gps.SupportsHeading)
                {
                    if (gps.IsListening)
                    {
                        throw new Exception("É necessario desativar o GPS para mudar isso.");
                    }
                    _includeHeading = value;
                }
                else
                {
                    _includeHeading = false;
                }
            }
        }

        public bool estaDisponivel()
        {
            //PermissaoUtils.pedirPermissao();
            if (!CrossGeolocator.IsSupported)
            {
                return false;
            }
            var gps = CrossGeolocator.Current;
            return gps.IsGeolocationAvailable;
        }

        public bool estaAtivo() {
            if (!CrossGeolocator.IsSupported) {
                return false;
            }
            return CrossGeolocator.Current.IsListening;
        }

        public async Task<bool> inicializar()
        {
            if (!estaDisponivel())
            {
                return false;
            }
            var gps = CrossGeolocator.Current;
            if (!gps.IsListening)
            {
                gps.PositionChanged += positionChanged;
                gps.PositionError += positionError;
                _processandoPosicao = false;
                return await gps.StartListeningAsync(
                    TimeSpan.FromSeconds(_tempoMinimo), _distanciaMinima, _includeHeading
                );
            }
            return true;
        }

        private void positionChanged(object sender, PositionEventArgs e)
        {
            if (!_processandoPosicao && aoAtualizarPosicao != null)
            {
                _processandoPosicao = true;
                var local = new GPSLocalInfo
                {
                    Latitude = e.Position.Latitude,
                    Longitude = e.Position.Longitude,
                    Precisao = (float)e.Position.Accuracy,
                    Sentido = (float)e.Position.Heading,
                    Velocidade = e.Position.Speed * 3.6,
                    Tempo = e.Position.Timestamp.UtcDateTime
                };
                aoAtualizarPosicao(this, local);
                _processandoPosicao = false;
            }
        }

        private void positionError(object sender, PositionErrorEventArgs e)
        {
            if (App.Current.MainPage != null)
            {
                App.Current.MainPage.DisplayAlert("Erro", "Erro ao pegar posição no GPS.", "Entendi");
            }
            else
            {
                throw new Exception("Erro ao pegar posição no GPS.");
            }
        }

        public Task<bool> finalizar()
        {
            var gps = CrossGeolocator.Current;
            gps.PositionChanged -= positionChanged;
            gps.PositionError -= positionError;
            return gps.StopListeningAsync();
        }

        public Task<Position> pegarUltimaPosicao() {
            var gps = CrossGeolocator.Current;
            return gps.GetLastKnownLocationAsync();
        }

        private async Task<Address> pegarEnderecoGooglePorPosicao(Position posicao)
        {
            var gps = CrossGeolocator.Current;
            var enderecos = await gps.GetAddressesForPositionAsync(posicao);
            if (enderecos != null) {
                return (from e in enderecos select e).FirstOrDefault();
            }
            return null;
        }

        public Task<EnderecoInfo> pegarEnderecoPorPosicao(double latitude, double longitude)
        {
            return pegarEnderecoPorPosicao(new Position(latitude, longitude));
        }

        public async Task<EnderecoInfo> pegarEnderecoPorPosicao(Position posicao) {
            var enderecoGoogle = await pegarEnderecoGooglePorPosicao(posicao);
            if (enderecoGoogle != null)
            {
                var endereco = new EnderecoInfo
                {
                    //Logradouro = enderecoGoogle.FeatureName,
                    Logradouro = enderecoGoogle.Thoroughfare,
                    Cep = enderecoGoogle.PostalCode,
                    Latitude = enderecoGoogle.Latitude,
                    Longitude = enderecoGoogle.Longitude,
                    Bairro = enderecoGoogle.SubLocality,
                    Cidade = enderecoGoogle.Locality,
                    Uf = enderecoGoogle.SubAdminArea
                };
                return endereco;
            }
            return null;
        }

        public Task<string> pegarEnderecoTextoPorPosicao(double latitude, double longitude)
        {
            return pegarEnderecoTextoPorPosicao(new Position(latitude, longitude));
        }

        public async Task<string> pegarEnderecoTextoPorPosicao(Position posicao)
        {
            var enderecoGoogle = await pegarEnderecoGooglePorPosicao(posicao);
            if (enderecoGoogle != null) {
                var endereco = new List<string>();
                if (!string.IsNullOrEmpty(enderecoGoogle.Thoroughfare)) {
                    endereco.Add(enderecoGoogle.Thoroughfare);
                }
                if (!string.IsNullOrEmpty(enderecoGoogle.SubLocality)) {
                    endereco.Add(enderecoGoogle.SubLocality);
                }
                if (!string.IsNullOrEmpty(enderecoGoogle.Locality))
                {
                    endereco.Add(enderecoGoogle.Locality);
                }
                if (!string.IsNullOrEmpty(enderecoGoogle.SubAdminArea)) {
                    endereco.Add(enderecoGoogle.SubAdminArea);
                }
                return string.Join(", ", endereco);
            }
            return null;
        }
    }
}
