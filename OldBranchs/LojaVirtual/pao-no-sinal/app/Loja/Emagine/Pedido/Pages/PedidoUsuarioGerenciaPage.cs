using Emagine.Login.Factory;
using Emagine.Pedido.Factory;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pedido.Pages
{
    public class PedidoUsuarioGerenciaPage: Login.Pages.UsuarioGerenciaPage
    {
        private LojaAvaliacaoPage _avaliacaoPage;

        public PedidoUsuarioGerenciaPage() {
            Children.Add(_avaliacaoPage);
        }

        protected async override Task atualizandoDado()
        {
            await base.atualizandoDado();

            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var regraPedido = PedidoFactory.create();
            _avaliacaoPage.Pedidos = await regraPedido.listarAvaliacao(0, usuario.Id);
        }

        protected override void inicializarComponente() {
            base.inicializarComponente();
            _avaliacaoPage = new LojaAvaliacaoPage
            {
                Title = "Avaliações"
            };
        }
    }
}
