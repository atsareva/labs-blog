<?php

include("assets/mpdf/mpdf.php");

class Controller_Convert
{

    function index($id = NULL)
    {
        if (isset($id[0]))
        {
            $material = Core::getModel('material')->load((int) $id[0]);
            if ($material->getId())
            {
                $html = $material->getFullText();

                $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /* задаем формат, отступы и.т.д. */

                $stylesheet = file_get_contents('assets/mpdf/style.css'); /* подключаем css */
                $mpdf->WriteHTML($stylesheet, 1);

                $mpdf->list_indent_first_level = 0;
                $mpdf->WriteHTML($html, 2); /* формируем pdf */
                $mpdf->Output($material->getTitle() . '.pdf', 'I');
            }
        }
    }

}

?>
