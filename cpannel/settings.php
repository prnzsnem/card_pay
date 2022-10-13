<?php
if (is_file("basefile.php")) {
  $basePath = "./";
} else if (is_file("./../basefile.php")) {
  $basePath = "./../";
} else if (is_file("./../../basefile.php")) {
  $basePath = "./../../";
}
include $basePath . "include/page_req_prop.php";

$paymentAmount = 10;
$paymentReceiver = "Hotel Pauwa";
if (isset($_GET['Amount'])) {
  $paymentAmount = $_GET['Amount'];
  $paymentReceiver = $_GET['PaymentReceiver'];
}


$sql = "SELECT * FROM app_config";
$urlId = "";
$redirectUrl = "";
$statement = $connect->prepare($sql);
if ($statement->execute()) {
    $result = $statement->fetch();
    $urlId = $result["id"];
    $redirectUrl = $result["redirection_url"];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SrvPay - CPannel | Settings</title>
  <?php include $basePath . Route::$CSS_FILES; ?>

  <link href="dashboard.css" rel="stylesheet">
</head>

<body>

  <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0">SrvPay CPannel</a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <a class="nav-link" href="#">Sign out</a>
      </li>
    </ul>
  </nav>

  <div class="container-fluid">
    <div class="row" style="display: flex; flex-direction: row-reverse;">
      <nav class="col-md-2 d-none d-md-block bg-light sidebar">
        <div class="sidebar-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="./index.php">
                <span data-feather="home"></span>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active">
                <span data-feather="settings"></span>
                Settings <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item d-none">
              <a class="nav-link" href="#">
                <span data-feather="shopping-cart"></span>
                Products
              </a>
            </li>
            <li class="nav-item d-none">
              <a class="nav-link" href="#">
                <span data-feather="users"></span>
                Customers
              </a>
            </li>
            <li class="nav-item d-none">
              <a class="nav-link" href="#">
                <span data-feather="bar-chart-2"></span>
                Reports
              </a>
            </li>
            <li class="nav-item d-none">
              <a class="nav-link" href="#">
                <span data-feather="layers"></span>
                Integrations
              </a>
            </li>
          </ul>

          <h6 class="sidebar-heading d-flex d-none justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Saved reports</span>
            <a class="d-flex align-items-center text-muted" href="#">
              <span data-feather="plus-circle"></span>
            </a>
          </h6>
          <ul class="nav flex-column mb-2">
            <li class="nav-item d-none">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Current month
              </a>
            </li>
            <li class="nav-item d-none">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Last quarter
              </a>
            </li>
            <li class="nav-item d-none">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Social engagement
              </a>
            </li>
            <li class="nav-item d-none">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Year-end sale
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2">Settings</h1>
        </div>

        <div class="row pb-3">
          <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Help</span>
              <span class="badge badge-secondary badge-pill">3</span>
            </h4>
            <ul class="list-group mb-3">
              <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                  <h6 class="my-0">What is Redirection URL?</h6>
                  <small class="text-muted">It is a url to redirect after user successfully performs transaction.</small>
                </div>
              </li>
          </div>
          <div class="col-md-8 order-md-1">
            <div class="mb-3">
              <label for="text">Redirectin URL</label>
              <input type="hidden" id="urlId" value="<?php echo $urlId; ?>">
              <input type="text" class="form-control" id="redirectUrl" value="<?php echo $redirectUrl; ?>" placeholder="http://your_redirection_url.com/yourendpoint">
              <div id="redirect_url_holder_msg"></div>
            </div>
            <button class="btn btn-primary btn-lg btn-block" onclick="insertUpdateConfig('Update');">Save</button>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <?php include $basePath . Route::$JS_FILES; ?>

  <!-- Icons -->
  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
  <script>
    feather.replace()
  </script>

  <!-- Graphs -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  <script>
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        datasets: [{
          data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
          lineTension: 0,
          backgroundColor: 'transparent',
          borderColor: '#007bff',
          borderWidth: 4,
          pointBackgroundColor: '#007bff'
        }]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: false
            }
          }]
        },
        legend: {
          display: false,
        }
      }
    });
  </script>
</body>

</html>