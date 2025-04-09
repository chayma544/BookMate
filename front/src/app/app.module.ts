//an angular module (@NgModule) is a container that groups related compnents, services w kol chay andou ale9a bbaadhou
//every angular app must have at least one module called root module 
//so basicallt app.module.ts defines el module(s) hedhom
import { NgModule } from '@angular/core'; // Defines an Angular module
import { BrowserModule } from '@angular/platform-browser'; // imports lhajet l fundemental to build the angular app that works in the browser
import { AppRoutingModule } from './app-routing.module'; // Handles routing (navigation) between pages
import { HttpClientModule } from '@angular/common/http'; // Allows making HTTP requests (e.g., fetching books from an API)
// Import the root component and other components used in the app
import { AppComponent } from './app.component'; 
import { BookListComponent } from './components/book-list/book-list.component';
import { NavbarComponent } from './components/navbar/navbar.component'; 

@NgModule({
  declarations: [
    // Declare all components that belong to this module
    AppComponent, // Root component of the app
    BookListComponent, // Component responsible for displaying books
    NavbarComponent // Component for the app's navigation bar
  ],
  imports: [
    // Import other Angular modules needed for the app
    BrowserModule, // Required for all Angular applications running in the browser
    AppRoutingModule, // Manages the routing configuration (handles navigation)
    HttpClientModule // Enables making HTTP requests (fetching data from an API)
  ],
  //a service is a TypeScript class with some logic that you want to reuse(kima fetching data from an api ..)
  //The providers array in an Angular module tells Angular which services should be available for dependency injection across the application
  //yaani when we want to create a new component with a constructor angular provides the necessary services
  providers: [
    // Services (e.g., BookService) can be registered here, but Angular provides them automatically
  ],
  bootstrap: [AppComponent] // Specifies the root component that Angular should load first
})
export class AppModule { } // This exports the module so it can be used in the application