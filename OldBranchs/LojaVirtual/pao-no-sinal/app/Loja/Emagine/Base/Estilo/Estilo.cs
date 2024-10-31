using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Estilo
{
    public class Estilo
    {
        public const string TELA_PADRAO = "pg-padrao";
        public const string TELA_EM_BRANCO = "pg-em-branco";
        public const string BG_PADRAO = "bg-padrao";
        public const string BG_ROOT = "bg-root";
        public const string BG_INVERTIDO = "bg-invertido";
        public const string MENU_PAGINA = "menu-pagina";
        public const string MENU_BG = "menu-bg";
        public const string MENU_LISTA = "menu-lista";
        public const string MENU_TEXTO = "menu-texto";
        public const string MENU_ICONE = "menu-icone";
        public const string BTN_PRINCIPAL = "btn-principal";
        public const string BTN_PADRAO = "btn-padrao";
        public const string BTN_SUCESSO = "btn-sucesso";
        public const string BTN_AVISO = "btn-aviso";
        public const string BTN_INFO = "btn-info";
        public const string BTN_DANGER = "btn-danger";
        public const string BTN_ROOT = "btn-root";
        public const string LABEL_CONTROL = "label-control";
        public const string LABEL_CAMPO = "label-campo";
        public const string LABEL_SWITCH = "label-switch";
        public const string LISTA_ITEM = "lista-item";
        public const string LISTA_BADGE_FUNDO = "lista-badge-frame";
        public const string LISTA_BADGE_TEXTO = "lista-badge-texto";
        public const string LISTA_PADRAO = "lista-padrao";
        public const string LISTA_FRAME_PADRAO = "lista-frame-padrao";
        public const string TITULO1 = "titulo1";
        public const string TITULO2 = "titulo2";
        public const string TITULO3 = "titulo3";
        public const string ENTRY_PADRAO = "entry-padrao";
        public const string ENTRY_MATERIAL = "entry-material";
        public const string ENTRY_DATA = "entry-data";
        public const string ENTRY_TEMPO = "entry-tempo";
        public const string SEARCH_BAR = "search-bar";
        public const string ICONE_PADRAO = "icone-padrao";
        public const string HR = "hr";
        public const string TOTAL_FRAME = "total-frame";
        public const string TOTAL_LABEL = "total-label";
        public const string TOTAL_TEXTO = "total-texto";
        public const string ENDERECO_ITEM = "endereco-item";
        public const string ENDERECO_FRAME = "endereco-frame";
        public const string ENDERECO_TITULO = "endereco-titulo";
        public const string ENDERECO_CAMPO = "endereco-campo";
        public const string ENDERECO_LABEL = "endereco-label";

        private ResourceDictionary _resources = null;
        private Color _primaryColor;
        private Color _successColor;
        private Color _infoColor;
        private Color _warningColor;
        private Color _dangerColor;
        private Color _defaultColor;
        private string _fontDefaultRegular;
        private string _fontDefaultBold;
        private string _fontDefaultItalic;
        private EstiloPage _telaPadrao = null;
        private EstiloPage _telaEmBranco = null;
        private EstiloStackLayout _BgPadrao = null;
        private EstiloStackLayout _BgRoot = null;
        private EstiloStackLayout _BgInvertido = null;
        private EstiloPage _MenuPagina = null;
        private EstiloStackLayout _MenuBg = null;
        private EstiloListView _MenuLista = null;
        private EstiloLabel _MenuTexto = null;
        private EstiloIcon _MenuIcone = null;
        private EstiloBotao _botaoPrincipal = null;
        private EstiloBotao _botaoPadrao = null;
        private EstiloBotao _botaoSucesso = null;
        private EstiloBotao _botaoAviso = null;
        private EstiloBotao _btnDanger = null;
        private EstiloBotao _botaoInfo = null;
        private EstiloMenuButton _botaoRoot = null;
        private EstiloLabel _labelControle = null;
        private EstiloLabel _labelCampo = null;
        private EstiloLabel _labelSwitch = null;
        private EstiloLabel _listaItem = null;
        private EstiloLabel _listaBadgeTexto = null;
        private EstiloFrame _listaBadgeFundo = null;
        private EstiloLabel _titulo1 = null;
        private EstiloLabel _titulo2 = null;
        private EstiloLabel _titulo3 = null;
        private EstiloEntry _entryPadrao = null;
        private EstiloXfxEntry _entryMaterial = null;
        private EstiloDatePicker _entryData = null;
        private EstiloTimePicker _entryTempo = null;
        private EstiloSearch _searchBar = null;
        private EstiloIcon _iconePadrao = null;
        private EstiloBoxView _hr = null;
        private EstiloFrame _totalFrame = null;
        private EstiloLabel _totalLabel = null;
        private EstiloLabel _totalTexto = null;
        private EstiloLabel _enderecoItem = null;
        private EstiloFrame _enderecoFrame = null;
        private EstiloLabel _enderecoTitulo = null;
        private EstiloLabel _enderecoCampo = null;
        private EstiloLabel _enderecoLabel = null;
        private EstiloListView _listaPadrao = null;
        private EstiloFrame _listaFramePadrao = null;

        public Estilo() {
            _resources = new ResourceDictionary();
            _telaPadrao = new EstiloPage();
            _telaEmBranco = new EstiloPage();
            _BgPadrao = new EstiloStackLayout();
            _BgRoot = new EstiloStackLayout();
            _BgInvertido = new EstiloStackLayout();
            _MenuPagina = new EstiloPage();
            _MenuBg = new EstiloStackLayout();
            _MenuLista = new EstiloListView();
            _MenuTexto = new EstiloLabel();
            _MenuIcone = new EstiloIcon();
            _botaoPrincipal = new EstiloBotao();
            _botaoPadrao = new EstiloBotao();
            _botaoSucesso = new EstiloBotao();
            _botaoAviso = new EstiloBotao();
            _botaoInfo = new EstiloBotao();
            _btnDanger = new EstiloBotao();
            _botaoRoot = new EstiloMenuButton();
            _labelControle = new EstiloLabel();
            _labelCampo = new EstiloLabel();
            _labelSwitch = new EstiloLabel();
            _listaItem = new EstiloLabel();
            _listaBadgeFundo = new EstiloFrame();
            _listaBadgeTexto = new EstiloLabel();
            _titulo1 = new EstiloLabel();
            _titulo2 = new EstiloLabel();
            _titulo3 = new EstiloLabel();
            _entryPadrao = new EstiloEntry();
            _entryMaterial = new EstiloXfxEntry();
            _entryData = new EstiloDatePicker();
            _entryTempo = new EstiloTimePicker();
            _searchBar = new EstiloSearch();
            _iconePadrao = new EstiloIcon();
            _hr = new EstiloBoxView();
            _totalFrame = new EstiloFrame();
            _totalLabel = new EstiloLabel();
            _totalTexto = new EstiloLabel();
            _enderecoItem = new EstiloLabel();
            _enderecoFrame = new EstiloFrame();
            _enderecoTitulo = new EstiloLabel();
            _enderecoCampo = new EstiloLabel();
            _enderecoLabel = new EstiloLabel();
            _listaPadrao = new EstiloListView();
            _listaFramePadrao = new EstiloFrame();
    }

        public Color PrimaryColor {
            get {
                return _primaryColor;
            }
            set {
                _primaryColor = value;
                BotaoPrincipal.BackgroundColor = _primaryColor;
            }
        }

        public Color SuccessColor
        {
            get
            {
                return _successColor;
            }
            set
            {
                _successColor = value;
                BotaoSucesso.BackgroundColor = _successColor;
            }
        }

        public Color InfoColor {
            get {
                return _infoColor;
            }
            set {
                _infoColor = value;
                BotaoInfo.BackgroundColor = _infoColor;
            }
        }

        public Color WarningColor {
            get {
                return _warningColor;
            }
            set {
                _warningColor = value;
                BotaoAviso.BackgroundColor = _warningColor;
            }
        }

        public Color DangerColor
        {
            get
            {
                return _dangerColor;
            }
            set
            {
                _dangerColor = value;
                ButtonDanger.BackgroundColor = _dangerColor;
            }
        }

        public Color DefaultColor {
            get {
                return _defaultColor;
            }
            set {
                _defaultColor = value;
                BotaoPadrao.BackgroundColor = _defaultColor;
            }
        }

        public Color BarTitleColor { get; set; }
        public Color BarBackgroundColor { get; set; }

        public string FontDefaultRegular {
            get {
                return _fontDefaultRegular;
            }
            set {
                _fontDefaultRegular = value;
            }
        }

        public string FontDefaultBold {
            get {
                return _fontDefaultBold;
            }
            set {
                _fontDefaultBold = value;
            }
        }

        public string FontDefaultItalic
        {
            get
            {
                return _fontDefaultItalic;
            }
            set
            {
                _fontDefaultItalic = value;
            }
        }

        public EstiloPage TelaPadrao {
            get {
                return _telaPadrao;
            }
            set {
                _telaPadrao = value;
            }
        }

        public EstiloPage TelaEmBranco
        {
            get {
                return _telaEmBranco;
            }
            set {
                _telaEmBranco = value;
            }
        }

        public EstiloStackLayout BgPadrao
        {
            get {
                return _BgPadrao;
            }
            set {
                _BgPadrao = value;
            }
        }

        public EstiloStackLayout BgRoot
        {
            get
            {
                return _BgRoot;
            }
            set {
                _BgRoot = value;
            }
        }

        public EstiloStackLayout BgInvestido {
            get {
                return _BgInvertido;
            }
            set {
                _BgInvertido = value;
            }
        }

        public EstiloPage MenuPagina {
            get {
                return _MenuPagina;
            }
            set {
                _MenuPagina = value;
            }
        }

        public EstiloStackLayout MenuBg {
            get {
                return _MenuBg;
            }
            set {
                _MenuBg = value;
            }
        }

        public EstiloListView MenuLista {
            get {
                return _MenuLista;
            }
            set {
                _MenuLista = value;
            }
        }

        public EstiloLabel MenuTexto {
            get {
                return _MenuTexto;
            }
            set {
                _MenuTexto = value;
            }
        }

        public EstiloIcon MenuIcone {
            get {
                return _MenuIcone;
            }
            set {
                _MenuIcone = value;
            }
        }

        public EstiloBotao BotaoPrincipal {
            get {
                return _botaoPrincipal;
            }
            set {
                _botaoPrincipal = value;
            }
        }

        public EstiloBotao BotaoPadrao {
            get {
                return _botaoPadrao;
            }
            set {
                _botaoPadrao = value;
            }
        }

        public EstiloBotao BotaoSucesso {
            get {
                return _botaoSucesso;
            }
            set {
                _botaoSucesso = value;
            }
        }

        public EstiloBotao BotaoAviso {
            get {
                return _botaoAviso;
            }
            set {
                _botaoAviso = value;
            }
        }

        public EstiloBotao BotaoInfo {
            get {
                return _botaoInfo;
            }
            set {
                _botaoInfo = value;
            }
        }

        public EstiloBotao ButtonDanger {
            get {
                return _btnDanger;
            }
            set {
                _btnDanger = value;
            }
        }

        public EstiloMenuButton BotaoRoot {
            get {
                return _botaoRoot;
            }
            set {
                _botaoRoot = value;
            }
        }

        public EstiloLabel LabelControle {
            get {
                return _labelControle;
            }
            set {
                _labelControle = value;
            }
        }

        public EstiloLabel LabelCampo {
            get {
                return _labelCampo;
            }
            set {
                _labelCampo = value;
            }
        }

        public EstiloLabel LabelSwitch {
            get {
                return _labelSwitch;
            }
            set {
                _labelSwitch = value;
            }
        }

        public EstiloLabel ListaItem {
            get {
                return _listaItem;
            }
            set {
                _listaItem = value;
            }
        }

        public EstiloLabel ListaBadgeTexto {
            get {
                return _listaBadgeTexto;
            }
            set {
                _listaBadgeTexto = value;
            }
        }

        public EstiloFrame ListaBadgeFundo {
            get {
                return _listaBadgeFundo;
            }
            set {
                _listaBadgeFundo = value;
            }
        }


        public EstiloListView ListaPadrao
        {
            get
            {
                return _listaPadrao;
            }
            set
            {
                _listaPadrao = value;
            }
        }

        public EstiloFrame ListaFramePadrao
        {
            get
            {
                return _listaFramePadrao;
            }
            set
            {
                _listaFramePadrao = value;
            }
        }

        public EstiloLabel Titulo1 {
            get {
                return _titulo1;
            }
            set {
                _titulo1 = value;
            }
        }

        public EstiloLabel Titulo2 {
            get {
                return _titulo2;
            }
            set {
                _titulo2 = value;
            }
        }

        public EstiloLabel Titulo3 {
            get {
                return _titulo3;
            }
            set {
                _titulo3 = value;
            }
        }

        public EstiloEntry EntryPadrao {
            get {
                return _entryPadrao;
            }
            set {
                _entryPadrao = value;
            }
        }

        public EstiloXfxEntry EntryMaterial
        {
            get
            {
                return _entryMaterial;
            }
            set
            {
                _entryMaterial = value;
            }
        }

        public EstiloDatePicker EntryData {
            get {
                return _entryData;
            }
            set {
                _entryData = value;
            }
        }

        public EstiloTimePicker EntryTempo {
            get {
                return _entryTempo;
            }
            set {
                _entryTempo = value;
            }
        }

        public EstiloSearch SearchBar {
            get {
                return _searchBar;
            }
            set {
                _searchBar = value;
            }
        }

        public EstiloIcon IconePadrao {
            get {
                return _iconePadrao;
            }
            set {
                _iconePadrao = value;
            }
        }

        public EstiloBoxView Hr {
            get {
                return _hr;
            }
            set {
                _hr = value;
            }
        }

        public EstiloFrame TotalFrame {
            get {
                return _totalFrame;
            }
            set {
                _totalFrame = value;
            }
        }

        public EstiloLabel TotalLabel {
            get {
                return _totalLabel;
            }
            set {
                _totalLabel = value;
            }
        }

        public EstiloLabel TotalTexto {
            get {
                return _totalTexto;
            }
            set {
                _totalTexto = value;
            }
        }

        public EstiloLabel EnderecoItem {
            get {
                return _enderecoItem;
            }
            set {
                _enderecoItem = value;
            }
        }

        public EstiloFrame EnderecoFrame {
            get {
                return _enderecoFrame;
            }
            set {
                _enderecoFrame = value;
            }
        }

        public EstiloLabel EnderecoTitulo {
            get {
                return _enderecoTitulo;
            }
            set {
                _enderecoTitulo = value;
            }
        }

        public EstiloLabel EnderecoCampo {
            get {
                return _enderecoCampo;
            }
            set {
                _enderecoCampo = value;
            }
        }

        public EstiloLabel EnderecoLabel {
            get {
                return _enderecoLabel;
            }
            set {
                _enderecoLabel = value;
            }
        }

        public EstiloProduto Produto { get; set; } = new EstiloProduto();

        public Style this[string index]
        {
            get {
                if (_resources.ContainsKey(index))
                {
                    return (Style)_resources[index];
                }
                return null;
            }
            set {
                _resources[index] = value;
            }
        }

        private void gravarEstilo(string chave, Style estilo) {
            if (_resources.ContainsKey(chave))
            {
                _resources[chave] = estilo;
            }
            else
            {
                _resources.Add(chave, estilo);
            }
        }

        public ResourceDictionary gerar() {
            gravarEstilo(TELA_PADRAO, _telaPadrao.gerar());
            gravarEstilo(TELA_EM_BRANCO, _telaEmBranco.gerar());
            gravarEstilo(BG_PADRAO, _BgPadrao.gerar());
            gravarEstilo(BG_ROOT, _BgRoot.gerar());
            gravarEstilo(BG_INVERTIDO, _BgInvertido.gerar());
            gravarEstilo(MENU_PAGINA, _MenuPagina.gerar());
            gravarEstilo(MENU_BG, _MenuBg.gerar());
            gravarEstilo(MENU_LISTA, _MenuLista.gerar());
            gravarEstilo(MENU_TEXTO, _MenuTexto.gerar());
            gravarEstilo(MENU_ICONE, _MenuIcone.gerar());
            gravarEstilo(BTN_PRINCIPAL, _botaoPrincipal.gerar());
            gravarEstilo(BTN_PADRAO, _botaoPadrao.gerar());
            gravarEstilo(BTN_SUCESSO, _botaoSucesso.gerar());
            gravarEstilo(BTN_AVISO, _botaoAviso.gerar());
            gravarEstilo(BTN_DANGER, _btnDanger.gerar());
            gravarEstilo(BTN_INFO, _botaoInfo.gerar());
            gravarEstilo(BTN_ROOT, _botaoRoot.gerar());
            gravarEstilo(LABEL_CONTROL, _labelControle.gerar());
            gravarEstilo(LABEL_CAMPO, _labelCampo.gerar());
            gravarEstilo(LABEL_SWITCH, _labelSwitch.gerar());
            gravarEstilo(LISTA_PADRAO, _listaPadrao.gerar());
            gravarEstilo(LISTA_FRAME_PADRAO, _listaFramePadrao.gerar());
            gravarEstilo(LISTA_ITEM, _listaItem.gerar());
            gravarEstilo(LISTA_BADGE_FUNDO, _listaBadgeFundo.gerar());
            gravarEstilo(LISTA_BADGE_TEXTO, _listaBadgeTexto.gerar());
            gravarEstilo(TITULO1, _titulo1.gerar());
            gravarEstilo(TITULO2, _titulo2.gerar());
            gravarEstilo(TITULO3, _titulo3.gerar());
            gravarEstilo(ENTRY_PADRAO, _entryPadrao.gerar());
            gravarEstilo(ENTRY_MATERIAL, _entryMaterial.gerar());
            gravarEstilo(ENTRY_DATA, _entryData.gerar());
            gravarEstilo(ENTRY_TEMPO, _entryTempo.gerar());
            gravarEstilo(SEARCH_BAR, _searchBar.gerar());
            gravarEstilo(ICONE_PADRAO, _iconePadrao.gerar());
            gravarEstilo(TOTAL_FRAME, _totalFrame.gerar());
            gravarEstilo(TOTAL_LABEL, _totalLabel.gerar());
            gravarEstilo(TOTAL_TEXTO, _totalTexto.gerar());
            gravarEstilo(HR, _hr.gerar());
            gravarEstilo(ENDERECO_ITEM, _enderecoItem.gerar());
            gravarEstilo(ENDERECO_FRAME, _enderecoFrame.gerar());
            gravarEstilo(ENDERECO_TITULO, _enderecoTitulo.gerar());
            gravarEstilo(ENDERECO_CAMPO, _enderecoCampo.gerar());
            gravarEstilo(ENDERECO_LABEL, _enderecoLabel.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_FRAME, Produto.Frame.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_FOTO, Produto.Foto.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_TITULO, Produto.Titulo.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_ICONE, Produto.Icone.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_LABEL, Produto.Label.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_VOLUME, Produto.Volume.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_QUANTIDADE, Produto.Quantidade.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_DESCRICAO, Produto.Descricao.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_PRECO_MOEDA, Produto.PrecoMoeda.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_PRECO_VALOR, Produto.PrecoValor.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_PROMOCAO_MOEDA, Produto.PromocaoMoeda.gerar());
            gravarEstilo(EstiloProduto.PRODUTO_PROMOCAO_VALOR, Produto.PromocaoValor.gerar());
            return _resources;
        }

        private static Estilo _Current;

        public static Estilo Current {
            get {
                if (_Current == null) {
                    _Current = new Estilo();
                }
                return _Current;
            }
            set
            {
                _Current = value;
            }
        }
    }
}
