<?php
header("Content-Type: application/json");

// Đặt thư mục lưu trữ ảnh
$targetDir = "uploads/";

// Tạo thư mục nếu chưa tồn tại
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Kiểm tra nếu có file được gửi lên
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["image"])) {
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Kiểm tra kích thước file (tối đa 2MB)
    if ($_FILES["image"]["size"] > 2000000) {
        echo json_encode(["success" => false, "message" => "File quá lớn. Giới hạn 2MB."]);
        exit;
    }

    // Chỉ chấp nhận định dạng ảnh
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(["success" => false, "message" => "Chỉ chấp nhận các file JPG, JPEG, PNG, GIF."]);
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
