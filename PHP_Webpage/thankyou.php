<?php
//Ensure user is logged in
session_start();

if (!isset($_SESSION["name"])) {
    echo 'Direct access not permitted. Please <a href="index.php">log in</a>.';
    die();
}

//Cookie
if (isset($_COOKIE['cart'])) {
    // Calculate the number of items in the cart
    $cartNum = count($_COOKIE['cart']);
    //var_dump($_COOKIE['cart']);
} else {
    $cartNum = 0;
}

include '../PHP_Function/db_connection.php';

$name = "";
$email = "";

$sql = $conn->prepare("SELECT email, recipientName from orders ORDER BY orderId DESC LIMIT 1;");
$sql->execute();
$result = $sql->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row["recipientName"];
        $email = $row["email"];
    }
}
$sql->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign In</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Luckiest Guy' rel='stylesheet'>
    <link rel="stylesheet" href="../CSS/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/thankyou.css">
</head>

<body>
    <div class="navigationBarContainer">
        <nav class="navBarContent">
            <span></span>
            <h1><a href="../PHP_Webpage/home.php">DUNOT</a></h1>
            <span class="icons">
                <a href="../PHP_Webpage/catalogue.php"><i class="fa fa-book fa-2x" aria-hidden="true"></i></a>

                <?php
                if ($cartNum > 0) {
                    echo "<div class='shoppingBag' data-count='" . $cartNum . "'>";
                } else {
                    echo "<div class='shoppingBag'>";
                }
                ?>
                <!-- <div class="shoppingBag"> -->
                <a href="../PHP_Webpage/basket.php"><i class="fa fa-shopping-bag fa-2x" aria-hidden="true"></i></a>
    </div>
    <a href="../PHP_Webpage/logout.php"><i class="fa fa-sign-out fa-2x" aria-hidden="true"></i></a>
    </span>
    </nav>
    </div>

    <div class="flex-container">
        <div class="bookLeft">
            <h1>DUNOT</h1>
        </div>

        <div class="bookRight">
            <div class="rightContent">
                <h2>Thank You!</h2>
                <p>Our deepest appreciation for being part of our story, <span style="color: #1C305C; font-weight: 900;">where imaginations come to life</span>, thanks to
                    readers like you.<br><br></p>

                <?php
                    echo '<p>Confirmation email of your orders has been sent to ' . $email . '.<br><br></p>';
                ?>                

                <p>Should you have any questions or concerns, please don't hesitate to reach out. We look forward to
                    see you again soon!</p>

                <button class="button indexBtn" onclick="backtohome()">Back to Home</button>
            </div>
        </div>
    </div>

    <footer>
        <div>
            <h1>
                DUNOT BOOKSTORE
            </h1>
            <p>50 Nanyang Avenue, <br>South Spine, <br>Singapore 639798.</p>
        </div>
    </footer>
</body>

<script>
    function backtohome(){
        const url = '../PHP_Webpage/home.php';
        window.location.href = url;
    }
</script>

<?php
//$to = 'f31ee@localhost';
$to = $email;
$subject = 'Dunot Order Number';
$message = 'Dear ' . $name . ', Your order has been received! The shipping is expected to arrive within 10 to 14 days. Thank you!';
$headers = 'From: f32ee@localhost' . "\r\n" .
    'Reply-To: f32ee@localhost' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail(
    $to,
    $subject,
    $message,
    $headers,
    '-ff32ee@localhost'
);
//echo ("mail sent to : " . $to);
?>
</html>