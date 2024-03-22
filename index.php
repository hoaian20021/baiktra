<!DOCTYPE html>
<html>

<head>
    <title>Danh sách nhân viên</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    .pagination {
        margin-top: 20px;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 16px;
        text-decoration: none;
        color: black;
        background-color: #f2f2f2;
        border: 1px solid #ddd;
    }

    .pagination a.active {
        background-color: #4CAF50;
        color: white;
    }

    .pagination a:hover:not(.active) {
        background-color: #ddd;
    }
    </style>
</head>

<body>

    <h2>Danh sách nhân viên</h2>

    <table>
        <tr>
            <th>MaNV</th>
            <th>Họ và tên</th>
            <th>Giới Tính</th>
            <th>Nơi Sinh</th>
            <th>Mã Phòng</th>
            <th>Lương</th>
        </tr>
        <?php
        // Thực hiện kết nối đến cơ sở dữ liệu MySQL
        $servername = "localhost";
        $username = "root"; // Thay thế bằng username của bạn
        $password = ""; // Thay thế bằng mật khẩu của bạn
        $dbname = "qlnhansu"; // Thay thế bằng tên cơ sở dữ liệu của bạn

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Kiểm tra kết nối
        if ($conn->connect_error) {
            die("Kết nối không thành công: " . $conn->connect_error);
        }

        // Số bản ghi trên mỗi trang
        $records_per_page = 5;

        // Xác định trang hiện tại
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $current_page = $_GET['page'];
        } else {
            $current_page = 1;
        }

        // Xác định vị trí bắt đầu của các bản ghi trên trang hiện tại
        $start = ($current_page - 1) * $records_per_page;

        // Truy vấn danh sách nhân viên với LIMIT và OFFSET
        $sql = "SELECT MaNV, TenNV, Phai, NoiSinh, MaPhong, Luong FROM nhanvien LIMIT $start, $records_per_page";
        $result = $conn->query($sql);

        // Hiển thị thông tin từ kết quả truy vấn
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["MaNV"] . "</td>";
                echo "<td>" . $row["TenNV"] . "</td>";
                echo "<td>";
                if ($row["Phai"] == "NU") {
                    echo '<img src="woman.jpg" alt="Woman" >';
                } else {
                    echo '<img src="man.jpg" alt="Man" >';
                }
                echo "</td>";
                echo "<td>" . $row["NoiSinh"] . "</td>";
                echo "<td>" . $row["MaPhong"] . "</td>";
                echo "<td>" . $row["Luong"] . "</td>";
                echo "<td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Không có nhân viên nào.</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <!-- Hiển thị các liên kết phân trang -->
    <div class="pagination">
        <?php
        // Thực hiện kết nối đến cơ sở dữ liệu MySQL
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Truy vấn để đếm tổng số bản ghi
        $sql = "SELECT COUNT(*) AS total_records FROM nhanvien";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_records = $row['total_records'];
        $total_pages = ceil($total_records / $records_per_page);

        // Hiển thị các liên kết đến các trang trước đó
        if ($current_page > 1) {
            echo "<a href='?page=" . ($current_page - 1) . "'>Trang trước</a>";
        }       

        // Hiển thị các liên kết đến các trang
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $current_page) {
                echo "<a class='active' href='?page=$i'>$i</a>";
            } else {
                echo "<a href='?page=$i'>$i</a>";
            }
        }

        // Hiển thị các liên kết đến các trang sau đó
        if ($current_page < $total_pages) {
            echo "<a href='?page=" . ($current_page + 1) . "'>Trang sau</a>";
        }

        $conn->close();
        ?>
    </div>

</body>

</html>