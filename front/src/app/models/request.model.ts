export interface Request {
  requester_id: number;
  book_id: number;
  book_title?: string; // Added for display purposes
  book_author?: string; // Added for display purposes
  type: 'BORROW' | 'TRADE' | 'GIFT';
  status: 'PENDING' | 'APPROVED' | 'REJECTED' | 'COMPLETED';
  date_request: string;
  date_processed?: string;
  processed_by?: number;
  //Tracks who handled the request - Stores the user ID of the admin/moderator who approved, rejected, or completed the request
  message?: string;
}