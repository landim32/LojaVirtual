using Emagine.Pagamento.Pages;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Pedido.Factory
{
    public static class EntregaMetodoPageFactory
    {
        public static Type Tipo { get; set; } = typeof(EntregaMetodoPage);

        public static EntregaMetodoPage create()
        {
            return (EntregaMetodoPage) Activator.CreateInstance(Tipo);
        }
    }
}
