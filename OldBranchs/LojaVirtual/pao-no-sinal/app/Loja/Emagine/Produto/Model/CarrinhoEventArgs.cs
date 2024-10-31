using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Produto.Model
{
    public class CarrinhoEventArgs: EventArgs
    {
        public int Quantidade { get; set; }
        public double Total { get; set; }

        public CarrinhoEventArgs(int quantidade, double total) : base() {
            Quantidade = quantidade;
            Total = total;
        }
    }
}
