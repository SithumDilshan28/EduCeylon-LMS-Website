<?php
session_start();
$cid= $_SESSION["course_id"];
include_once("../DB_Files/db.php");

if (!isset($_SESSION['stu_id'])) {
    header('Location:../index.php');
}


$stu_email = $_SESSION['stu_email'];
$sql = "SELECT * FROM students WHERE stu_email='$stu_email'";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $stuId = $row['stu_id'];
    $stuName = $row["stu_name"];
}
// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');


if (isset($_REQUEST['payment'])) {

    $order_id = $_REQUEST['order_id'];
    $full_name = $_REQUEST['name'];
    $email = $_REQUEST['email'];
    $amount = $_REQUEST['price'];
    $course_id = $cid;
    $date = $_REQUEST['date'];
    $course_name = $_REQUEST["course_name"];

    $sql = "INSERT INTO courseorder(order_id, stu_name, stu_email, course_id,course_name, amount, date) VALUES ('$order_id','$full_name','$email','$course_id','$course_name','$amount','$date')";

    if ($conn->query($sql) == TRUE) {
        $msg = '<div class="success">Course Enrolled Successfully</div>';
        $dontrefresh = '<div class="alert">Do not Refresh Web Page</div>';
        echo "<script>setTimeout(()=>{window.location.href='MyCourse.php';},1500);</script>";
    } else {
        $msg = '<div class="alert">Payment Failed</div>';
    }
}
?> 


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edu-Ceylon College-Checkout</title>
    <link rel="stylesheet" href="CSS/Checkout.css">

</head>

<body onkeydown="return (event.keyCode != 116)">
    <div class="container">
        <?php
        if (isset($_SESSION['course_id'])) {
            $sql = "SELECT * FROM course WHERE course_id='$cid'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
        }
        ?>
        <form action="" method="POST">
            <?php if (isset($msg)) {
                echo $dontrefresh;
                echo "<br>";
                echo $msg;
            } ?><br>
            <div class="row">
                <div class="col">
                    <br>
                    <h3 class="title">Course details</h3>

                    <div class="inputBox">
                        <span>Order ID :</span>
                        <input name="order_id" type="text" readonly value="<?php echo uniqid(); ?>">
                    </div>
                    <div class="inputBox">
                        <span>full name :</span>
                        <input name="name" type="text" readonly value="<?php if (isset($stuName)) {
                                                                            echo $stuName;
                                                                        } ?>">
                    </div>
                    <div class="inputBox">
                        <span>email :</span>
                        <input name="email" type="email" readonly value="<?php echo $stu_email ?>">
                    </div>
                    <div class="inputBox">
                        <span>Course Name :</span>
                        <input type="text" name="course_name" readonly value="<?php echo $row['course_name'] ?>">
                    </div>
                    <div class="inputBox">
                        <span>Amount :</span>
                        <input type="text" name="price" readonly value="Lkr <?php echo $row['course_price'] ?>">
                    </div>
                    <div class="inputBox">
                        <input type="hidden" name="date" readonly value="<?php echo $date; ?>">
                    </div>
                </div>






            </div>
            <button type="submit" name="payment" class="submit-btn">proceed</button>
        </form>

    </div>
</body>

</html>