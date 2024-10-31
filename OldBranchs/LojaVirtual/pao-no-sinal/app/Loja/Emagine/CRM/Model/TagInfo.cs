using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.CRM.Model
{
    public class TagInfo
    {
        public TagInfo()
        {
            this.Id = 0;
            this.Slug = string.Empty;
            this.Nome = string.Empty;
        }

        public TagInfo(int id, string slug, string nome) {
            this.Id = id;
            this.Slug = slug;
            this.Nome = nome;
        }

        public TagInfo(string nome) {
            this.Id = 0;
            this.Slug = nome;
            this.Nome = nome;
        }

        [JsonProperty("id_tag")]
        public int Id { get; set; }
        [JsonProperty("slug")]
        public string Slug { get; set; }
        [JsonProperty("nome")]
        public string Nome { get; set; }
    }
}
