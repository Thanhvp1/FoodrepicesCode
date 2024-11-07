<?php
// Kết nối đến cơ sở dữ liệu MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feedback_database";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu có dữ liệu POST được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $foodName = $_POST['foodName'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Kiểm tra các trường dữ liệu
    if (!empty($name) && !empty($phone) && !empty($foodName) && !empty($email) && !empty($message)) {
        // Chuẩn bị câu lệnh SQL để chèn dữ liệu
        $stmt = $conn->prepare("INSERT INTO feedbacks (name, phone, foodName, email, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $phone, $foodName, $email, $message);

        // Thực thi và kiểm tra xem phản hồi đã được lưu thành công
        if ($stmt->execute()) {
            echo "<script>
                alert('Phản hồi đã được lưu thành công!');
                window.history.back(); // Quay lại trang trước
            </script>";
        } else {
            echo "<script>
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
                window.history.back(); // Quay lại trang trước
            </script>";
        }

        // Đóng câu lệnh
        $stmt->close();
    } else {
        echo "<script>
            alert('Vui lòng điền đầy đủ thông tin.');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('Yêu cầu không hợp lệ.');
        window.history.back();
    </script>";
}

// Đóng kết nối
$conn->close();
?>