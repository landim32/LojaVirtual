using System;
using System.Collections.Generic;
using System.Linq;
using Emagine.Login.Model;
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
        public long? IdPagamento { get; set; }

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
                return UltimaAlteracao.ToString("YYYY-mm-dd H:i:s");
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
        public DateTime? DataRetirada { get; set; }
        [JsonProperty("data_retirada_str")]
        public string DataRetiradaStr { get; set; }

        [JsonProperty("data_retirada_executada")]
        public DateTime? DataRetiradaExecutada { get; set; }
        [JsonProperty("data_retirada_executada_str")]
        public string DataRetiradaExecutadaStr { get; set; }

        [JsonProperty("data_entrega")]
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
                var origem = Locais.Where(x => x.Tipo == FreteLocalTipoEnum.Saida).FirstOrDefault();
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
                //var origem = Locais.Where(x => x.Tipo == FreteLocalTipoEnum.Saida).FirstOrDefault();
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

        public bool TemSaida() {
            bool retorno = false;
            foreach (var local in Locais) {
                if (local.Tipo == FreteLocalTipoEnum.Saida) {
                    retorno = true;
                    break;
                }
            }
            return retorno;
        }

        public bool TemDestino()
        {
            bool retorno = false;
            foreach (var local in Locais)
            {
                if (local.Tipo == FreteLocalTipoEnum.Destino)
                {
                    retorno = true;
                    break;
                }
            }
            return retorno;
        }
    }
}
