<?php 
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Get user by ID
                $stmt = $pdo->prepare("SELECT * FROM user WHERE `user_id` = ?");
                $stmt->execute([$_GET['id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    echo json_encode($user);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'User not found']);
                }
            } else {
                // Get all users
                $stmt = $pdo->query("SELECT * FROM user");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($users);
            }
            break;

        case 'POST':
            // Parse incoming JSON data
            $requestData = json_decode(file_get_contents("php://input"), true);

            // Validate required fields for adding a user
            if (empty($requestData['FirstName']) || empty($requestData['LastName']) || empty($requestData['email'])) {
                http_response_code(400);
                echo json_encode(['error' => 'First name, last name, and email are required']);
                break;
            }

            // Check if email already exists
            $emailCheck = $pdo->prepare("SELECT 1 FROM profil WHERE email = ?");
            $emailCheck->execute([$requestData['email']]);

            if ($emailCheck->rowCount() > 0) {
                http_response_code(409);
                echo json_encode(['error' => 'Email already exists']);
                break;
            }

            // Insert new user into database
            $stmt = $pdo->prepare("
                INSERT INTO user (FirstName, LastName, age, address, user_swap_score) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $requestData['FirstName'],
                $requestData['LastName'],
                $requestData['age'] ?? null,
                $requestData['address'] ?? null,
                0 // Default swap score
            ]);

            $userId = $pdo->lastInsertId();

            // Insert profile data for the new user
            $profileStmt = $pdo->prepare("
                INSERT INTO profil (user_id, email, password) 
                VALUES (?, ?, ?)
            ");
            $profileStmt->execute([
                $userId,
                $requestData['email'],
                password_hash($requestData['password'], PASSWORD_DEFAULT) // Password hashing
            ]);

            http_response_code(201);
            echo json_encode([
                'id' => $userId,
                'message' => 'User added successfully'
            ]);
            break;

        case 'PUT':
            $requestData = json_decode(file_get_contents("php://input"), true);

            // Verify user ID exists
            if (empty($requestData['user_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'User ID is required']);
                break;
            }

            // Get current user data
            $checkUser = $pdo->prepare("SELECT * FROM user WHERE `user_id` = ?");
            $checkUser->execute([$requestData['user_id']]);
            $currentData = $checkUser->fetch(PDO::FETCH_ASSOC);

            if (!$currentData) {
                http_response_code(404);
                echo json_encode(['error' => 'User not found']);
                break;
            }

            // Merge new data with existing data (preserve unchanged fields)
            $mergedData = array_merge($currentData, $requestData);

            // Prepare dynamic update query
            $updateFields = [];
            $params = [];

            // List of allowed fields to update
            $allowedFields = [
                'FirstName', 
                'LastName', 
                'age', 
                'address'
            ];

            foreach ($allowedFields as $field) {
                if (isset($requestData[$field])) {
                    $updateFields[] = "`$field` = ?";
                    $params[] = $requestData[$field];
                }
            }

            // If no valid fields provided
            if (empty($updateFields)) {
                http_response_code(400);
                echo json_encode(['error' => 'No valid fields provided for update']);
                break;
            }

            // Add user_id to params
            $params[] = $requestData['user_id'];

            // Build and execute dynamic query
            $query = "UPDATE user SET " . implode(', ', $updateFields) . " WHERE `user_id` = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'User updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'No changes made']);
            }
            break;

        case 'DELETE':
            if (empty($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'User ID is required']);
                break;
            }

            // Delete user profile first
            $profileStmt = $pdo->prepare("DELETE FROM profil WHERE user_id = ?");
            $profileStmt->execute([$_GET['id']]);

            // Delete the user record
            $stmt = $pdo->prepare("DELETE FROM user WHERE user_id = ?");
            $stmt->execute([$_GET['id']]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'User removed successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'User not found']);
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

function addSwapScore($userId) {
    global $pdo;
    // Increase the user's swap score by 1 each time a swap is made
    $stmt = $pdo->prepare("UPDATE user SET user_swap_score = user_swap_score + 1 WHERE user_id = ?");
    $stmt->execute([$userId]);
}
?>
