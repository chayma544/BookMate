//we use a class when there are methods inside
//in our case we use interfaces considering it's more flexible (allow objects to be created dynamically without requiring a constructor)
export interface User {
    FirstName: string;
    LastName: string;
    Age?: number;
    //the age is optional
    favorite_genres?: string[];//add this to the user table
    joined_date?: string;
  }
  
  export interface UserBook {
    user_id: number;
    book_id: number;
    added_date: string;
    rating?: 0.5 | 1 | 1.5 | 2 | 2.5 | 3 | 3.5 | 4 | 4.5 | 5;
  }