
export interface Book {  // Must include 'export'
    id?: number;
    title: string;
    author_name: string;
    status?: 'available' | 'borrowed';
}