import { Component } from '@angular/core';

@Component({
  selector: 'app-welcome-banner',
  templateUrl: './welcome-banner.component.html',
  standalone: true,
  styleUrls: ['./welcome-banner.component.scss']
})
export class WelcomeBannerComponent {
  title = 'Welcome to BookMate';
  description = 'Share your books and discover new reads from your community';
}