using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Base.Utils
{
    public static class StringUtils
    {
        private static IEnumerable<char> ReadNext(string str, int currentPosition, int count)
        {
            for (var i = 0; i < count; i++)
            {
                if (currentPosition + i >= str.Length)
                {
                    yield break;
                }
                else
                {
                    yield return str[currentPosition + i];
                }
            }
        }

        public static IEnumerable<string> quotedSplit(string s, string delim)
        {
            const char quote = '\"';

            var sb = new StringBuilder(s.Length);
            var counter = 0;
            while (counter < s.Length)
            {
                // if starts with delmiter if so read ahead to see if matches
                if (delim[0] == s[counter] &&
                    delim.SequenceEqual(ReadNext(s, counter, delim.Length)))
                {
                    yield return sb.ToString();
                    sb.Clear();
                    counter = counter + delim.Length; // Move the counter past the delimiter 
                }
                // if we hit a quote read until we hit another quote or end of string
                else if (s[counter] == quote)
                {
                    sb.Append(s[counter++]);
                    while (counter < s.Length && s[counter] != quote)
                    {
                        sb.Append(s[counter++]);
                    }
                    // if not end of string then we hit a quote add the quote
                    if (counter < s.Length)
                    {
                        sb.Append(s[counter++]);
                    }
                }
                else
                {
                    sb.Append(s[counter++]);
                }
            }

            if (sb.Length > 0)
            {
                yield return sb.ToString();
            }
        }

        public static double extrairNumero(string texto) {
            string str = string.Empty;
            foreach (char c in texto.ToCharArray())
            {
                if (char.IsNumber(c) || c == ',' || c == '.') {
                    str += c;
                }
            }
            if (!string.IsNullOrEmpty(str)) {
                if (str.IndexOf('.') >= 0 && str.IndexOf(',') >= 0) {
                    str = str.Replace(".", "");
                }
                else if (str.IndexOf('.') >= 0) {
                    str = str.Replace(".", ",");
                }
                double valor = 0;
                if (double.TryParse(str, out valor)) {
                    return valor;
                }
            }
            return 0;
        }
    }
}
