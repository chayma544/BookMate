import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';

@Component({
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="landing-page">
      <button (click)="fetchBooks()">Load Books</button>
      <ul>
        <li *ngFor="let book of books">{{ book.title }}</li>
      </ul>
    </div>
  `
})
export class AppComponent {
  books: any;

  constructor(private http: HttpClient) {}

  fetchBooks() {
    this.http.get('http://localhost:8000/api/books.php').subscribe((data) => {
      this.books = data;
    });
  }
}