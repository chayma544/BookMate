<?php 
//general notes!!!!!!!!!!!!!!!!!! is it author_name or author-name underscores are more convetional
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../config/db.php';

// Handle preflight requests here's an example:

    //1.A frontend app (example.com) is trying to call an API hosted at (api.example.com).
    //2.The browser sends an OPTIONS request first to verify permissions.
    //3.This PHP code intercepts the request and allows it by returning a 200 status.
    //4.If the preflight request is successful, the browser proceeds with the actual request (e.g., POST, GET).


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


$method = $_SERVER['REQUEST_METHOD'];


try {
    switch ($method) {
        case 'GET':
            //methode 1
            // Get all books
            //$stmt = $pdo->query("SELECT * FROM Book");
            //$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //echo json_encode($books);
            //break;

            //method 2
            if (isset($_GET['id'])) {
                // Get single book by ID
                // $_GET stocke les parametres de l'URL dans un tableau associatif(the part after the ?)
                //$_GET = [
                  //  "search" => "harry",
                   // "genre" => "fantasy"
                //];                
                $stmt = $pdo->prepare("SELECT * FROM bookmate_livre WHERE book_id = ?");//this means pdo is already an object of the PDO class from db.php
                //This prepares the SQL query but does not execute it yet.
                //$object->method();  ==> Call a method of an object
                $stmt->execute([$_GET['id']]);//This does two things: 1️⃣ Binds the value ($_GET['id']) to the ? placeholder.
                                                                    //2️⃣ Executes the query securely, preventing SQL injection.
                $book = $stmt->fetch(PDO::FETCH_ASSOC);
                //$stmt is an object that holds the prepared SQL query.
                //The structure of the query never changes, only the values do.


                //fetch() gets one row at a time.


                //PDO::FETCH_ASSOC ensures the result is an associative array (["column_name" => value]) else returns false

                //The :: operator is used in four main cases:

                //1-echo MathHelper::add(5, 10);-->access methods
                //2-echo Config::SITE_NAME; -->SITE_NAME is a variable containing a value(output : the value of this variable)
                //3-return parent::makeSound()-->calling parent methods
                //4-$object->method(); VS ClassName::method();  ===>PDO is a classname(php data objects)

                if ($book) {
                    echo json_encode($book);
                    //json_encode() pour convertir le tableau associatif en format json
                } else {
                    http_response_code(404);
                    //On envoie un code d’erreur HTTP 404 (Not Found)
                    echo json_encode(['error' => 'Book not found']);
                    //Cela permet au client de comprendre que la requête a échoué.
                }
            } 
            // Search books with filters
            elseif (isset($_GET['search']) || isset($_GET['genre']) || isset($_GET['author'])) {


                //permet d'éviter les erreurs et de gérer le cas où un paramètre n'est pas fourni
                

                $search = isset($_GET['search']) ? "%{$_GET['search']}%" : "%";
                //commence ou se termine par le titre IF WE DON'T SPECIFY WE GET ALL THE TITLES
                $genre = isset($_GET['genre']) ? $_GET['genre'] : "%";
                //commence par le genre
                $author = isset($_GET['author']) ? "%{$_GET['author']}%" : "%";
                //se termine par le nom de l'auteur



                
                $stmt = $pdo->prepare("
                    SELECT * FROM bookmate_livre 
                    WHERE (title LIKE :search AND author_name LIKE :author
                    AND genre LIKE :genre
                    AND availability = 'available')
                ");
                
                //the prepared sql query is in $stmt


                //form of an associative array

                $stmt->execute([
                    ':search' => $search,
                    ':author' => $author,
                    ':genre' => $genre
                ]);
                // :search, :author, and :genre are placeholders in the SQL query.

                //$search, $author, and $genre are the actual values we pass into the query.

                //execute([...]) binds the values to the placeholders and runs the query.

                //this executes the sql query


                $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //gets all the matching rows from the database
                echo json_encode($books);
                //y5arajlekk les livres trouvés en format json
            }
            // Get all available books
            //if the user didn't specify anything
            else {
                $stmt = $pdo->query("SELECT * FROM livre WHERE availability = 'available'");
                $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($books);
            }
            break;


            case 'POST':
                // Parse the incoming JSON data
                $requestData = json_decode(file_get_contents("php://input"), true);
                
                // Validate required fields for adding a book
                if (empty($requestData['title']) || empty($requestData['author_name'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Title and author name are required']);
                    break;
                }
            
                // Insert new book into database
                $stmt = $pdo->prepare("
                    INSERT INTO bookmate_livre 
                    (title, author_name, language, genre, release_date, status, dateAjout, availability, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)
                ");
            
                $stmt->execute([
                    $requestData['title'],
                    $requestData['author_name'],
                    $requestData['language'] ?? 'Unknown',
                    $requestData['genre'] ?? 'Other',
                    $requestData['release_date'] ?? null,
                    $requestData['status'] ?? 'good',
                    $requestData['availability'] ?? 'available',
                    $requestData['user_id'] ?? null 
                ]);
            
                // Return success response
                http_response_code(201);
                echo json_encode([
                    'id' => $pdo->lastInsertId(),
                    'message' => 'Book added successfully'
                ]);
                break;
                

                case 'PUT':
                    // Verify book ID and required fields
                    if (empty($requestData['book_id'])) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Book ID is required']);
                        break; // exits here only if book-id is completely missing ya3ni ken kif book-id ma 3andhech value
                    }
                    
                    // Update book information : hethi requette préparée
                    
                    $stmt = $pdo->prepare("
                        UPDATE bookmate_livre 
                        SET title = ?, author_name = ?, language = ?, genre = ?, 
                            release_date = ?, status = ?, availability = ?
                        WHERE book_id = ?
                    ");
                    
                    // executes the query

                    $stmt->execute([
                        $requestData['title'],
                        $requestData['author_name'],
                        $requestData['language'],
                        $requestData['genre'],
                        $requestData['release_date'],
                        $requestData['status'],
                        $requestData['availability'],
                        $requestData['book_id']
                    ]);
                    

                    // checks if any rows were affected 

                    if ($stmt->rowCount() > 0) {
                        // success : at least 1 row is updated
                        echo json_encode(['message' => 'Book updated successfully']);
                    } else {
                        // failure : 0 rows updated ( book-id unmached or no changes needed )
                        http_response_code(404);
                        echo json_encode(['error' => 'Book not found or no changes made']);
                    }
                    break;
        
                case 'DELETE':
                    // Verify book ID
                    if (empty($_GET['id'])) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Book ID is required']);
                        break;
                    }
                    
                    // Soft delete (recommended) or hard delete
                    $bookId = $_GET['id'];
                    
                    // Option 1: Soft delete (update availability)
                    $stmt = $pdo->prepare("
                        UPDATE bookmate_livre 
                        SET availability = 'deleted' 
                        WHERE book_id = ?
                    ");
                    $stmt->execute([$bookId]);
                    
                    // Option 2: Hard delete
                    // $stmt = $pdo->prepare("DELETE FROM bookmate_livre WHERE book_id = ?");
                    // $stmt->execute([$bookId]);
                    
                    if ($stmt->rowCount() > 0) {
                        echo json_encode(['message' => 'Book removed successfully']);
                    } else {
                        http_response_code(404);
                        echo json_encode(['error' => 'Book not found']);
                    }
                    break;
        
                default:
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ]);
        }
        ?>