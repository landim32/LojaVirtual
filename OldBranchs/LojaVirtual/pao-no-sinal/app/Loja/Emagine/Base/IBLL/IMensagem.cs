using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.IBLL
{
    public interface IMensagem
    {
        void exibirAviso(string Titulo, string Mensagem);
        bool notificar(int id, string titulo, string descricao, int icone = 0, string audio = null, double velocidade = 0);
        bool notificarPermanente(int id, string titulo, string descricao, int idParar, string textoParar, string acaoParar);
        bool pararNotificaoPermanente(int id);
		bool enviarEmail(string para, string titulo, string mensagem);
        void vibrar(int milisegundo);
    }
}
