<?php
require_once 'env.php';
loadEnv();

header("Content-Type: application/json");

// Kết nối MongoDB từ .env
$mongoUri = getenv('MONGO_URI');
$mongoDb = getenv('MONGO_DB');
$mongoCollection = getenv('MONGO_COLLECTION');

try {
    $mongo = new MongoClient($mongoUri);
    $db = $mongo->selectDB($mongoDb);
    $collection = $db->selectCollection($mongoCollection);
} catch (MongoConnectionException $e) {
    echo json_encode(["success" => false, "message" => "Không thể kết nối tới MongoDB."]);
    exit;
}

// Xử lý payload
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["success" => false, "message" => "Payload không hợp lệ."]);
        exit;
    }

    // Chèn dữ liệu vào MongoDB
    try {
        $collection->insert($data);
        echo json_encode(["success" => true, "message" => "Dữ liệu đã được chèn vào MongoDB."]);
    } catch (MongoCursorException $e) {
        echo json_encode(["success" => false, "message" => "Lỗi khi chèn dữ liệu: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Chỉ hỗ trợ phương thức POST."]);
}
?>
