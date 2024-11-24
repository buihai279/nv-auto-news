<?php
require_once 'env.php';
loadEnv();

header("Content-Type: application/json");

// Lấy cấu hình từ .env
$uploadPath = getenv('UPLOAD_PATH');
$maxFileSize = getenv('MAX_FILE_SIZE');
$allowedFileTypes = explode(',', getenv('ALLOWED_FILE_TYPES'));

// Tạo thư mục nếu chưa tồn tại
if (!is_dir($uploadPath)) {
    mkdir($uploadPath, 0777, true);
}

// Kiểm tra nếu có file được gửi lên
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["image"])) {
    $targetFile = $uploadPath . '/' . basename($_FILES["image"]["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Kiểm tra kích thước file
    if ($_FILES["image"]["size"] > $maxFileSize) {
        echo json_encode(["success" => false, "message" => "File quá lớn. Giới hạn " . ($maxFileSize / 1000000) . "MB."]);
        exit;
    }

    // Kiểm tra loại file
    if (!in_array($fileType, $allowedFileTypes)) {
        echo json_encode(["success" => false, "message" => "Chỉ chấp nhận các file: " . implode(', ', $allowedFileTypes) . "."]);
        exit;
    }

    // Lưu file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        echo json_encode(["success" => true, "message" => "Tải ảnh thành công.", "path" => $targetFile]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi tải ảnh."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Không có ảnh được tải lên."]);
}
?>
