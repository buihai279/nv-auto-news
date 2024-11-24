<?php
header("Content-Type: application/json");

// Kết nối MongoDB
try {
    $mongo = new MongoClient("mongodb://localhost:27017");
    $db = $mongo->selectDB("ten_cua_database"); // Thay bằng tên database
    $collection = $db->selectCollection("ten_cua_collection"); // Thay bằng tên collection
} catch (MongoConnectionException $e) {
    echo json_encode(["success" => false, "message" => "Không thể kết nối tới MongoDB."]);
    exit;
}

// Kiểm tra nếu payload được gửi lên
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lấy dữ liệu JSON từ payload
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
