import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';

import { HeaderComponent } from './components/header/header.component';
import { SidebarComponent } from './components/sidebar/sidebar.component';
import { WelcomeBannerComponent } from './components/welcome-banner/welcome-banner.component';
import { StatsCardComponent } from './components/stats-card/stats-card.component';
import { ReadingSectionComponent } from './components/reading-section/reading-section.component';
import { QuickStatsComponent } from './components/quick-stats/quick-stats.component';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
  imports: [
    CommonModule,
    HeaderComponent,
    SidebarComponent,
    WelcomeBannerComponent,
    StatsCardComponent,
    ReadingSectionComponent,
    QuickStatsComponent
  ]
})
export class AppComponent {
  title = 'BookMate';
  notifications = 2;
}
