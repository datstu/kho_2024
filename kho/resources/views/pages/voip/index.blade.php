<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Form với Select2 Required</title>

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- Fix CSS để HTML5 validate hoạt động -->
  <style>
    select[required].select2-hidden-accessible {
      display: inline !important;
      height: 0;
      padding: 0;
      width: 0;
      position: absolute;
    }
  </style>
</head>
<body>

  <h2>Form Gửi Thông Tin</h2>

  <form id="myForm">
    <label for="city">Chọn thành phố:</label>
    <select id="city" name="city" class="select2" required>
      <option value="">-- Vui lòng chọn --</option>
      <option value="hanoi">Hà Nội</option>
      <option value="danang">Đà Nẵng</option>
      <option value="hcm">TP HCM</option>
    </select>

    <br><br>
    <button type="submit">Gửi</button>
  </form>

  <script>
    $(document).ready(function() {
      $('.select2').select2({
        placeholder: "-- Vui lòng chọn --",
        allowClear: true
      });

      // Optional: Cảnh báo nếu chưa chọn (trường hợp trình duyệt không hỗ trợ validate HTML5)
      $('#myForm').on('submit', function(e) {
        if ($('#city').val() === '') {
          alert('Vui lòng chọn thành phố!');
          e.preventDefault();
        }
      });
    });
  </script>

</body>
</html>
