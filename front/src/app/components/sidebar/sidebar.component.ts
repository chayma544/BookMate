import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  standalone: true,
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent {
  @Input() notifications: number = 0;
  
  navItems = [
    { label: 'Home', icon: 'home', active: true },
    { label: 'My Library', icon: 'library_books', active: false },
    { label: 'Discover Books', icon: 'explore', active: false },
    { label: 'My Books', icon: 'book', active: false },
    { label: 'Borrowed Books', icon: 'access_time', active: false },
    { label: 'Requests', icon: 'people', active: false },
    { label: 'Notifications', icon: 'notifications', active: false, badge: true }
  ];
}