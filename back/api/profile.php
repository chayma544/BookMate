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
            if (isset($_GET['user_id'])) {
                // Get profile by user ID
                $stmt = $pdo->prepare("SELECT * FROM profil WHERE user_id = ?");
                $stmt->execute([$_GET['user_id']]);
                $profile = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($profile) {
                    // Remove password from response for security
                    unset($profile['password']);
                    echo json_encode($profile);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Profile not found']);
                }
            } 
            elseif (isset($_GET['email'])) {
                // Check if email exists (no password returned)
                $stmt = $pdo->prepare("SELECT user_id, email FROM profil WHERE email = ?");
                $stmt->execute([$_GET['email']]);
                $profile = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($profile) {
                    echo json_encode($profile);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Email not found']);
                }
            }
            else {
                // Admin only - should be protected
                $stmt = $pdo->query("SELECT user_id, email FROM profil");
                $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($profiles);
            }
            break;
            
        case 'POST':
            // For creating a profile separately (usually done when creating user)
            $requestData = json_decode(file_get_contents("php://input"), true);
            
            // Validate required fields
            if (empty($requestData['user_id']) || empty($requestData['email']) || empty($requestData['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'User ID, email and password are required']);
                break;
            }
            
            // Check if user exists
            $userCheck = $pdo->prepare("SELECT 1 FROM user WHERE user_id = ?");
            $userCheck->execute([$requestData['user_id']]);
            
            if ($userCheck->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'User not found']);
                break;
            }
            
            // Check if profile already exists
            $profileCheck = $pdo->prepare("SELECT 1 FROM profil WHERE user_id = ?");
            $profileCheck->execute([$requestData['user_id']]);
            
            if ($profileCheck->rowCount() > 0) {
                http_response_code(409);
                echo json_encode(['error' => 'Profile already exists for this user']);
                break;
            }
            
            // Check if email already exists
            $emailCheck = $pdo->prepare("SELECT 1 FROM profil WHERE email = ?");
            $emailCheck->execute([$requestData['email']]);
            
            if ($emailCheck->rowCount() > 0) {
                http_response_code(409);
                echo json_encode(['error' => 'Email already in use']);
                break;
            }
            
            // Hash password
            $hashedPassword = password_hash($requestData['password'], PASSWORD_DEFAULT);
            
            // Insert new profile
            $stmt = $pdo->prepare("
                INSERT INTO profil 
                (user_id, email, password) 
                VALUES (?, ?, ?)
            ");
            
            $stmt->execute([
                $requestData['user_id'],
                $requestData['email'],
                $hashedPassword
            ]);
            
            // Return success response
            http_response_code(201);
            echo json_encode([
                'user_id' => $requestData['user_id'],
                'message' => 'Profile created successfully'
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
            
            // Get current profile data
            $checkProfile = $pdo->prepare("SELECT * FROM profil WHERE user_id = ?");
            $checkProfile->execute([$requestData['user_id']]);
            $currentData = $checkProfile->fetch(PDO::FETCH_ASSOC);
            
            if (!$currentData) {
                http_response_code(404);
                echo json_encode(['error' => 'Profile not found']);
                break;
            }
            
            // Check if new email already exists
            if (isset($requestData['email']) && $requestData['email'] !== $currentData['email']) {
                $emailCheck = $pdo->prepare("SELECT 1 FROM profil WHERE email = ? AND user_id != ?");
                $emailCheck->execute([$requestData['email'], $requestData['user_id']]);
                
                if ($emailCheck->rowCount() > 0) {
                    http_response_code(409);
                    echo json_encode(['error' => 'Email already in use']);
                    break;
                }
            }
            
            // Prepare update query
            $updateFields = [];
            $params = [];
            
            // Update email if provided
            if (isset($requestData['email'])) {
                $updateFields[] = "email = ?";
                $params[] = $requestData['email'];
            }
            
            // Update password if provided
            if (isset($requestData['password'])) {
                $updateFields[] = "password = ?";
                $params[] = password_hash($requestData['password'], PASSWORD_DEFAULT);
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
            $query = "UPDATE profil SET " . implode(', ', $updateFields) . " WHERE user_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Profile updated successfully']);
            } else {
                http_response_code(200);
                echo json_encode(['message' => 'No changes made']);
            }
            break;
            
        case 'DELETE':
            // Usually profiles are deleted when users are deleted
            if (empty($_GET['user_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'User ID is required']);
                break;
            }
            
            $userId = $_GET['user_id'];
            
            $stmt = $pdo->prepare("DELETE FROM profil WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Profile deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Profile not found']);
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