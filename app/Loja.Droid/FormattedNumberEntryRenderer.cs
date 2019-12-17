using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;
using Xamarin.Forms.Platform.Android;
using Xamarin.Forms;
using Emagine.Base.Controls;
using Android.Text;
using System.ComponentModel;
using Loja.Droid;
using Xfx;

[assembly: ExportRenderer(typeof(FormattedNumberEntry), typeof(FormattedNumberEntryRenderer))]

namespace Loja.Droid
{
    public class FormattedNumberEntryRenderer : EntryRenderer
    {
        protected override void OnElementChanged(ElementChangedEventArgs<Entry> e)
        {
            base.OnElementChanged(e);

            if (e.OldElement != null)
            {
                Control.AfterTextChanged -= Control_AfterTextChanged;
            }
            if (e.NewElement != null)
            {
                Control.AfterTextChanged += Control_AfterTextChanged;
            }
        }

        protected override void OnElementPropertyChanged(object sender, PropertyChangedEventArgs e)
        {
            if (e.PropertyName.Equals(nameof(FormattedNumberEntry.Value)))
            {
                var element = ((FormattedNumberEntry)Element);
                // 5. Format number, and place the formatted text in newText
                //var newText = $"{element.Value:#,##0}";
                var newText = element.Value.ToString("N2");

                // 6. Set the Text property of our control to newText
                Control.Text = newText;

            }
            else
            {
                base.OnElementPropertyChanged(sender, e);
            }

        }

        void Control_AfterTextChanged(object sender, AfterTextChangedEventArgs e)
        {
            var element = ((FormattedNumberEntry)Element);

            // 1. Stop listening for changes on our control Text property
            if (!element.ShouldReactToTextChanges) return;
            element.ShouldReactToTextChanges = false;

            // 2. Get the current cursor position
            var cursorPosition = Control.SelectionStart;

            // 3. Take the control’s text, lets name it oldText
            var oldText = Control.Text;

            // 4. Parse oldText into a number, lets name it number
            var number = FormattedNumberEntry.DumbParse(oldText);
            element.Value = number;

            // 5. Format number, and place the formatted text in newText
            //var newText = $"{number:#,##0}";
            var newText = number.ToString("N2");

            // 6. Set the Text property of our control to newText
            Control.Text = newText;

            // 7. Calculate the new cursor position
            var change = oldText.Length - newText.Length;

            // 8. Set the new cursor position
            Control.SetSelection(cursorPosition - change);

            // 9. Start listening for changes on our control’s Text property
            element.ShouldReactToTextChanges = true;
        }
    }
}