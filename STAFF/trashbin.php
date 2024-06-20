<?php
    require_once('../functions.php');
    session_start();
    if(!isset($_SESSION["username"]) && !isset($_SESSION["branch"]) )
	{
    	header("location: ../login.php");
    	exit();
	}
  $branch = $_SESSION["branch"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trashbin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="css/trashbin.css">
    <link rel="stylesheet" href="css/ribbon.css">
    <link rel ="icon" href ="dashboard-icons/logo.png" type ="image/x-icon">
</head>
<body>
    <?php include "header_sidebar.php"; ?>
    <main>
        <div class="row page-path">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"> <i class='bx bxs-home' style='color:#36a7ff'  ></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Trashbin</li>
                </ol>
              </nav>
        </div>

        <h1 class="ribbon">
            <strong class="ribbon-content">DELETED INVOICES</strong>
        </h1>

        <div class="inv_table table-responsive-md">
            <?php echo deleted_invoice_table($branch) ?>
        </div>

        <div class="modal fade" id="invcModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-success text-white">
                  <h5 class="modal-title" id="exampleModalLabel">Print Receipt?</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <img src="dashboard-icons/logo.png" alt="" class="mod-img">
                    </div>

                    <div class="headings">
                        <h2>BikePro Bike Shop</h2>
                        <p>Babang II Rd, Lapu-Lapu City, Cebu</p>
                        <p>Phone: +63-927-981-5165</p>
                        <p>Email: clarkmollejon18@gmail.com</p>
                    </div>

                    <div class="details">
                        <h2>SALES INVOICE</h2>
                        <p>CASHIER &nbsp;&nbsp;&nbsp;&nbsp;: <span>Clark Mollejon</span></p>
                        <p>INVOICE NO &nbsp;: <span>11023</span></p>
                        <p>CUSTOMER &nbsp;&nbsp;&nbsp;: <span>Guest</span></p>
                        <p>DATE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>November 13th, 2023</span></p>
                        <p>TIME &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>3:32:16 AM</span></p>
                    </div>

                    <table class="table table-sm receipt">
                        <tbody>
                          <tr>
                            <td class="headers">ITEM</td>
                            <td class="headers">QTY</td>
                            <td class="headers">UNIT</td>
                            <td class="headers">PRICE</td>
                          </tr>
                          <tr>
                            <td class="data">Bar Tape T-35 Pink</td>
                            <td class="data">2</td>
                            <td class="data">pairs</td>
                            <td class="data">₱244.00</td>
                          </tr>
                          <tr>
                            <td class="data">Freewheel 18T Kent</td>
                            <td class="data">5</td>
                            <td class="data">pcs</td>
                            <td class="data">₱255.00</td>
                          </tr>
                          <tr>
                            <td class="data">CST Tire 700X25C</td>
                            <td class="data">2</td>
                            <td class="data">bottle</td>
                            <td class="data">₱690.00</td>
                          </tr>
                        </tbody>
                    </table>

                    <div class="payments">
                        <div class="inv-row">
                            <p>TOTAL</p>
                            <p>₱1,189.00</p>
                        </div>
                        
                        <div class="inv-row">
                            <p>DISCOUNT<span>(0.0):</span></p>
                            <p>₱0.00</p>
                        </div>

                        <div class="inv-row">
                            <p>PAYABLE</p>
                            <p>₱1,189.00</p>
                        </div>

                        <div class="inv-row">
                            <p>PAID:</p>
                            <p>₱2,000.00</p>
                        </div>

                        <div class="inv-row">
                            <p>BALANCE:</p>
                            <p>₱11.00</p>
                        </div>

                        <div class="inv-row">
                            <p>PAID VIA:</p>
                            <p>CASH</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">No</button>
                  <button type="button" class="btn btn-outline-primary">Print</button>
                </div>
              </div>
            </div>
          </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

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
</body>
</html>