<?php
    require_once('../functions.php');
    session_start();
    if(!isset($_SESSION["username"]) && !isset($_SESSION["branch"]) )
	{
    	header("location: ../login.php");
    	exit();
	}
    $session_name = $_SESSION["username"];
    $userid2 = $_SESSION["userID"];
    $branch = $_SESSION["branch"];
    $row = array();
	$row = user_data($session_name);
    $last_login = $row["LAST_LOGIN"];
    $cashier = $row["USER_FIRSTNAME"]." ".$row["USER_LASTNAME"];
    $totPrice = total_payment($branch);
    $totsales = total_sales($branch);
    $total_sold = total_sold_items($branch);

    $query = "SELECT INVC_STATUS FROM INVOICE WHERE INVC_STATUS = 'UNUSED' AND INVC_BRANCH = :branch";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":branch", $branch);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    if(empty($result)){
        $button = '<button type="submit" class="btn btn-outline-primary" name="addInvc" id="addInvc">Generate Invoice</button>';
    }
    else{
        $button = '';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="css/ribbon.css">
    <link rel ="icon" href ="dashboard-icons/logo.png" type ="image/x-icon">
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </symbol>
    </svg>
</head>
<body>
    <?php include "header_sidebar.php"; ?>
    <main>
        <div class="row page-path">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"> <i class='bx bxs-home' style='color:#36a7ff'  ></i></a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </nav>
        </div>

        <h1>Dashboard</h1>

        <div class="panels">
            <a href="sales.php">
                <div class="panels-container">
                    <div class="panels-icon2">
                        <i class='bx bx-line-chart' style='color:#ffffff'></i>
                    </div>
                    <div class="panels-stats">
                        <div class="panels-stats-box">
                            <p class="stats-num">&#8369;<?php echo number_format($totsales, 2, '.'); ?></p>
                            <p class="stats-label">Daily Sales</p>
                        </div>
                    </div>
                </div>
            </a>

            <div class="panels-container">
                <div class="panels-icon3">
                    <i class='bx bx-money-withdraw' style='color:#ffffff' ></i>
                </div>
                <div class="panels-stats">
                    <div class="panels-stats-box">
                        <p class="stats-num"><?php echo number_format($total_sold);?></p>
                        <p class="stats-label">Items Sold</p>
                    </div>
                </div>
            </div>
        </div>

        <h1 class="ribbon">
            <strong class="ribbon-content">P.O.S&nbsp;System</strong>
        </h1>

        <div class="POS">
            <div class="add-form bg-light">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div style="font-weight: 600;">
                      Generate New Invoice
                    </div>
                </div>
                <form class="needs-validation" action="" method="post" novalidate>
                    <div class="pos-input">
                        <label>Customer</label>
                        <select class="form-select" required aria-label="select example" name="cus_type">
                            <option value="">Select Customer Type</option>
                            <option value="New">New</option>
                            <option value="Regular">Regular</option>
                            <option value="VIP">VIP</option>
                        </select>
                        <div class="invalid-feedback">Please select a customer type</div>
                    </div>

                    <div class="pos-input"  style="margin-bottom: 30px;">
                        <label>Mode of Payment</label>
                        <select class="form-select" required aria-label="select example" name="mop_type">
                            <option value="">Select Mode of Payment</option>
                            <option value="Cash">Cash</option>
                            <option value="Debit Card">Debit Card</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Online App">Online App</option>
                        </select>
                        <div class="invalid-feedback">Please select a MOP</div>
                    </div>

                    <div class="btn-container">
                        <?php echo $button; ?>
                    </div>
                </form>
                <div class="alert-box">
                  <?php add_invoice($branch, $cashier); ?>
                </div>
            </div>

            <div class="add-form-2 bg-light">
                <div class="alert alert-success d-flex align-items-center" role="alert" style="margin-bottom: 30px;">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div style="font-weight: 600;">
                        Order Items
                    </div>
                </div>
                <form action="" class="needs-validation" novalidate method="post">
                    <div class="row" style="margin-bottom: 30px;">
                        <div class="pos-input">
                            <label>Invoice ID</label>
                            <select class="form-select" required aria-label="select example" name="invc_id">
                                <option value="">Please select invoice ID</option>
                                <?php
                                $query= "SELECT INVC_NUMBER FROM INVOICE WHERE INVC_STATUS = 'UNUSED' AND INVC_BRANCH = :branch";
                        
                                $stmt = $pdo->prepare($query);

                                $stmt->bindParam(":branch", $branch);
                        
                                $stmt->execute();
                        
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                foreach($result as $invcID){
                            ?>
                                <option value="<?php echo $invcID["INVC_NUMBER"]; ?>"><?php echo $invcID["INVC_NUMBER"]; ?></option>
                        <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select invoice ID</div>
                        </div>

                        <div class="pos-input">
                            <label for="inventory-id" class="form-label">Inventory I.D</label>
                            <input type="number" placeholder="####" id="inventory-id" class="form-control" required name="invID">
                            <div class="invalid-feedback">
                                Please input inventory ID
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 30px;">
                        <div class="pos-input">
                            <label for="qnty">Quantity</label>
                            <input type="number" placeholder="####" id="qnty" class="form-control" required name="item_qty">
                            <div class="invalid-feedback">
                                Please input quantity
                            </div>
                        </div>

                        <div class="pos-input">
                            <label for="dscnt">Discount</label>
                            <input type="number" placeholder="###" id="dscnt" step=".01" name="discount">
                        </div>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn btn-success col-4" name="order_save" id="addItemBtn">Add Item</button>
                    </div>
                </form>
                <?php save_order_list() ?>
            </div>
        </div>

        <div class="table-responsive-md invc-table mb-3">
            <?php order_item_list($branch); ?>
        </div>

        <div class="total-price">
            <div class="add-form3 bg-light">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                    <div style="font-weight: 600;">
                      Total Payments
                    </div>
                </div>

                <div class="pos-payment">
                    <h2>Total Payment: &#8369;<span id="price"><?php echo number_format($totPrice, 2, '.'); ?></span></h2>
                </div>

                <form class="needs-validation" action="" method="post" novalidate>
                    <div class="pos-pay-input" style="margin-bottom: 10px;">
                        <label>Customer's Cash: </label>
                        <input type="number" placeholder="P#.##" class="form-control" required name="cash" step=".01" id="customersCash" oninput="calculate()">
                        <div class="invalid-feedback">
                            Please input an amount! 
                        </div>
                    </div>

                    <div class="pos-change">
                        <h2>Change: &#8369;<span id="change"></span></h2>
                    </div>

                    <div class="btn-payments">
                        <button type="submit" class="btn btn-primary col-4" name="getPaid" id="getPaid"><i class='bx bx-money' style='color:#ffffff'></i>PAY</button>
                    </div>
                </form>
            </div>
        </div>
        <?php payments($totPrice, $branch); ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    
    <script type="text/javascript">
        var forms = document.querySelectorAll(".needs-validation");

        Array.prototype.slice.call(forms).forEach(function(form)
        {
            form.addEventListener("submit", function(event)
            {
                if(!form.checkValidity())
                {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            });
        });
    </script>

    <script>
        function saveScrollPosition() {
            localStorage.setItem('scrollPosition', window.scrollY);
        }

        // Function to scroll to the previously saved position
        function scrollToSavedPosition() {
            var savedPosition = localStorage.getItem('scrollPosition');
            if (savedPosition !== null) {
                window.scrollTo(0, savedPosition);
                localStorage.removeItem('scrollPosition'); // Clear saved position after scrolling
            }
        }

        // Add event listener to the button to save scroll position addInvc
        document.getElementById('addItemBtn').addEventListener('click', saveScrollPosition);

        // Call the function to scroll to the saved position when the page is loaded
        window.onload = scrollToSavedPosition;
    </script>

    
    <script>
        function saveScrollPosition() {
            localStorage.setItem('scrollPosition', window.scrollY);
        }

        function scrollToSavedPosition() {
            var savedPosition = localStorage.getItem('scrollPosition');
            if (savedPosition !== null) {
                window.scrollTo(0, savedPosition);
                localStorage.removeItem('scrollPosition');
            }
        }

        document.getElementById('getPaid').addEventListener('click', saveScrollPosition);
        window.onload = scrollToSavedPosition;
    </script>

    <script>
        function calculate() {
            var result;
            var spanElement = document.getElementById('price');
            var spanText = spanElement.textContent;
            var spanText2 = spanText.replace(/,/g, '');
            var totalPrice = Number(spanText2);

            var input = document.getElementById('customersCash').value;

            if(totalPrice > input){
                result = " Insufficient Amount";
            }
            else{
                result = input - totalPrice;
            }

            // Display the result
            document.getElementById('change').textContent = result.toLocaleString(undefined, {maximumFractionDigits: 2});
        }
    </script>
</body>
</html>