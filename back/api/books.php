<?php
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get all books
        $stmt = $pdo->query("SELECT * FROM Book");
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($books);
        break;

    case 'POST':
        // Add new book
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO Book (title, author_name, status) VALUES (?, ?, ?)");
        $stmt->execute([$data['title'], $data['author_name'], $data['status'] ?? 'available']);
        echo json_encode(['id' => $pdo->lastInsertId()]);
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

try {
    // Your CRUD code...
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>