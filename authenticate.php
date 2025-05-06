<?php
if (isset($_POST['credential'])) {
    $id_token = $_POST['credential'];

    // Validate the token with Google
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $id_token;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $userData = json_decode($response, true);

    // Define the list of allowed emails
    $allowedEmails = [
        'youssefa@saharahomecare.com',
        'orders@saharahomecare.com',
        'armaghan@saharahomecare.com',
        'saminas@saharahomecare.com',
        'nazimab@saharahomecare.com',
        'Fahadk@saharahomecare.com',
        'portalinquiries@saharahomecare.com',
        'nilofers@saharahomecare.com',
        'saimak@saharahomecare.com',
        'aashiyap@saharahomecare.com',
        'anams@saharahomecare.com',
        'aaliyar@saharahomecare.com',
        'faizas@saharahomecare.com',
        'jaet@saharahomecare.com',
        'yeseniav@saharahomecare.com',
        'saminak@saharahomecare.com',
        'bindals@saharahomecare.com',
        'rachnap@saharahomecare.com',
        'americac@saharahomecare.com',
        'joselynj@saharahomecare.com',
        'azmatr@saharahomecare.com',
        'sabak@saharahomecare.com'
    ];

    // Check if the token is valid and the email is in the allowed list
    if (isset($userData['email']) && in_array($userData['email'], $allowedEmails)) {
        // Successfully authenticated, email is allowed
        session_start();
        $_SESSION['email'] = $userData['email'];
        $_SESSION['name'] = $userData['name'];
        if ($_SESSION['email'] == "nilofers@saharahomecare.com") {
            $_SESSION['loca'] = "Lombard";
        } elseif ($_SESSION['email'] == "faizas@saharahomecare.com") {
            $_SESSION['loca'] = "Skokie";
        } elseif ($_SESSION['email'] == "anams@saharahomecare.com") {
            $_SESSION['loca'] = "Devon";
        } elseif ($_SESSION['email'] == "jaet@saharahomecare.com") {
            $_SESSION['loca'] = "Melrose Park";
        } elseif ($_SESSION['email'] == "saminak@saharahomecare.com") {
            $_SESSION['loca'] = "Hanover Park";
        } elseif ($_SESSION['email'] == "saimak@saharahomecare.com") {
            $_SESSION['loca'] = "Bolingbrook";
        } elseif ($_SESSION['email'] == "yeseniav@saharahomecare.com") {
            $_SESSION['loca'] = "Justice";
        } elseif ($_SESSION['email'] == "bindals@saharahomecare.com") {
            $_SESSION['loca'] = "Elgin";
        } elseif ($_SESSION['email'] == "aashiyap@saharahomecare.com") {
            $_SESSION['loca'] = "Albany Park";
        } elseif ($_SESSION['email'] == "rachnap@saharahomecare.com") {
            $_SESSION['loca'] = "Mount Prospect";
        } elseif ($_SESSION['email'] == "americac@saharahomecare.com") {
            $_SESSION['loca'] = "Crystal Lake";
        } elseif ($_SESSION['email'] == "sabak@saharahomecare.com") {
            $_SESSION['loca'] = "Devon";
        } elseif ($_SESSION['email'] == "joselynj@saharahomecare.com") {
            $_SESSION['loca'] = "Rockford";
        } else {
            $_SESSION['loca'] = "Warehouse";
        }

        header("Location: index.php"); // Redirect after successful login
        exit();
    } else {
        // Display a message for unauthorized emails
        echo "<style> body { display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; } .message-box { text-align: center; padding: 20px; border: 1px solid #ccc; background-color: white; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); } .message-box h1 { font-size: 24px; color: #333; } </style><body> <div class='message-box'> <h1>Access Denied: You do not have permission to access this application.</h1> </div> </body>";
    }
} else {
    echo "No token received!";
}
?>
