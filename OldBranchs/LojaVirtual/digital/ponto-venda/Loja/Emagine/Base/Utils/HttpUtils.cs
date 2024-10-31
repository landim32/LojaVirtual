using System;
using System.Collections.Generic;
using System.Linq;

namespace Emagine.Base.Utils
{
    public static class HttpUtils
    {
        public static string montaParametrosGet(List<KeyValuePair<string, string>> parans)
        {
            var ret = "";
            foreach(var param in parans)
            {
                ret += param.Key + "=" + Uri.EscapeDataString(param.Value) + "&";
            }
            if(ret != "")
            {
                ret = ret.Substring(0, ret.Length - 1);
            }
            return ret;
        }
        public static string montaLinkGet(string url, List<KeyValuePair<string, string>> parans)
        {
            return url + "?" + montaParametrosGet(parans);
        }
    }
}