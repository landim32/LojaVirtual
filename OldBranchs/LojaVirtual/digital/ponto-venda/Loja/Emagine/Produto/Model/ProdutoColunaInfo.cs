using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Produto.Model
{
    public class ProdutoColunaInfo
    {
        public ProdutoInfo Coluna1 { get; set; }
        public ProdutoInfo Coluna2 { get; set; }
        public ProdutoInfo Coluna3 { get; set; }
        public bool Coluna1Visivel {
            get {
                return (Coluna1 != null);
            }
        }
        public bool Coluna2Visivel {
            get {
                return (Coluna2 != null);
            }
        }
        public bool Coluna3Visivel {
            get {
                return (Coluna3 != null);
            }
        }
    }
}
