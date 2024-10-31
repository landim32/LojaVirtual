using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Model
{
    public enum SituacaoEnum
    {
        Ativo = 1,
        AguardandoValidacao = 2,
        Bloqueado = 3,
        Inativo = 4
    }
}
