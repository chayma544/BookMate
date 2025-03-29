import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Book } from '../models/book';  // Critical import

@Injectable({ providedIn: 'root' })
export class ApiService {
  private apiUrl = 'http://localhost:8000/api/books.php';

  constructor(private http: HttpClient) { }

  getBooks() {
    return this.http.get<Book[]>(this.apiUrl);
  }

  addBook(book: Book) {
    return this.http.post(this.apiUrl, book);
  }

  updateBook(book: Book) {
    return this.http.put(this.apiUrl, book);
  }

  deleteBook(id: number) {
    return this.http.delete(`${this.apiUrl}?id=${id}`);
  }
}