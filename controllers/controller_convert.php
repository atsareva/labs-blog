<?php

include("assets/mpdf/mpdf.php");
require_once 'model.php';

class Controller_Convert extends Model
{

    function index()
    {
        if (isset($_GET['id']) && $_GET['id'] > 0)
        {
            $where = array(
                'id' => $_GET['id']
            );
            $material = $this->select($where, 'materials');
            $html = $material['full_text'];
            
            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /* задаем формат, отступы и.т.д. */
//$mpdf->charset_in = 'cp1251'; /*не забываем про русский*/

            $stylesheet = file_get_contents('assets/mpdf/style.css'); /* подключаем css */
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->list_indent_first_level = 0;
            $mpdf->WriteHTML($html, 2); /* формируем pdf */
            $mpdf->Output('material.pdf', 'I');
        }
    }

}

?>
