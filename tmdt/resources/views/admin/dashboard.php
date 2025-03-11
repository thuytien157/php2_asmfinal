<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Tổng quan</h1>
    <a class="btn btn-dark"  href="/php2/ASMC/user/logout">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
    <a class="btn btn-success" href="/php2/ASMC">
      <i class="bi bi-box-arrow-right"></i> Chuyển lại web
    </a>
  </div>
  <!-- <input type="date" class="form-control w-25 mb-4"> -->
  <form method="POST" id="filterForm" class="row g-3 align-items-end mb-3">
    <div class="col-md-3">
      <label for="date_from" class="form-label">Từ ngày:</label>
      <input type="date" id="date_from" class="form-control" name="date_from" required>
    </div>

    <div class="col-md-3">
      <label for="date_to" class="form-label">Đến ngày:</label>
      <input type="date" id="date_to" class="form-control" name="date_to" required>
    </div>

    <div class="col-md-3">
      <label for="group_by" class="form-label">Thống kê theo:</label>
      <select id="group_by" name="group_by" class="form-select">
        <option value="day">Ngày</option>
        <option value="month">Tháng</option>
        <option value="year">Năm</option>
      </select>
    </div>

    <div class="col-md-3">
      <button type="submit" name="loc2" class="btn btn-primary w-50">Lọc</button>
    </div>
  </form>
  <div class="row">
    <div class="col-lg-6 col-md-12 col-12">
      <canvas id="ordersChart"></canvas>
    </div>
    <div class="col-lg-6 col-md-12 col-12">
      <canvas id="salesChart"></canvas>
    </div>
  </div>

  <hr>


  <div class="row thongke">
    <div class="col-lg-6 col-md-12 col-12">
      <div class="card shadow-sm">
        <div class="card-header">Top 5 sản phẩm bán chạy</div>
        <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Số lượng bán</th>
              </tr>
            </thead>
            <tbody>
            <tbody>
              <?php foreach ($topSellingProducts as $index => $product): ?>
                <tr>
                  <td><?= $product['product_id'] ?></td>
                  <td><?= htmlspecialchars($product['name']) ?></td>
                  <td class="text-center"><?= $product['total_sold'] ?></td>
                </tr>
              <?php endforeach; ?>
              <!-- </tbody> -->

              <!-- <tr>
                  <td>2</td>
                  <td>Jane Smith</td>
                  <td><span class="badge bg-warning">Chờ xử lý</span></td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Emily Brown</td>
                  <td>2024-11-25</td>
                </tr>
                <tr>
                  <td>4</td>
                  <td>Michael Green</td>
                  <td>2024-11-23</td>
                </tr> -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-md-12 col-12">
      <div class="card shadow-sm">
        <div class="card-header">Số lượng đơn hàng theo trạng thái</div>
        <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>STT</th>
                <th>Trạng thái</th>
                <th>Tổng</th>
              </tr>
            </thead>
            <tbody>
            <tbody>
              <?php foreach ($orderStatusStats as $index => $status): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td><?= $status['status'] ?></td>
                  <td><?= $status['total'] ?></td>
                </tr>
              <?php endforeach; ?>

              <!-- <tr>
                  <td>1</td>
                  <td>Vue.js Basics</td>
                  <td><span class="badge bg-success">Hoàn thành</span></td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Jane Smith</td>
                  <td><span class="badge bg-warning">Chờ xử lý</span></td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Emily Brown</td>
                  <td>2024-11-25</td>
                </tr>
                <tr>
                  <td>4</td>
                  <td>Michael Green</td>
                  <td>2024-11-23</td>
                </tr> -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



</div>
<?php
$jsonData = json_encode($data);  
$jsontopSellingCategory = json_encode($topSellingCategory);  
?>
<script>
  var categoryData = <?php echo $jsontopSellingCategory; ?>;
  const label = categoryData.map(item => item.name);  
  const dataPoint = categoryData.map(item => parseInt(item.revenue_percentage)); 

  const salesCtx = document.getElementById('salesChart').getContext('2d');
  new Chart(salesCtx, {
    type: 'pie',
    data: {
      labels: label,
      datasets: [{
        label: 'Doanh thu',
        data: dataPoint,
        backgroundColor: ['#deafb9', '#ff9484', '#2fc4f9', '#757e85'],
      }]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: {
                callbacks: {
                    label: tooltipItem => tooltipItem.raw + '%'
                }
            }
        }
    }
  });
var revenueData = <?php echo $jsonData; ?>;
const labels = revenueData.map(item => item.date);  
const dataPoints = revenueData.map(item => parseInt(item.revenue)); 

const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'bar',
    data: {
        labels: labels,  
        datasets: [{
            label: 'Doanh thu',
            data: dataPoints,  
            backgroundColor: ['#deafb9', '#ff9484', '#0dcaf0'],
        }]
    }
});
</script>
</body>

</html>