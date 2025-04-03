<?php
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
                    WHERE (title LIKE :search OR author_name LIKE :author)
                    AND genre LIKE :genre
                    AND availability = 'available'
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


        case 'POST': // what the hell ???!???!???!???!???!???!???!???!???!???!???!???!
            case 'POST':
                // Read JSON data from the request body
                $data = json_decode(file_get_contents("php://input"), true);
                
                // Ensure the action parameter is set in the request
                if (!isset($data['action'])) {
                    echo json_encode(['error' => 'Missing action parameter']);
                    exit;
                }
            
                // Connect to MySQL database
                //its alreadyy connected!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=bookmate", "root", "");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
                    exit;
                }
            
                switch ($data['action']) {
                    case 'add_book': // ✅ Add a new book to the database
                        // Ensure all required fields are provided
                        if (!isset($data['title'], $data['author_name'], $data['language'], $data['genre'], $data['release_date'], $data['user_id'])) {
                            echo json_encode(['error' => 'Missing required fields']);
                            exit;
                        }
                        
                        // Prepare and execute the SQL INSERT query
                        $stmt = $pdo->prepare("INSERT INTO `livre` (`title`, `author-name`, `language`, `genre`, `release-date`, `status`, `dateAjout`, `availability`, `user-id`) VALUES (?, ?, ?, ?, ?, 'available', NOW(), 'yes', ?)");
                        $stmt->execute([$data['title'], $data['author_name'], $data['language'], $data['genre'], $data['release_date'], $data['user_id']]);
                        
                        // Return success response with the newly inserted book ID
                        echo json_encode(['message' => 'Book added successfully', 'id' => $pdo->lastInsertId()]);
                        break;
            
                    case 'update_book': // ✅ Update an existing book's details
                        // Ensure all required fields are provided
                        if (!isset($data['book_id'], $data['title'], $data['author_name'], $data['language'], $data['genre'], $data['release_date'], $data['status'], $data['availability'])) {
                            echo json_encode(['error' => 'Missing required fields']);
                            exit;
                        }
                        
                        // Prepare and execute the SQL UPDATE query
                        $stmt = $pdo->prepare("UPDATE `livre` SET `title` = ?, `author-name` = ?, `language` = ?, `genre` = ?, `release-date` = ?, `status` = ?, `availability` = ? WHERE `book-id` = ?");
                        $stmt->execute([$data['title'], $data['author_name'], $data['language'], $data['genre'], $data['release_date'], $data['status'], $data['availability'], $data['book_id']]);
                        
                        // Return success response
                        echo json_encode(['message' => 'Book updated successfully']);
                        break;
            
                    case 'delete_book': // ✅ Delete a book from the database
                        // Ensure the book ID is provided
                        if (!isset($data['book_id'])) {
                            echo json_encode(['error' => 'Missing book ID']);
                            exit;
                        }
                        
                        // Prepare and execute the SQL DELETE query
                        $stmt = $pdo->prepare("DELETE FROM `livre` WHERE `book-id` = ?");
                        $stmt->execute([$data['book_id']]);
                        
                        // Return success response
                        echo json_encode(['message' => 'Book deleted successfully']);
                        break;
            
                    case 'create_request': // ✅ Create a swap/borrow request
                        // Ensure all required fields are provided
                        if (!isset($data['requester_id'], $data['book_id'], $data['type'])) {
                            echo json_encode(['error' => 'Missing required fields']);
                            exit;
                        }
                        
                        // Prepare and execute the SQL INSERT query for requests
                        $stmt = $pdo->prepare("INSERT INTO `requests` (`requester_id`, `book_id`, `type`, `status`) VALUES (?, ?, ?, 'PENDING')");
                        $stmt->execute([$data['requester_id'], $data['book_id'], strtoupper($data['type'])]);
                        
                        // Return success response with the newly created request ID
                        echo json_encode(['message' => 'Request created successfully', 'id' => $pdo->lastInsertId()]);
                        break;
            
                    default:
                        // Handle invalid actions
                        echo json_encode(['error' => 'Invalid action']);
                        break;
                }
                break;
            

        case 'PUT':
            // Update book
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $pdo->prepare("UPDATE Book SET title=?, author_name=?, status=? WHERE id=?");
            $stmt->execute([$data['title'], $data['author_name'], $data['status'], $data['id']]);
            echo json_encode(['message' => 'Book updated']);
            break;

        case 'DELETE':
            // Delete book
            $id = $_GET['id'];
            $stmt = $pdo->prepare("DELETE FROM Book WHERE id=?");
            $stmt->execute([$id]);
            echo json_encode(['message' => 'Book deleted']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
 
    }

 
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    ?>