//this service will handle everything related  to books (CRUD functions)

import { Injectable } from '@angular/core';// Marks this class as a service that can be injected into other components or services
import { HttpClient } from '@angular/common/http';// Allows us to make HTTP requests (like GET, POST, PUT, DELETE)
import { Observable } from 'rxjs'; // Observable helps us deal with asynchronous data (like data from an API)
import { Book } from './models/book.model.ts';// Importing the Book model so we know what shape our data should have

@Injectable({
   // This means the service is available throughout the whole application
  providedIn: 'root' // makes it available app-wide
})
//This service contains functions to interact with the backend API that handles book data.
export class BookService {
  private apiUrl = 'http://localhost/BookMate/back/api/books.php'; //This property stores the URL of the API endpoint where the books are managed. It's the base URL where all HTTP requests (GET, POST, PUT, DELETE) will be sent to.
  constructor(private http: HttpClient) { }// Inject HttpClient into the constructor so we can use it for HTTP requests

  //CRUD functions with "Observable<Book>" as the return type

  // Get all books
  getBooks(): Observable<Book[]> { 
    return this.http.get<Book[]>(this.apiUrl); // sends a GET request to the apiUrl and expects an array of Book objects as the response.
  }

  // Get one book by ID
  getBookById(id: string): Observable<Book> {
    return this.http.get<Book>(`${this.apiUrl}/${id}`); //It sends a GET request to apiUrl/{id} where {id} is replaced with the actual book ID. This retrieves one book from the backend.
  } 

  // Add a new book
  addBook(book: Book): Observable<Book> {
    return this.http.post<Book>(this.apiUrl, book);//It sends a POST request to the apiUrl with the book data (which is the new book to be added) as the body of the request.
  }

  // Update an existing book
  updateBook(id: string, book: Book): Observable<Book> {
    return this.http.put<Book>(`${this.apiUrl}/${id}`, book); //It sends a PUT request to apiUrl/{id} with the updated book object as the request body.
  }

  // Delete a book
  deleteBook(id: string): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`); //sends a DELETE request to apiUrl/{id} to delete the book with the specified ID.
  }
}
