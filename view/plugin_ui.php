<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPress Plugin</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>

<body>
    <form method="post" class="qr-code-form">
        <h2>QR Code Generator</h2>
        <input type="text" name="url" placeholder="Enter URL"><br><br>
        <input type="submit" class="button button-primary" name="generate" value="Generate QR Code">
    </form>

    <form method="post" class="hyperlink-param-form">
        <h2>Script Parameters</h2>
        <label>Android Hyperlink</label><br>
        <input type="url" class="input-field" name="android_input" value="<?php DatabaseOperations::dbSelect('Hyperlink', 'android'); ?>"><br>
        <label>iOS Hyperlink</label><br>
        <input type="url" class="input-field" name="ios_input" value="<?php DatabaseOperations::dbSelect('Hyperlink', 'ios'); ?>"><br><br>
        <label>User Information</label><br>
        <input type="text" class="input-field" name="userinfo_input" value="<?php DatabaseOperations::dbSelect('RedirectMessage', 'android'); ?>"><br>
        <label>Redirect Message</label><br>
        <input type="text" class="input-field" name="redirectmsg_input" value="<?php DatabaseOperations::dbSelect('UserInformation', 'android'); ?>"><br><br>
        <input type="submit" class="button button-primary" name="submit-btn" value="Submit Changes">
    </form>

    <style>
        <?php include 'style.css'; ?>
    </style>
</body>

</html>