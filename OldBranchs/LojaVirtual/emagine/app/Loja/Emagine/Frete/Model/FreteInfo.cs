using System;
using System.Collections.Generic;
using System.Linq;
using Emagine.Login.Model;
using Emagine.Pagamento.Model;
using Newtonsoft.Json;
using Xamarin.Forms;

namespace Emagine.Frete.Model
{
    public class FreteInfo
    {
        public FreteInfo() {
            Locais = new List<FreteLocalInfo>();
            Veiculos = new List<TipoVeiculoInfo>();
            Carrocerias = new List<TipoCarroceriaInfo>();
        }

        [JsonProperty("id_frete")]
        public int Id { get; set; }

        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }

        [JsonProperty("id_pagamento")]
        public int? IdPagamento { get; set; }

        [JsonProperty("pagamento")]
        public PagamentoInfo Pagamento { get; set; }

        [JsonProperty("usuario")]
        public UsuarioInfo Usuario { get; set; }

        [JsonProperty("id_motorista")]
        public int? IdMotorista { get; set; }

        [JsonProperty("motorista")]
        public MotoristaInfo Motorista { get; set; }

        [JsonIgnore]
        public string NomeMotorista {
            get {
                return (Motorista != null ? (Motorista.Usuario != null ? Motorista.Usuario.Nome : "-") : "");
            }
        }

        [JsonProperty("data_inclusao")]
        public string _DataInclusao {
            get {
                return DataInclusao.ToString("yyyy-MM-dd HH:mm:ss");
            }
            set {
                DateTime dataInclusao;
                if (DateTime.TryParse(value, out dataInclusao)) {
                    DataInclusao = dataInclusao;
                }
            }
        }

        [JsonIgnore]
        public DateTime DataInclusao { get; set; }

        [JsonProperty("data_inclusao_str")]
        public string DataInclusaoStr { get; set; }

        [JsonIgnore]
        public DateTime UltimaAlteracao { get; set; }

        [JsonProperty("ultima_alteracao")]
        public string _UltimaAlteracao {
            get {
                return UltimaAlteracao.ToString("yyyy-MM-dd HH:mm:ss");
            }
            set {
                DateTime ultimaAlteracao;
                if (DateTime.TryParse(value, out ultimaAlteracao)) {
                    UltimaAlteracao = ultimaAlteracao;
                }
            }
        }

        [JsonProperty("ultima_alteracao_str")]
        public string UltimaAlteracaoStr { get; set; }

        [JsonProperty("foto")]
        public string Foto { get; set; }

        [JsonProperty("foto_url")]
        public string FotoUrl { get; set; }

        [JsonProperty("foto_base64")]
        public string FotoBase64 { get; set; }

        [JsonProperty("data_retirada")]
        public string _DataRetirada {
            get {
                if (DataRetirada.HasValue) {
                    return DataRetirada.Value.ToString("yyyy-MM-dd HH:mm:ss");
                }
                return null;
            }
            set {
                if (!string.IsNullOrEmpty(value))
                {
                    DateTime data;
                    if (DateTime.TryParse(value, out data)) {
                        DataRetirada = data;
                    }
                    else {
                        DataRetirada = null;
                    }
                }
                else {
                    DataRetirada = null;
                }
            }
        }

        [JsonIgnore]
        public DateTime? DataRetirada { get; set; }

        [JsonProperty("data_retirada_str")]
        public string DataRetiradaStr { get; set; }

        [JsonProperty("data_retirada_executada")]
        public DateTime? DataRetiradaExecutada { get; set; }
        [JsonProperty("data_retirada_executada_str")]
        public string DataRetiradaExecutadaStr { get; set; }

        [JsonProperty("data_entrega")]
        public string _DataEntrega {
            get {
                if (DataEntrega.HasValue) {
                    return DataEntrega.Value.ToString("yyyy-MM-dd HH:mm:ss");
                }
                return null;
            }
            set {
                if (!string.IsNullOrEmpty(value)) {
                    DateTime data;
                    if (DateTime.TryParse(value, out data)) {
                        DataEntrega = data;
                    }
                    else {
                        DataEntrega = null;
                    }
                }
                else {
                    DataEntrega = null;
                }
            }
        }

        [JsonIgnore]
        public DateTime? DataEntrega { get; set; }

        [JsonProperty("data_entrega_str")]
        public string DataEntregaStr { get; set; }

