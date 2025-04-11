export interface Book {
    id?: string;
    //Quand tu crées un nouveau livre, tu n’as pas encore l'ID (il est généré par la base de données ou l'API)
    //Mais quand tu reçois un livre depuis l'API, il aura un ID.
    title: string;
    author: string;
    coverUrl: string;
    progress?: number;
    isShared?: boolean;
    isBorrowed?: boolean;
  }