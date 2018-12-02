using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using Newtonsoft.Json;

namespace PsvdApi.Models
{
    [Table("spaces")]
    public class Space
    {
        public Space()
        {
            space_time_added = DateTime.Now;
        }

        [Key]
        [DatabaseGenerated(DatabaseGeneratedOption.Identity)]
        public long space_id { get; set; }

        public int space_pi_id { get; set; }

        public int space_area_code { get; set; }

        public int space_status_id { get; set; }

        public DateTime space_time_added { get; set; }

        [ForeignKey("Lot")]
        public int space_lot_id { get; set; }

        [JsonIgnore]
        public Lot Lot { get; set; }
    }
}
