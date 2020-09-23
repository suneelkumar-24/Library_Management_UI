using Library_Management_UI.Models;
using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace Library_Management_UI.DAL
{
    public class InitilizeData : DropCreateDatabaseIfModelChanges<LibraryDBContext>
    {

        protected override void Seed(LibraryDBContext context)
        {
            List<Book> books = new List<Book>();

            foreach (Book book in books)
            {
                context.Books.Add(book);
            }

            context.SaveChanges();


            List<Customer> customers = new List<Customer>();

            foreach (Customer customer in customers)
            {
                context.Customers.Add(customer);
            }

            context.SaveChanges();

        }
    }
}