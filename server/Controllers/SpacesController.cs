using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using PsvdApi.Models;

namespace PsvdApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class SpacesController : ControllerBase
    {
        private readonly Context _context;

        public SpacesController(Context context)
        {
            _context = context;
        }

        // GET: api/Spaces
        [HttpGet]
        public IEnumerable<Space> GetSpaces()
        {
            return _context.Spaces.OrderBy(s => s.space_pi_id).ThenBy(s => s.space_area_code);
        }

        // GET: api/Spaces/{id}
        [HttpGet("{id}")]
        public async Task<IActionResult> GetSpace([FromRoute] long id)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            var space = await _context.Spaces.FindAsync(id);

            if (space == null)
            {
                return NotFound();
            }

            return Ok(space);
        }

        // PUT: api/Spaces/{id}
        [HttpPut("{id}")]
        public async Task<IActionResult> PutSpace([FromRoute] long id, [FromBody] Space space)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (id != space.space_id)
            {
                return BadRequest();
            }

            _context.Entry(space).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!SpaceExists(id))
                {
                    return NotFound();
                }

                throw;
            }

            return NoContent();
        }

        // POST: api/Spaces
        [HttpPost]
        public async Task<IActionResult> PostSpace([FromBody] Space space)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            _context.Spaces.Add(space);
            await _context.SaveChangesAsync();

            return CreatedAtAction("GetSpace", new { id = space.space_id }, space);
        }

        // DELETE: api/Spaces/{id}
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteSpace([FromRoute] long id)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            var space = await _context.Spaces.FindAsync(id);
            if (space == null)
            {
                return NotFound();
            }

            _context.Spaces.Remove(space);
            await _context.SaveChangesAsync();

            return Ok(space);
        }

        private bool SpaceExists(long id)
        {
            return _context.Spaces.Any(s => s.space_id == id);
        }
    }
}
