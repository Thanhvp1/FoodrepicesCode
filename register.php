<?php
// Thông tin cấu hình để kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Tên máy chủ MySQL (cục bộ)
$username = "root";        // Tên người dùng MySQL (thường là root khi dùng XAMPP)
$password = "";            // Mật khẩu MySQL (để trống nếu chưa đặt mật khẩu)
$dbname = "user_database";  // Tên cơ sở dữ liệu đã tạo

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối, nếu lỗi thì dừng chương trình
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu dữ liệu được gửi qua phương thức POST (người dùng nhấn nút đăng ký)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form đăng ký
    $username = $_POST['username'];        // Tên người dùng
    $email = $_POST['email'];              // Email người dùng
    $password = $_POST['password'];        // Mật khẩu người dùng (chưa mã hóa)

    // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Chuẩn bị câu lệnh SQL để thêm người dùng mới vào bảng `users`
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password); // Gắn giá trị vào câu lệnh SQL

    // Thực thi câu lệnh SQL
    if ($stmt->execute()) {
        // Hiển thị thông báo thành công và chuyển hướng về trang chính
        echo "<script>alert('Đăng ký thành công!'); window.location.href = 'index.html';</script>";
    } else {
        // Hiển thị thông báo lỗi nếu đăng ký không thành công
        echo "<script>alert('Đăng ký thất bại, vui lòng thử lại!'); window.history.back();</script>";
    }

    // Đóng câu lệnh chuẩn bị
    $stmt->close();
}

// Đóng kết nối đến cơ sở dữ liệu
$conn->close();
?>