        [JsonProperty("data_entrega_executada")]
        public DateTime? DataEntregaExecutada { get; set; }
        [JsonProperty("data_entrega_executada_str")]
        public string DataEntregaExecutadaStr { get; set; }

        [JsonIgnore]
        public string DataEntregaLbl { 
            get
            {
                if (DataRetiradaStr == DataEntregaStr)
                    return "-";
                else
                    return DataEntregaStr;
            }
        }

        /*"cod_caminhao": null,
        "caminhao": null,
        "cod_carroceria": null,
        "carroceria": null,*/

        [JsonProperty("preco")]
        public double Preco { get; set; }

        [JsonProperty("peso")]
        public double Peso { get; set; }

        [JsonProperty("largura")]
        public double Largura { get; set; }

        [JsonProperty("altura")]
        public double Altura { get; set; }

        [JsonProperty("tempo")]
        public int? Tempo { get; set; }

        [JsonProperty("tempo_str")]
        public string TempoStr { get; set; }

        [JsonProperty("rota_encontrada")]
        public bool RotaEncontrada { get; set; }

        [JsonProperty("profundidade")]
        public double Profundidade { get; set; }

        [JsonProperty("nota_frete")]
        public int NotaFrete { get; set; }

        [JsonProperty("nota_motorista")]
        public int NotaMotorista { get; set; }

        [JsonProperty("distancia")]
        public int Distancia { get; set; }

        /*
        [JsonIgnore]
        public double DistanciaStr { 
            get {
                return Distancia / 1000;
            }
        }
        */

        [JsonProperty("distancia_str")]
        public string DistanciaStr { get; set; }

        [JsonProperty("observacao")]
        public string Observacao { get; set; }

        [JsonProperty("endereco_origem")]
        public string EnderecoOrigem { get; set; }

        [JsonProperty("endereco_destino")]
        public string EnderecoDestino { get; set; }

        [JsonProperty("polyline")]
        public string Polyline { get; set; }


        [JsonProperty("cod_situacao")]
        public int _CodSituacao {
            get {
                return (int) Situacao;
            }
            set {
                Situacao = (FreteSituacaoEnum) value;
            }
        }

        [JsonIgnore]
        public FreteSituacaoEnum Situacao { get; set; }

        [JsonProperty("situacao")]
        public string SituacaoStr { get; set; }

        /*
        [JsonIgnore]
        public string SituacaoLblMaisCargas
        {
            get
            {
                switch (Situacao)
                {
                    case FreteSituacaoEnum.AguardandoPagamento:
                        return "Aguardando pagamento";
                    case FreteSituacaoEnum.Cancelado:
                        return "Cancelado";
                    case FreteSituacaoEnum.EntregaConfirmada:
                        return "Concluído e avaliado";
                    case FreteSituacaoEnum.Entregando:
                        return "Confirmado: Em trânsito";
                    case FreteSituacaoEnum.Entregue:
                        return "Confirmado: Entrega Realizada";
                    case FreteSituacaoEnum.PegandoEncomenda:
                        return "Confirmado: Aguardando retirada";
                    case FreteSituacaoEnum.ProcurandoMotorista:
                        return "Aguardando definição de fretista";
                }
                return "-";
            }
        }

        [JsonIgnore]
        public Color CorSituacao
        {
            get
            {
                switch (Situacao)
                {
                    case FreteSituacaoEnum.AguardandoPagamento:
                        return Color.FromHex("#003e79");
                    case FreteSituacaoEnum.Cancelado:
                        return Color.FromHex("#f0566b");
                    case FreteSituacaoEnum.EntregaConfirmada:
                        return Color.FromHex("#8fc85a");
                    case FreteSituacaoEnum.Entregando:
                        return Color.FromHex("#825aa3");
                    case FreteSituacaoEnum.Entregue:
                        return Color.FromHex("#bc3b9e");
                    case FreteSituacaoEnum.PegandoEncomenda:
                        return Color.FromHex("#f49944");
                    case FreteSituacaoEnum.ProcurandoMotorista:
                        return Color.FromHex("#458ceb");
                }
                return Color.Transparent;
            }
        }

        [JsonIgnore]
        public string EnderecoOrigem { 
            get{
                var origem = Locais.Where(x => x.Tipo == FreteLocalTipoEnum.Origem).FirstOrDefault();
                if (origem == null)
                    return null;
                else{
                    return origem.Latitude.ToString().Replace(',', '.') + ","+ origem.Longitude.ToString().Replace(',', '.');
                }
            }
        }

        [JsonIgnore]
        public string EnderecoDestino { 
            get{
                var origem = Locais.Where(x => x.Tipo == FreteLocalTipoEnum.Destino).FirstOrDefault();
                if (origem == null)
                    return null;
                else
                {
                    return origem.Latitude.ToString().Replace(',', '.') + "," + origem.Longitude.ToString().Replace(',', '.');
                }        
            } 
        }
        */

