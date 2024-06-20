<?php
    require_once("dompdf/autoload.inc.php");
    use Dompdf\Dompdf;

    $dompdf = new Dompdf();
    $dompdf->loadHtml("POForm.php");
    $dompdf->render();
    $dompdf->stream();
?>