using Xamarin.Forms;

namespace Radar.Pages
{
	public class GrupoTabbedPage : TabbedPage
	{
		public GrupoTabbedPage()
		{
			var navigationPage = new NavigationPage(new GrupoPage());
			navigationPage.Icon = "navicon.png";
			navigationPage.Title = "Schedule";

			//Children.Add(new GrupoAdministracaoPage());
			Children.Add(navigationPage);

		}
	}
}