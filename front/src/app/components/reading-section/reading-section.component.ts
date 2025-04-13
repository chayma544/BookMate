import { Component } from '@angular/core';
import { Book } from '../../models/Book.model';
import { BookItemComponent } from '../book-item/book-item.component'; 
import { NgFor } from '@angular/common';
@Component({
  selector: 'app-reading-section',
  templateUrl: './reading-section.component.html',
  standalone: true,
  imports: [BookItemComponent,NgFor],
  styleUrls: ['./reading-section.component.css']
})
export class ReadingSectionComponent {
  books: Book[] = [
    {
      title: 'The Silent Patient',
      author: 'Alex Michaelides',
      progress: 67,
      coverUrl: 'assets/book-covers/silent-patient.jpg'
    },
    {
      title: 'Atomic Habits',
      author: 'James Clear',
      progress: 0,
      coverUrl: 'assets/book-covers/atomic-habits.jpg'
    }
  ];
}