        [JsonIgnore]
        public string TituloFreteMotoristaLbl
        {
            get {
                return (Observacao ?? "Carga") + "(" + Peso + "t" + ")";
            }
        }

        [JsonIgnore]
        public string OrigemDestinoStr
        {
            get
            {
                //var origem = Locais.Where(x => x.Tipo == FreteLocalTipoEnum.Origem).FirstOrDefault();
                //var destino = Locais.Where(x => x.Tipo == FreteLocalTipoEnum.Destino).FirstOrDefault();
                return EnderecoOrigem + " > " + EnderecoDestino;
            }
        }

        [JsonIgnore]
        public bool MostraP { get; set; } = false;

        [JsonIgnore]
        public bool PodeEditar { 
            get {
                return Situacao == FreteSituacaoEnum.ProcurandoMotorista ? true : false;
            }
        }

        [JsonProperty("locais")]
        public IList<FreteLocalInfo> Locais { get; set; }

        [JsonProperty("veiculos")]
        public IList<TipoVeiculoInfo> Veiculos { get; set; }

        [JsonProperty("veiculos_str")]
        public string VeiculoStr { get; set; }

        [JsonProperty("carrocerias")]
        public IList<TipoCarroceriaInfo> Carrocerias { get; set; }

        [JsonProperty("carrocerias_str")]
        public string CarroceriaStr { get; set; }

        [JsonProperty("dimensao")]
        public string Dimensao { get; set; }

        [JsonProperty("duracao")]
        public int Duracao { get; set; }

        [JsonIgnore]
        public string DuracaoStr {
            get {
                return new TimeSpan(0, 0, Duracao).ToString();
            }
        }

        [JsonIgnore]
        public TimeSpan Previsao {
            get {
                if (DataEntrega.HasValue && DataRetirada.HasValue) {
                    return DataEntrega.Value.Subtract(DataRetirada.Value);
                }
                return TimeSpan.Zero;
            }
        }

        [JsonIgnore]
        public int PrevisaoTempo {
            get {
                return (int)Math.Floor(Previsao.TotalSeconds);
            }
        }

        [JsonIgnore]
        public string PrevisaoStr {
            get {
                var p = Previsao;
                return p.Hours.ToString().PadLeft(2, '0') + ":" + p.Minutes.ToString().PadLeft(2, '0');
            }
        }

        [Obsolete("Use TemOrigem")]
        public bool TemSaida()
        {
            return TemOrigem();
        }

        public bool TemOrigem() {
            return ((
                from l in Locais where (l.Tipo == FreteLocalTipoEnum.Origem) select l
            ).FirstOrDefault() != null);
        }

        public bool TemDestino() {
            return ((
                from l in Locais where (l.Tipo == FreteLocalTipoEnum.Destino) select l
            ).FirstOrDefault() != null);
        }

        public FreteLocalInfo LocalOrigem {
            get {
                return (
                    from l in Locais where (l.Tipo == FreteLocalTipoEnum.Origem) select l
                ).FirstOrDefault();
            }
            set {
                var origem = (
                    from l in Locais where (l.Tipo == FreteLocalTipoEnum.Origem) select l
                ).FirstOrDefault();
                if (origem != null) {
                    Locais.Remove(origem);
                }
                Locais.Insert(0, value);
            }
        }

        public FreteLocalInfo LocalDestino {
            get {
                return (
                    from l in Locais where (l.Tipo == FreteLocalTipoEnum.Destino) select l
                ).FirstOrDefault();
            }
            set {
                var destino = (
                    from l in Locais where (l.Tipo == FreteLocalTipoEnum.Destino) select l
                ).FirstOrDefault();
                if (destino != null) {
                    Locais.Remove(destino);
                }
                Locais.Add(value);
            }
        }
    }
}
