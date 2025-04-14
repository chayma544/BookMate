import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  standalone: true,
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent {
  @Input() notifications: number = 0;
}