<?php
require_once 'env.php';
loadEnv();

header("Content-Type: application/json");

// Kết nối MongoDB
$mongoUri = getenv('MONGO_URI');
$mongoDb = getenv('MONGO_DB');

function formatData($data) {
    if (is_array($data)) {
        // Nếu là mảng, lặp qua từng phần tử và xử lý
        foreach ($data as $key => $value) {
            $data[$key] = formatData($value);
        }
    } elseif (is_object($data)) {
        // Nếu là đối tượng, gọi đệ quy cho từng thuộc tính
        foreach ($data as $key => $value) {
            $data->$key = formatData($value);
        }
    } elseif (is_string($data)) {
        // Kiểm tra xem chuỗi có phải là ObjectId hay không
        if (preg_match('/^[a-fA-F0-9]{24}$/', $data)) {
            // Nếu là chuỗi dài 24 ký tự, coi như ObjectId
            return new MongoId($data);
        }

        // Kiểm tra xem chuỗi có phải là định dạng thời gian ISODate hay không
        if (strtotime($data)) {
            // Nếu chuỗi có thể chuyển đổi thành thời gian hợp lệ, coi nó là một thời gian
            return new MongoDate(strtotime($data));
        }
    } elseif (is_int($data)) {
        // Nếu là số nguyên, chuyển đổi sang MongoInt64
        return new MongoInt64($data);
    }
    return $data;
}

try {
    $mongo = new MongoClient($mongoUri);
    $db = $mongo->selectDB($mongoDb);

    // Nhận dữ liệu từ client (giả sử là JSON payload)
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Kiểm tra dữ liệu đầu vào
    if (empty($data) || !is_array($data)) {
        echo json_encode(["success" => false, "message" => "Dữ liệu đầu vào không hợp lệ."]);
        exit;
    }

    // Kiểm tra xem có trường "collection_name" trong body hay không
    if (!isset($data['collection_name'])) {
        echo json_encode(["success" => false, "message" => "Chưa có tên collection trong dữ liệu."]);
        exit;
    }

    // Lấy tên collection từ body
    $collectionName = $data['collection_name'];

    // Kiểm tra xem tên collection có hợp lệ không (chắc chắn là chuỗi không rỗng)
    if (empty($collectionName)) {
        echo json_encode(["success" => false, "message" => "Tên collection không hợp lệ."]);
        exit;
    }

    // Chọn collection tương ứng
    $collection = $db->selectCollection($collectionName);

    // Xóa trường "collection_name" trước khi lưu dữ liệu vào MongoDB
    unset($data['collection_name']);

    // Chuyển đổi dữ liệu tự động (ObjectId, MongoInt64 và MongoDate)
    $formattedData = formatData($data);

    // Chèn dữ liệu vào MongoDB
    $result = $collection->insert($formattedData);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Dữ liệu đã chèn thành công."]);
    } else {
        echo json_encode(["success" => false, "message" => "Chèn dữ liệu thất bại."]);
    }
} catch (MongoConnectionException $e) {
    echo json_encode(["success" => false, "message" => "Không thể kết nối tới MongoDB: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
