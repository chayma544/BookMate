import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-stats-card',
  templateUrl: './stats-card.component.html',
  styleUrls: ['./stats-card.component.scss']
})
export class StatsCardComponent {
  @Input() icon: string = '';
  @Input() label: string = '';
  @Input() value: number = 0;
  @Input() change?: number;
}