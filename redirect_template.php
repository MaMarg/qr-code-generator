<?php /* Template Name: Redirect Template */ ?>

<?php
//This is the WordPress template, paste it into your theme folder.

global $wpdb;
$androidResults = $wpdb->get_results('SELECT Hyperlink FROM prev_redirect_urls WHERE OS = "android";');
$iosResults = $wpdb->get_results('SELECT Hyperlink FROM prev_redirect_urls WHERE OS = "ios";');
$userInfo = $wpdb->get_results('SELECT UserInformation FROM prev_redirect_urls WHERE OS = "android";');
$redirectInfo = $wpdb->get_results('SELECT RedirectMessage FROM prev_redirect_urls WHERE OS = "android";');

foreach ($androidResults as $androidResult) {
    $androidStore = $androidResult->Hyperlink;
}

foreach ($iosResults as $iosResult) {
    $iosStore = $iosResult->Hyperlink;
}

foreach ($userInfo as $userResult) {
    $message = $userResult->UserInformation;
}

foreach ($redirectInfo as $redirectResult) {
    $redirectMessage = $redirectResult->RedirectMessage;
}
?>

<input id="android" value="<?php echo $androidStore ?>" style="display: none;">
<input id="ios" value="<?php echo $iosStore ?>" style="display: none;">

<h1><?php echo $message ?></h1><br>
<h1><a onclick="checkOS();"><?php echo $redirectMessage ?></a></h1>

<script>
    let isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        }
    };

    afibAndroid = document.getElementById('android').value;
    afibIOS = document.getElementById('ios').value;

    function checkOS() {
        isMobile.iOS() ? window.location = afibIOS : window.location = afibAndroid;
    }

    checkOS();
</script>
