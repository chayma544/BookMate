<?php
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../config/db.php';

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['admin_id'])) {
                // Get administrator by admin_id
                $stmt = $pdo->prepare("SELECT * FROM administrateur WHERE admin_id = ?");
                $stmt->execute([$_GET['admin_id']]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($admin) {
                    echo json_encode($admin);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Administrator not found']);
                }
            } else {
                // Get all administrators (admin-only functionality)
                $stmt = $pdo->query("SELECT admin_id, FirstName, LastName FROM administrateur");
                $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($admins);
            }
            break;
        
        case 'POST':
            // For creating an administrator profile
            $requestData = json_decode(file_get_contents("php://input"), true);
            
            // Validate required fields
            if (empty($requestData['FirstName']) || empty($requestData['LastName']) || empty($requestData['age'])) {
                http_response_code(400);
                echo json_encode(['error' => 'FirstName, LastName, and age are required']);
                break;
            }
            
            // Check if admin_id already exists
            $adminCheck = $pdo->prepare("SELECT 1 FROM administrateur WHERE admin_id = ?");
            $adminCheck->execute([$requestData['admin_id']]);
            
            if ($adminCheck->rowCount() > 0) {
                http_response_code(409);
                echo json_encode(['error' => 'Administrator with this admin_id already exists']);
                break;
            }

            // Insert new administrator
            $stmt = $pdo->prepare("
                INSERT INTO administrateur (FirstName, LastName, age, admin_id) 
                VALUES (?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $requestData['FirstName'],
                $requestData['LastName'],
                $requestData['age'],
                $requestData['admin_id']
            ]);
            
            // Return success response
            http_response_code(201);
            echo json_encode([
                'admin_id' => $requestData['admin_id'],
                'message' => 'Administrator created successfully'
            ]);
            break;
        
        case 'PUT':
            $requestData = json_decode(file_get_contents("php://input"), true);
            
            // Verify admin_id exists
            if (empty($requestData['admin_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'admin_id is required']);
                break;
            }
            
            // Get current administrator data
            $checkAdmin = $pdo->prepare("SELECT * FROM administrateur WHERE admin_id = ?");
            $checkAdmin->execute([$requestData['admin_id']]);
            $currentData = $checkAdmin->fetch(PDO::FETCH_ASSOC);
            
            if (!$currentData) {
                http_response_code(404);
                echo json_encode(['error' => 'Administrator not found']);
                break;
            }
            
            // Prepare update query
            $updateFields = [];
            $params = [];
            
            // Update FirstName if provided
            if (isset($requestData['FirstName'])) {
                $updateFields[] = "FirstName = ?";
                $params[] = $requestData['FirstName'];
            }
            
            // Update LastName if provided
            if (isset($requestData['LastName'])) {
                $updateFields[] = "LastName = ?";
                $params[] = $requestData['LastName'];
            }

            // Update age if provided
            if (isset($requestData['age'])) {
                $updateFields[] = "age = ?";
                $params[] = $requestData['age'];
            }
            
            // If no valid fields provided
            if (empty($updateFields)) {
                http_response_code(400);
                echo json_encode(['error' => 'No valid fields provided for update']);
                break;
            }
            
            // Add admin_id to params
            $params[] = $requestData['admin_id'];
            
            // Build and execute dynamic query
            $query = "UPDATE administrateur SET " . implode(', ', $updateFields) . " WHERE admin_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Administrator updated successfully']);
            } else {
                http_response_code(200);
                echo json_encode(['message' => 'No changes made']);
            }
            break;
        
        case 'DELETE':
            if (empty($_GET['admin_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'admin_id is required']);
                break;
            }
            
            $adminId = $_GET['admin_id'];
            
            $stmt = $pdo->prepare("DELETE FROM administrateur WHERE admin_id = ?");
            $stmt->execute([$adminId]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Administrator deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Administrator not found']);
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
