export interface Request {
    request_id: number;
    requester_id: number;
    recipient_id: number;
    book_id: number;
    book_details: {  // Expanded book info for UI display
      title: string;  // "The Art of War"
      author: string; // "Sun Tzu"
      cover_image?: string; // URL to book cover
      recommended_by?: number; // 3 (from "Recommended by Z3 users")
    };
    type: 'BORROW' | 'TRADE' | 'GIFT';
    status: 'PENDING' | 'APPROVED' | 'REJECTED' | 'COMPLETED' | 'CANCELLED';
    dates: {
      requested: string;  // ISO date
      updated?: string;
    };
    participants: {
      requester_name: string;
      recipient_name: string;
    };
    message?: string;
  }