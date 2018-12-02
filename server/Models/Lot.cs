using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace PsvdApi.Models
{
    [Table("lots")]
    public class Lot
    {
        public Lot()
        {
            lot_updated = DateTime.Now;
        }

        [Key]
        [DatabaseGenerated(DatabaseGeneratedOption.Identity)]
        public int lot_id { get; set; }

        public string lot_name { get; set; }

        public string lod_desc { get; set; }

        public DateTime lot_updated { get; set; }

        public string lot_location { get; set; }

        [InverseProperty("Lot")]
        public ICollection<Space> Spaces { get; set; }
    }
}
