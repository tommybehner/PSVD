using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using Newtonsoft.Json;

namespace PsvdApi.Models
{
    [Table("updates")]
    public class Update
    {
        public Update()
        {
            update_time = DateTime.Now;
        }

        [Key]
        [DatabaseGenerated(DatabaseGeneratedOption.Identity)]
        public long update_id { get; set; }

        public DateTime update_time { get; set; }

        public int update_status { get; set; }

        [ForeignKey("Space")]
        public long update_space_id { get; set; }

        [JsonIgnore]
        public Space Space { get; set; }
    }
}
