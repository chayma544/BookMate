import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';


import { AppComponent } from './app.component';
import { HeaderComponent } from './components/header/header.component';
import { SidebarComponent } from './components/sidebar/sidebar.component';
import { WelcomeBannerComponent } from './components/welcome-banner/welcome-banner.component';
import { StatsCardComponent } from './components/stats-card/stats-card.component';
import { ReadingSectionComponent } from './components/reading-section/reading-section.component';
import { BookItemComponent } from './components/book-item/book-item.component';
import { QuickStatsComponent } from './components/quick-stats/quick-stats.component';



@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    SidebarComponent,
    WelcomeBannerComponent,
    StatsCardComponent,
    ReadingSectionComponent,
    BookItemComponent,
    QuickStatsComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    RouterModule.forRoot([
        { path: '', component: WelcomeBannerComponent },
        { path: 'books', component: ReadingSectionComponent }
    ]),
    ReadingSectionComponent,
    HeaderComponent,
    SidebarComponent,
    StatsCardComponent,
    BookItemComponent,
    QuickStatsComponent,
    WelcomeBannerComponent
],
  
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
