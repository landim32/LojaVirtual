using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Base.BLL
{
    public class RestAPIBase
    {
        //protected string API_URL = "http://emagine.com.br/emagine-frete";

        public RestAPIBase()
        {
            //API_URL = GlobalAplicacao.getURLAplicacao();
        }

        protected async Task<T> queryGet<T>(string url)
        {
            using (var client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync(url);
                //response.EnsureSuccessStatusCode();
                var str = await response.Content.ReadAsStringAsync();
                if (!response.IsSuccessStatusCode)
                {
                    throw new Exception(str);
                }
                try
                {
                    var retorno = JsonConvert.DeserializeObject<T>(str);
                    return retorno;
                }
                catch (Exception erro)
                {
                    throw erro;
                }
            }
        }

        protected async Task<string> queryGet(string url)
        {
            using (var client = new HttpClient())
            {
                try
                {
                    HttpResponseMessage response = await client.GetAsync(url);
                    response.EnsureSuccessStatusCode();
                    var str = await response.Content.ReadAsStringAsync();
                    if (!response.IsSuccessStatusCode)
                    {
                        throw new Exception(str);
                    }
                    return str;
                }
                catch (Exception erro)
                {
                    throw erro;
                }
            }
        }

        protected async Task<T> queryPut<T>(string url, object[] args)
        {
            using (var client = new HttpClient())
            {
                var strJson = JsonConvert.SerializeObject(args.Length == 1 ? args[0] : args);
                var content = new StringContent(strJson);
                HttpResponseMessage response = await client.PutAsync(url, content);
                var str = await response.Content.ReadAsStringAsync();
                if (!response.IsSuccessStatusCode)
                {
                    throw new Exception(str);
                }
                try
                {
                    var retorno = JsonConvert.DeserializeObject<T>(str);
                    return retorno;
                }
                catch (Exception erro)
                {
                    throw erro;
                }
            }
        }
        protected async Task<string> queryPut(string url, object[] args)
        {
            using (var client = new HttpClient())
            {
                var strJson = JsonConvert.SerializeObject(args.Length == 1 ? args[0] : args);
                var content = new StringContent(strJson);
                HttpResponseMessage response = await client.PutAsync(url, content);
                var str = await response.Content.ReadAsStringAsync();
                if (!response.IsSuccessStatusCode)
                {
                    throw new Exception(str);
                }
                return str;
            }
        }

        protected async Task execPut(string url, params object[] args)
        {
            using (var client = new HttpClient())
            {
                var strJson = JsonConvert.SerializeObject(args.Length == 1 ? args[0] : args);
                var content = new StringContent(strJson);
                HttpResponseMessage response = await client.PutAsync(url, content);
                var str = await response.Content.ReadAsStringAsync();
                if (!response.IsSuccessStatusCode)
                {
                    throw new Exception(str);
                }
                /*
                if (!string.IsNullOrEmpty(str))
                {
                    var xml = JsonConvert.DeserializeXNode(str);
                    if (xml != null)
                    {
                        var element = xml.Element("error");
                        if (element != null)
                            throw new Exception(element.Value);
                    }
                }
                */
                return;
            }
        }

        protected async Task execGet(string url)
        {
            using (var client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync(url);
                var str = await response.Content.ReadAsStringAsync();
                if (!response.IsSuccessStatusCode)
                {
                    throw new Exception(str);
                }
                /*
                if (!string.IsNullOrEmpty(str))
                {
                    var xml = JsonConvert.DeserializeXNode(str);
                    if (xml != null)
                    {
                        var element = xml.Element("error");
                        if (element != null)
                        {
                            throw new Exception(element.Value);
                        }
                    }
                }
                */
                return;
            }
        }
    }
}
