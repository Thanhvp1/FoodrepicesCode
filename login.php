<?php
// Thông tin cấu hình để kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Tên máy chủ MySQL
$username = "root";        // Tên người dùng MySQL
$password = "";            // Mật khẩu MySQL
$dbname = "user_database";  // Tên cơ sở dữ liệu

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối, nếu lỗi thì dừng chương trình
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu dữ liệu được gửi qua phương thức POST (người dùng nhấn nút đăng nhập)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form đăng nhập
    $email = $_POST['email'];          // Email người dùng
    $password = $_POST['password'];    // Mật khẩu người dùng

    // Chuẩn bị câu lệnh SQL để kiểm tra email trong cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // Gắn email vào câu lệnh SQL
    $stmt->execute();                // Thực thi câu lệnh SQL
    $stmt->store_result();           // Lưu kết quả

    // Gắn kết kết quả (mật khẩu đã mã hóa)
    $stmt->bind_result($hashed_password);

    // Kiểm tra nếu email tồn tại trong cơ sở dữ liệu
    if ($stmt->num_rows > 0) {
        $stmt->fetch(); // Lấy mật khẩu đã mã hóa từ cơ sở dữ liệu

        // Kiểm tra mật khẩu người dùng nhập có trùng khớp với mật khẩu đã mã hóa không
        if (password_verify($password, $hashed_password)) {
            // Nếu mật khẩu trùng khớp, đăng nhập thành công
            echo "<script>alert('Đăng nhập thành công!'); window.location.href = 'index.html';</script>";
        } else {
            // Nếu mật khẩu không đúng, thông báo lỗi
            echo "<script>alert('Sai mật khẩu!'); window.history.back();</script>";
        }
    } else {
        // Nếu email không tồn tại, thông báo lỗi
        echo "<script>alert('Tài khoản không tồn tại!'); window.history.back();</script>";
    }

    // Đóng câu lệnh chuẩn bị
    $stmt->close();
}

// Đóng kết nối đến cơ sở dữ liệu
$conn->close();
?>