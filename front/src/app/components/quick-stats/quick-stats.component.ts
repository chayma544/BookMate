import { Component } from '@angular/core';

@Component({
  selector: 'app-quick-stats',
  templateUrl: './quick-stats.component.html',
  styleUrls: ['./quick-stats.component.scss']
})
export class QuickStatsComponent {
  availableBooks = 5;
  lendingScore = 4.8;
}