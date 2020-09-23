using Library_Management_UI.Models;
using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace Library_Management_UI.DAL
{
    public class LibraryDBContext : DbContext
    {

        public LibraryDBContext(): base("dbConnKey")
        {

        }

        public DbSet<Book> Books { get; set; }

        public DbSet<Customer> Customers { get; set; }


    }
}