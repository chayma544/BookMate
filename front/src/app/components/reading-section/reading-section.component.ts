import { Component } from '@angular/core';
import { Book } from '../../models/book.model';

@Component({
  selector: 'app-reading-section',
  templateUrl: './reading-section.component.html',
  standalone: true,
  styleUrls: ['./reading-section.component.scss']
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
