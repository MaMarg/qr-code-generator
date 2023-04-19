<?php
/*
Plugin Name: QR Code Generator
Description: Generates custom QR Codes and configurate the hyperlinks for the redirect-script.
Version: 1.0.0
Author: Marvin Margull
License: GPL2
*/

//Redirect Template needed, /wp-content/themes/current-child-theme/redirect_template.php

class DatabaseOperations
{
    public static function maybeCreateTable($tableName, $tableParams)   //Creates DB-table if it doesnt already exists.
    {
        global $wpdb;
        //Looks if the table already exists.
        $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($tableName));

        if ($wpdb->get_var($query) === $tableName) {
            return true;
        }

        // Didn't find it, so try to create it.
        $wpdb->query('CREATE TABLE ' . $tableName . ' (
        ' . $tableParams . '
      );');

        //Insert the placeholder values.
        $wpdb->insert($tableName, array('ID' => 1, 'OS' => 'android', 'Hyperlink' => 'Placeholder', 'UserInformation' => 'Placeholder', 'RedirectMessage' => 'Placeholder'));
        $wpdb->insert($tableName, array('ID' => 2, 'OS' => 'ios', 'Hyperlink' => 'Placeholder'));

        return false;
    }

    public static function dbSelect(string $param, string $os)     //Selects Hyperlink from database, depending on operating system (os).
    {
        global $wpdb;
        $results = $wpdb->get_results('SELECT ' . $param . ' FROM prev_redirect_urls WHERE OS = "' . $os . '";');

        foreach ($results as $result) {
            echo $result->$param;
        }
    }

    public static function dbUpdate(string $param, string $name, string $os)    //Updates the Hyperlink in the DB.
    {
        global $wpdb;
        $wpdb->update('prev_redirect_urls', array($param => $_POST[$name]), array('OS' => $os));
    }
}

class QrCodeGeneratorMenu
{
    public $plugin;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'addMenuPage'));      //Adds menu page and sidebar to the wp-admin page.
        $this->plugin = plugin_basename(__FILE__);
        add_filter("plugin_action_links_$this->plugin", array($this, 'settingsLink'));      //Adds "Settings" link in plugin overview.
    }

    public function addMenuPage()
    {
        add_menu_page('QR Code Generator', 'QR Code Generator', 'manage_options', 'qr_code_generator', array($this, 'QrCodeGeneratorPage'), 'dashicons-editor-expand');
    }

    public function settingsLink(array $links)
    {
        $settingsLink = '<a href="options-general.php?page=qr_code_generator">Settings</a>';
        array_push($links, $settingsLink);
        return $links;
    }

    public function QrCodeGeneratorPage()   //plugin UI and logic
    {
        require_once('phpqrcode/qrlib.php');    //includes QR-Code library

        if (isset($_POST['generate'])) {
            $url = $_POST['url'];

            QRcode::png($url, 'qrcode.png');    //creates QR-Code as img
            echo '<img src="qrcode.png" alt="QR-Code">';
            echo '<div class="notice notice-info is-dismissible">
            <p><a onclick="location.reload();" style="cursor: pointer;">Go back to page.</a></p>
            </div>';
        } else {
            require 'view/plugin_ui.php';

            if (isset($_POST['submit-btn'])) {
                try {
                    DatabaseOperations::dbUpdate('Hyperlink', 'android_input', 'android');      //updates DB entry with text fi
                    DatabaseOperations::dbUpdate('Hyperlink', 'ios_input', 'ios');
                    DatabaseOperations::dbUpdate('UserInformation', 'userinfo_input', 'android');
                    DatabaseOperations::dbUpdate('RedirectMessage', 'redirectmsg_input', 'android');
                    echo '<br><div class="notice notice-success is-dismissible"> 
                    <p><strong>Success! </strong>Your changes have been saved. <a onclick="location.reload();">Refresh</a> the page to see the new changes.</p>
                    </div>';
                } catch (Exception $error) {
                    echo '<br><div class="notice notice-error is-dismissible">
                    <p><strong>Error! </strong>Something went wrong(' . $error->getMessage() . ')</p>
                    </div>';
                }
            }
        }
    }
}

DatabaseOperations::maybeCreateTable('prev_redirect_urls', 'ID int, OS varchar(255), Hyperlink varchar(255), UserInformation varchar(255), RedirectMessage varchar(255), PRIMARY KEY (ID)');
new QrCodeGeneratorMenu();