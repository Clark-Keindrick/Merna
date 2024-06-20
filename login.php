<?php
    require_once('functions.php');

    session_start();
    
    if(isset($_SESSION["branch"]) )
	{
        if($_SESSION["branch"] == "ADMIN"){
            header("location: Admin/dashboard.php");
            exit();
        }

        else if($_SESSION["branch"] == "LLC" || $_SESSION["branch"] == "CORDOVA"){
            header("location: STAFF/dashboard.php");
            exit();
        }

        else{
            header("location: login.php");
            exit();
        }
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name = "description" content = "Bike Parts, Bike Shop, Lapu-Lapu City Bike Shop, Cordova Bike Shop">
    <meta name = "keyword" content = "BikePro, Bike Pro">
    <meta name = "author" content = "Clark Mollejon">
    <title>User Log-in</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel ="icon" href ="logo.png" type ="image/x-icon">
</head>
<body>
    <div class="register-error"><?php register_user(); ?></div>
    <img src="icons/logo.png" alt="image" class="shop-logo">
    <div class="wrapper">
        <form action="loginVerify.php" autocomplete="off" method="post">
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" class="form-control <?php if (isset($_GET['error'])) {echo $_GET['error'];} ?>" placeholder="Username" name="uname"  >
                <img src="icons/user.png" alt="image">
                <div class="invalid-feedback user-valid">Error!</div>
            </div>

            <div class="input-box mb-5">
                <input type="password" class="form-control <?php if (isset($_GET['error'])) {echo $_GET['error'];} ?>"  placeholder="Password" name="password" >
                <img src="icons/lock.png" alt="image">
                <div class="invalid-feedback pass-valid" >Error!</div>
            </div>

            <button type="submit" class="btn">Login</button>

            <div class="register-link">
                <div><a href="#" data-bs-toggle="modal" data-bs-target="#registerStatus">Registration's Status</a></div>
                <div><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Register</a></div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exampleModalLabel">REGISTRATION FORM</h5>
                </div>
                <div class="modal-body">
                    <div class="add-form">
                        <form action="" class="needs-validation" method="post" enctype="multipart/form-data" novalidate autocomplete="off">
                            <div class="form-group mb-4">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="fname" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lname" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="phnum" class="form-label">Phone Number</label>
                                <input type="number" class="form-control" id="phnum" name="phnum" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="profpic" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" id="profpic" name="profpic" required>
                                <div class="invalid-feedback">
                                    choose photo!
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="form-label">Birthdate</label>
                                <div class="input-group date" id="datepicker">
                                    <input type="text" class="form-control" name="bdate" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-white">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="Male" required>
                                        <label class="form-check-label" for="male">Male</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="Female" required>
                                        <label class="form-check-label" for="female">Female</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="others" value="Others" required>
                                        <label class="form-check-label" for="others">Others</label>
                                      </div>
                                      <div class="invalid-feedback">Please select gender</div>
                                </div>
                            </div>
                            <div class="form-group mb-2 mt-4">
                                <label for="Uname" class="form-label">Username</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                    <input type="text" class="form-control" id="Uname"  aria-describedby="inputGroupPrepend" name="username" required>
                                    <div class="invalid-feedback">
                                        Please choose a username.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="pword">Password</label>
                                <input type="password" class="form-control" id="pword" name="pword" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="confirm">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm" name="confirmPword"  required>
                            </div>
                            <div class="form-group mt-4 mb-4">
                                <select class="form-select user-select" required aria-label="select example" name="branch" >
                                    <option value="">Select who is this user</option>
                                    <option value="ADMIN">Admin</option>
                                    <option value="LLC">LLC BRANCH</option>
                                    <option value="CORDOVA">CORDOVA BRANCH</option>
                                  </select>
                                  <div class="invalid-feedback">Please select a user</div>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary" >Register</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerStatus" tabindex="1" aria-labelledby="registerStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="registerStatusLabel">REGISTRATION'S STATUS</h5>
                </div>
                <div class="modal-body">
                    <div class="row row-cols-sm-1 row-cols-md-10  card-image">
                        <?php view_reg_status(); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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

    <script type="text/javascript">
        $(document).ready(function () {
        var today = new Date();
        $('#datepicker').datepicker({
            format: 'yy-mm-dd',
            autoclose:true,
            endDate: "today",
            maxDate: today
        }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });


        $('#datepicker').keyup(function () {
            if (this.value.match(/[^0-9]/g)) {
                this.value = this.value.replace(/[^0-9^-]/g, '');
            }
        });
    })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
    
    <script>

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


</body>
</html>