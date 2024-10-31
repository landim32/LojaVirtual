using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Support.Design.Widget;
using Android.Runtime;
using Android.Views;
using Android.Widget;
using Xamarin.Forms;
using Xamarin.Forms.Platform.Android;
using Xamarin.Forms.Platform.Android.AppCompat;
using Loja.Droid;

[assembly: ExportRenderer(typeof(TabbedPage), typeof(MyTabbedPageRenderer))]

namespace Loja.Droid
{
    public class MyTabbedPageRenderer : TabbedPageRenderer
    {
        private TabLayout tabLayout = null;

        protected override void OnElementChanged(ElementChangedEventArgs<TabbedPage> e)
        {
            base.OnElementChanged(e);

            this.tabLayout = (TabLayout)this.GetChildAt(1);

            var selectPosition = this.tabLayout.SelectedTabPosition;

            tabLayout.TabMode = TabLayout.ModeScrollable;
            tabLayout.TabGravity = TabLayout.GravityFill;

            Handler h = new Handler();
            Action myAction = () =>
            {
                tabLayout.GetTabAt(selectPosition).Select();
            };

            h.PostDelayed(myAction, 200);
        }
    }
}