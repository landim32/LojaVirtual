using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;
using Emagine.Base.Controls;
using FormsPlugin.Iconize;
using FormsPlugin.Iconize.Droid;
using Frete.Droid;
using Plugin.Iconize.Droid;
using Xamarin.Forms;
using Xamarin.Forms.Platform.Android;

/*
#if USE_FASTRENDERERS
using ButtonRenderer = Xamarin.Forms.Platform.Android.FastRenderers.ButtonRenderer;
#else
using ButtonRenderer = Xamarin.Forms.Platform.Android.AppCompat.ButtonRenderer;
#endif
*/
[assembly: ExportRenderer(typeof(CircleIconButton), typeof(CircleIconButtonRenderer))]

namespace Frete.Droid
{

    public class CircleIconButtonRenderer : ButtonRenderer
    {
        public CircleIconButtonRenderer(Context context) : base(context)
        {

        }

        protected override void OnDraw(Android.Graphics.Canvas canvas)
        {
            base.OnDraw(canvas);
        }

        private IconButton Button => Element as IconButton;

        /// <summary>
        /// Called when [attached to window].
        /// </summary>
        protected override void OnAttachedToWindow()
        {
            base.OnAttachedToWindow();
            #if USE_FASTRENDERERS       
            TextChanged += OnTextChanged;
            #else
            Control.TextChanged += OnTextChanged;
            #endif
        }

        /// <summary>
        /// Called when [detached from window].
        /// </summary>
        protected override void OnDetachedFromWindow()
        {
            #if USE_FASTRENDERERS
            TextChanged -= OnTextChanged;
            #else
            Control.TextChanged -= OnTextChanged;
            #endif
            base.OnDetachedFromWindow();
        }

        /// <summary>
        /// Raises the <see cref="E:ElementChanged" /> event.
        /// </summary>
        /// <param name="e">The <see cref="ElementChangedEventArgs{Button}" /> instance containing the event data.</param>
        protected override void OnElementChanged(ElementChangedEventArgs<Xamarin.Forms.Button> e)
        {
            base.OnElementChanged(e);

            if (Button == null)
                return;

            #if USE_FASTRENDERERS
            SetAllCaps(false);
            #else
            Control.SetAllCaps(false);
            #endif

            if (Build.VERSION.SdkInt >= BuildVersionCodes.Lollipop)
            {
                this.SetBackground(null);
                StateListAnimator = null;
            }

            UpdateText();
        }

        /// <summary>
        /// Called when [element property changed].
        /// </summary>
        /// <param name="sender">The sender.</param>
        /// <param name="e">The <see cref="PropertyChangedEventArgs" /> instance containing the event data.</param>
        protected override void OnElementPropertyChanged(Object sender, PropertyChangedEventArgs e)
        {
            base.OnElementPropertyChanged(sender, e);

            if (Button == null)
                return;

            switch (e.PropertyName)
            {
                case nameof(IconButton.FontSize):
                case nameof(IconButton.TextColor):
                    UpdateText();
                    break;
            }
        }

        /// <summary>
        /// Called when [text changed].
        /// </summary>
        /// <param name="sender">The sender.</param>
        /// <param name="e">The <see cref="Android.Text.TextChangedEventArgs" /> instance containing the event data.</param>
        private void OnTextChanged(Object sender, Android.Text.TextChangedEventArgs e)
        {
            UpdateText();
        }

        /// <summary>
        /// Updates the text.
        /// </summary>
        private void UpdateText()
        {
            #if USE_FASTRENDERERS
            TextChanged -= OnTextChanged;
            #else
            Control.TextChanged -= OnTextChanged;
            #endif

            var icon = Plugin.Iconize.Iconize.FindIconForKey(Button.Text);
            if (icon != null)
            {
                #if USE_FASTRENDERERS
                Text = $"{icon.Character}";
                Typeface = Iconize.FindModuleOf(icon).ToTypeface(Context);
                #else
                Control.Text = $"{icon.Character}";
                Control.Typeface = Plugin.Iconize.Iconize.FindModuleOf(icon).ToTypeface(Context);
                #endif
            }

            #if USE_FASTRENDERERS
            TextChanged += OnTextChanged;
            #else
            Control.TextChanged += OnTextChanged;
            #endif
        }
    }
}
 