<?php

class AdminController extends App
{
    public function __construct() {
        if (!(isset($_SESSION['admin']) && $_SESSION['admin'])) {
            $dev = 'Veuillez vous <b>identifier en tant qu\'admin</b>';
            $prod = 'Adresse URL incorrect';
            die(new NewException(404, $dev, $prod));
        }
    }

    public function importAction() {

    }

    public function exportAction() {
        $path = RP .'vendor'. DS .'upload'. DS;

        exec('perl '. $path .'shellTest.pl', $output);

        $file = $path .'myConverter.xlsx';

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }
}

?>