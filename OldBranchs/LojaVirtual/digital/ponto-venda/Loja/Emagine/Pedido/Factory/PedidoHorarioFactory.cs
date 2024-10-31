using Emagine.Pedido.BLL;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Pedido.Factory
{
    public class PedidoHorarioFactory
    {
        private static PedidoHorarioBLL _horario;

        public static PedidoHorarioBLL create()
        {
            if (_horario == null)
            {
                _horario = new PedidoHorarioBLL();
            }
            return _horario;
        }
    }
}
