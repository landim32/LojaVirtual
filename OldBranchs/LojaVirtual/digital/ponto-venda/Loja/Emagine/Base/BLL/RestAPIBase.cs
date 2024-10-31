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
        private string INTERNET_ERRO = "Erro na conexão. Verifique sua Internet.";

        protected async Task<T> queryGet<T>(string url)
        {
            using (var client = new HttpClient()) {
                try {
                    HttpResponseMessage response = await client.GetAsync(url);
                    var str = await response.Content.ReadAsStringAsync();
                    if (!response.IsSuccessStatusCode) {
                        throw new Exception(str);
                    }
                    var retorno = JsonConvert.DeserializeObject<T>(str);
                    return retorno;
                }
                catch (HttpRequestException erro)
                {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (TaskCanceledException erro) {
                    throw new Exception(INTERNET_ERRO, erro);
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
                catch (HttpRequestException erro)
                {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (TaskCanceledException erro)
                {
                    throw new Exception(INTERNET_ERRO, erro);
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
                try
                {
                    var strJson = JsonConvert.SerializeObject(args.Length == 1 ? args[0] : args);
                    var content = new StringContent(strJson);
                    HttpResponseMessage response = await client.PutAsync(url, content);
                    var str = await response.Content.ReadAsStringAsync();
                    if (!response.IsSuccessStatusCode) {
                        throw new Exception(str);
                    }
                    var retorno = JsonConvert.DeserializeObject<T>(str);
                    return retorno;
                }
                catch (HttpRequestException erro)
                {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (TaskCanceledException erro) {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (Exception erro)
                {
                    throw erro;
                }
            }
        }
        protected async Task<string> queryPut(string url, object[] args)
        {
            using (var client = new HttpClient()) {
                try {
                    var strJson = JsonConvert.SerializeObject(args.Length == 1 ? args[0] : args);
                    var content = new StringContent(strJson);
                    HttpResponseMessage response = await client.PutAsync(url, content);
                    var str = await response.Content.ReadAsStringAsync();
                    if (!response.IsSuccessStatusCode) {
                        throw new Exception(str);
                    }
                    return str;
                }
                catch (HttpRequestException erro)
                {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (TaskCanceledException erro)
                {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (Exception erro)
                {
                    throw erro;
                }
            }
        }

        protected async Task execPut(string url, params object[] args)
        {
            using (var client = new HttpClient()) {
                try {
                    var strJson = JsonConvert.SerializeObject(args.Length == 1 ? args[0] : args);
                    var content = new StringContent(strJson);
                    HttpResponseMessage response = await client.PutAsync(url, content);
                    var str = await response.Content.ReadAsStringAsync();
                    if (!response.IsSuccessStatusCode) {
                        throw new Exception(str);
                    }
                    return;
                }
                catch (HttpRequestException erro)
                {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (TaskCanceledException erro) {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (Exception erro) {
                    throw erro;
                }
            }
        }

        protected async Task execGet(string url)
        {
            using (var client = new HttpClient()) {
                try {
                    HttpResponseMessage response = await client.GetAsync(url);
                    var str = await response.Content.ReadAsStringAsync();
                    if (!response.IsSuccessStatusCode) {
                        throw new Exception(str);
                    }
                    return;
                }
                catch (HttpRequestException erro)
                {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (TaskCanceledException erro) {
                    throw new Exception(INTERNET_ERRO, erro);
                }
                catch (Exception erro) {
                    throw erro;
                }
            }
        }
    }
}
