using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Base.Pages
{
	public class BlankPage : ContentPage
	{
		public BlankPage ()
		{
			Content = new StackLayout {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill
			};
		}
	}
}