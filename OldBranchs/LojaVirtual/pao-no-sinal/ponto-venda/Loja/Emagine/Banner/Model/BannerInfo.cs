using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Banner.Model
{
    public class BannerInfo
    {
        [JsonProperty("id_banner")]
        public int Id { get; set; }
        [JsonProperty("slug")]
        public string Slug { get; set; }
        [JsonProperty("nome")]
        public string Nome { get; set; }
        [JsonProperty("largura")]
        public int Largura { get; set; }
        [JsonProperty("altura")]
        public int Altura { get; set; }
    }
}
