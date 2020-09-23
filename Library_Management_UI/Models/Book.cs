using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Web;

namespace Library_Management_UI.Models
{
    public class Book
    {

        [Key]
        public int BookId { get; set; }
        [Required]
        public string Title{ get; set; }
        
        [Required]
        [Display(Name = "Serial Number")]
        public string SerialNo{ get; set; }

        public string Author{ get; set; }

        public string Publisher{ get; set; }

        

    }
}