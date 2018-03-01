<?php 
require_once(ROOT . DS  .'vendor' . DS  . 'dompdf' . DS . 'autoload.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Lato-Hairline');
$dompdf = new Dompdf($options);

$dompdf = new Dompdf();


//$description =  wordwrap($invoice->delivery_description,25,'<br/>');
//pr($description);exit;
$html = '
<html>
<head>
  <style>
  @page { margin: 160px 15px 10px 30px; }

  body{
    line-height: 20px;
	}
	
    #header { position:fixed; left: 0px; top: -160px; right: 0px; height: 160px;}
    
	#content{
    position: relative; 
	}
	
	@font-face {
		font-family: Lato;
		src: url("https://fonts.googleapis.com/css?family=Lato");
	}
	p.test {
		width: 11px; 
    word-wrap: break-word;
}
	p{
		margin:0;font-family: Lato;font-weight: 100;line-height: 12px !important;margin-top:-9px;
	}
	.odd td p{
		margin:0;font-family: Lato;font-weight: 100;line-height: 17px !important;margin-bottom: -1px;
	}
	.show td p{
			margin:0;font-family: Lato;font-weight: 100;line-height: 17px !important;
	}
	.topdata p{
		margin:0;font-family: Lato;font-weight: 100;line-height: 17px !important;margin-bottom: 1px;
	}
	.des p{
		margin:0;font-family: Lato;font-weight: 100;line-height: 17px !important;margin-bottom: 1px;width:291px;
	}
	table td{
		margin:0;font-family: Lato;font-weight: 100;padding:0;line-height: 1;
	}
	table.table_rows tr.odd{
		page-break-inside: avoid;
	}
	.table_rows, .table_rows th, .table_rows td {
	    border: 1px solid  #000; 
		border-collapse: collapse;
		padding:2px; 
	}
	.itemrow tbody td{
		border-bottom: none;border-top: none;
	}
	
	.table2 td{
		border: 0px solid  #000;font-size: 14px;padding:0px; 
	}
	.table_top td{
		font-size: 12px !important; 
	}
	.table-amnt td{
		border: 0px solid  #000;padding:0px; 
	}
	.table_rows th{
		border: 1px solid  #000;
		font-size:'. h($invoice->pdf_font_size).' !important;
	}
	.table_rows td{
		border: 1px solid  #000;
		font-size:'. h($invoice->pdf_font_size).' !important;
	}
	.avoid_break{
		page-break-inside: avoid;
	}
	.table-bordered{
		border: hidden;
	}
	table.table-bordered td {
		border: hidden;
	}
	
	</style>

<body>
  <div id="header" ><br/>	
		<table width="100%">
			<tr>
				<td colspan="3" align="right">
				<span style="font-size: 13px;margin:0;"><b>'. h($invoice->pdf_to_print) .'</b></span>
				</td>
			</tr>
			<tr>
				<td width="35%" rowspan="2" valign="bottom">
				<img src='.ROOT . DS  . 'webroot' . DS  .'logos/'.$invoice->invoice->company->logo.' height="80px" />
				</td>
				<td colspan="2" align="right">
				<span style="font-size: 20px;">'. h($invoice->invoice->company->name) .'</span>
				</td>
			</tr>
			<tr>
				<td width="30%" valign="top"> 
				<div align="center" style="font-size: 28px;font-weight: bold;color: #0685a8;">TAX INVOICE</div>
				</td>
				<td align="right" width="35%" style="font-size: 12px; ">
				<span >'. $this->Text->autoParagraph(h($invoice->invoice->company->address)) .'</span>
				<span ><img style="margin-top:3px !important;" src='.ROOT . DS  . 'webroot' . DS  .'img/telephone.gif height="11px" /> '. h($invoice->invoice->company->mobile_no).'</span> | 
				<span><img style="margin-top:2px !important;" src='.ROOT . DS  . 'webroot' . DS  .'img/email.png height="15px" /> '. h($invoice->invoice->company->email).'</span>
				</td>
			</tr>
			<!-- <tr>
				<td colspan="3" >
					<div style="border:solid 2px #0685a8;"></div>
				</td>
			</tr>-->
		</table>
  </div>
 
<div id="content"> '
.$invoice->send_data.'</div>
</body>
</html>';

	//echo $html; exit; 

$name='Invoice';

$dompdf->loadHtml($html);
$dompdf->set_paper('letter', 'landscape');
//$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$output = $dompdf->output(); //echo $name; exit;
file_put_contents('Invoice_email/'.$name.'.pdf', $output);
$dompdf->stream($name,array('Attachment'=>0));
exit(0);
?>
