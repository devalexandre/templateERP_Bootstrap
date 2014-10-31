<?php
include_once 'lib/adianti/util/TAdiantiLoader.class.php';
spl_autoload_register(array('TAdiantiLoader', 'autoload_gtk'));
$ini  = parse_ini_file('application.ini');
date_default_timezone_set($ini['timezone']);
TAdiantiCoreTranslator::setLanguage( $ini['language'] );
TApplicationTranslator::setLanguage( $ini['language'] );
define('APPLICATION_NAME', $ini['application']);
define('OS', strtoupper(substr(PHP_OS, 0, 3)));
define('PATH', dirname(__FILE__));
ini_set('php-gtk.codepage', 'UTF8');

class TApplication extends TCoreApplication
{
    function __construct()
    {
        parent::__construct();
        parent::set_size_request(500, 300);
        parent::set_title('Adianti ERP Template');
        parent::add(new GtkLabel('No desktop version available for this system'));
        parent::show_all();
    }
}

$app = new TApplication;

try
{
    Gtk::Main();
}
catch (Exception $e)
{
    $app->destroy();
    $ev=new TExceptionView($e);
    $ev->show();
    Gtk::main();
}
?>