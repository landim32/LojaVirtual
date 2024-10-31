using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Base.Controls
{
    public class DropDownList: BaseDropDownList
    {
        public event EventHandler<object> Clicked;

        public object Value
        {
            get {
                return _value;
            }
            set {
                _value = value;
                if (_value != null) {
                    _textoLabel.Text = value.ToString();
                }
                atualizarPlaceholder();
            }
        }

        protected override void OnClicked(object sender, EventArgs e)
        {
            Clicked?.Invoke(this, e);
        }
    }
}
