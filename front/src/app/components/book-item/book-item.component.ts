import { Component, Input } from '@angular/core';
import { Book } from '../../models/Book.model';

@Component({
  selector: 'app-book-item',
  templateUrl: './book-item.component.html',
  standalone: true,
  styleUrls: ['./book-item.component.css']
})
export class BookItemComponent {
  @Input() book!: Book;
}