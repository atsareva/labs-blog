<?php
$html = '<table border="1"><tr><td>Пример 1</td><td>Пример 2</td><td>Пример 3</td><td>Пример 4</td></tr>
<tr><td>Пример 5</td><td>Пример 6</td><td>Пример 7</td><td><a href="http://mpdf.bpm1.com/" title="mPDF">mPDF</a></td></tr></table>';

include("mpdf.php");

$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
//$mpdf->charset_in = 'cp1251'; /*не забываем про русский*/

$stylesheet = file_get_contents('style.css'); /*подключаем css*/
$mpdf->WriteHTML($stylesheet, 1);

$mpdf->list_indent_first_level = 0;
$mpdf->WriteHTML($html, 2); /*формируем pdf*/
$mpdf->Output('mpdf.pdf', 'I');
?>
