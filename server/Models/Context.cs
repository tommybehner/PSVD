using Microsoft.EntityFrameworkCore;

namespace PsvdApi.Models
{
    public class Context : DbContext
    {
        public Context(DbContextOptions options) : base(options) { }

        public DbSet<Lot> Lots { get; set; }

        public DbSet<Space> Spaces { get; set; }

        public DbSet<Update> Updates { get; set; }
    }
}
