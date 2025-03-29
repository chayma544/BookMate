import { Component } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { Book } from '../../models/book'

@Component({
  selector: 'app-book-list',
  template: `
    <div *ngFor="let book of books">
      {{ book.title }} - {{ book.author_name }}
    </div>
  `
})


export class BookListComponent {
  books: Book[] = [];

  constructor(private api: ApiService) {}

  ngOnInit() {
    this.loadBooks();
  }

  loadBooks() {
    this.api.getBooks().subscribe(books => this.books = books);
  }

  addBook() {
    const newBook = { title: 'Sample', author_name: 'Author' };
    this.api.addBook(newBook).subscribe(() => this.loadBooks());
  }
}