<?php 
require_once(ROOT . DS  .'vendor' . DS  . 'dompdf' . DS . 'autoload.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Lato-Hairline');
$dompdf = new Dompdf($options);

$dompdf = new Dompdf();


//$description =  wordwrap($invoice->invoice->delivery_description,25,'<br/>');
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
    #footer { position: fixed; left: 0px; bottom: -30px; right: 0px; height: 120px;text-align:center;}
    #footer .page:after { content: content: counter(page); }
	#content{
    position: relative; 
	}
	
	.img-height{
		height:15;
		text-align:center;
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
	    //border: 1px solid  #000; 
		//border-collapse: collapse;
		padding:2px; 
	}
	.itemrow tbody td{
		//border-bottom: none;border-top: none;
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
		
		font-size:'. h($invoice->invoice->dispatch_font_size).' !important;
	}
	.table_rows td{
		
		font-size:'. h($invoice->invoice->dispatch_font_size).' !important;
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
				<td width="35%" rowspan="2" valign="bottom">
				<img src='.ROOT . DS  . 'webroot' . DS  .'logos/'.$invoice->invoice->company->logo.' height="80px" />
				</td>
				<td colspan="2" align="right">
				<span style="font-size: 20px;">'. h($invoice->invoice->company->name) .'</span>
				</td>
			</tr>
			<tr>
				<td width="30%" valign="top"> 
				<div align="center" style="font-size: 28px;font-weight: bold;color: #0685a8;"></div>
				</td>
				<td align="right" width="35%" style="font-size: 12px; ">
				<span >'. $this->Text->autoParagraph(h($invoice->invoice->company->address)) .'</span>
				<span ><img style="margin-top:3px !important;" src='.ROOT . DS  . 'webroot' . DS  .'img/telephone.gif height="11px" /> '. h($invoice->invoice->company->mobile_no).'</span> | 
				<span><img style="margin-top:2px !important;" src='.ROOT . DS  . 'webroot' . DS  .'img/email.png height="15px" /> '. h($invoice->invoice->company->email).'</span>
				</td>
			</tr>
			<tr>
				<td colspan="3" >
					<div style="border:solid 2px #0685a8;margin-top: 5px; margin-top:15px;"></div>
				</td>
			</tr>
		</table>
  </div>
   <div id="footer">
	<hr style="width:100%"/>
	<table width="100%" style="text-align:center;font-size:10px;">
		<tr>
			<td  width="100%" colspan="7" align="center"><b style="font-size:12px;"><u>:: Authorised Dealer for Rajasthan ::</u></b></td>
		</tr>
		<tr>
			<td width="25%">
				<img src="'.ROOT . DS  . 'webroot' . DS  .'img/quotation/tushaco_pumps.png" class="img-height" />
				<br/>Gear / Screw Pumps
			</td>
			<td width="25%">
				<img src="'.ROOT . DS  . 'webroot' . DS  .'img/quotation/johnson_pump.png" class="img-height"/>
				<br/>Centrifugal/Multistage Pumps
			</td>
			<td width="25%">
				<img src="'.ROOT . DS  . 'webroot' . DS  .'img/quotation/antico.png" class="img-height" />
				<br/>Non Metallic Pumps
			</td>
			<td width="25%">
				<img src="'.ROOT . DS  . 'webroot' . DS  .'img/quotation/darling_pump.png" class="img-height" />
				<br/>Submersible Pumps
			</td>
			<td width="33%">
				<img src="'.ROOT . DS  . 'webroot' . DS  .'img/quotation/allweler.png" class="img-height"  />
				<br/>Screw Pumps
			</td>
			<td width="33%">
				<img src="'.ROOT . DS  . 'webroot' . DS  .'img/quotation/lightnin.png" class="img-height" />
				<br/>Mixers / Agitators
			</td>
			<td width="34%">
				<img src="'.ROOT . DS  . 'webroot' . DS  .'img/quotation/positive.png" class="img-height" />
				<br/>Dosing / Diaphragm Pumps
			</td>
		</tr>
	</table>
  </div>

  
  <div id="content"> ';
  
  $html.='
 
<table width="100%" class="table_rows itemrow" style="">
	<thead>
		<tr class="show">
			<td align="">';
				$html.='
					<table  valign="center" width="100%"  class="table2">
						<tr>
							<td style="width: 1em; word-wrap: break-word;  font-family:Palatino Linotype; font-size:'. h(($invoice->dispatch_font_size)) .';">
								<span><b>'. h($invoice->invoice->customer->customer_name) .'</b></span><br/>
								
								'. $this->Text->autoParagraph(h($invoice->invoice->customer_address));
								
                            $html.=' </td>
						</tr>
						<tr>
						<td></td>
						</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top:15px; font-family:Palatino Linotype;  font-size:'. h(($invoice->invoice->dispatch_font_size)) .';">Attn
			<span><b>:</b></span>
			<span>'. h($invoice->invoice->sales_order->dispatch_name) .'</span><br/>
			</td>
		</tr>
		<tr><td colspan="2"  style="padding-top:15px; font-family:Palatino Linotype; font-size:'. h(($invoice->invoice->dispatch_font_size)) .';">Sub
			<span><b>:</b></span>
			<span>Dispatch Intimation</span><br/>
			</td>
		</tr>
		<tr><td colspan="2"  style="padding-top:15px; font-family:Palatino Linotype; font-size:'. h(($invoice->invoice->dispatch_font_size)) .';">Ref
			<span><b>:</b></span>
			<span>Your Purchase Order No.'. h($invoice->invoice->customer_po_no) .' dated '. h(date("d-m-Y",strtotime($invoice->invoice->po_date))) .'</span><br/>
			</td>
		</tr>
		<tr>
			<td colspan="2"  style="padding-top:15px; font-family:Palatino Linotype; font-size:'. h(($invoice->invoice->dispatch_font_size)) .';">Dear Sir, </td>
		</tr>
		<tr>
			<td  colspan="2" style=" font-family:Palatino Linotype; font-size:'. h(($invoice->invoice->dispatch_font_size)) .';"><br/>With reference to above, please find herewith following dispatch documents:</td>
		</tr>
		<tr>
			<td  colspan="2" style=" font-family:Palatino Linotype; font-size:'. h(($invoice->invoice->dispatch_font_size)) .';"><br/>1. Invoice No. '. h(($invoice->invoice->in1."/"."IN-".str_pad($invoice->invoice->in2, 3, "0", STR_PAD_LEFT)."/".$invoice->invoice->in3."/".$invoice->invoice->in4)) .' dated '. h(date("d-m-Y",strtotime($invoice->invoice->date_created))).' For Rs.'. h(number_format($invoice->invoice->grand_total,2)).'/- in duplicate.</td>
		</tr>
		<tr>
			<td colspan="2" style=" font-family:Palatino Linotype; font-size:'. h(($invoice->invoice->dispatch_font_size)) .';">
		'  .$invoice->send_data . '
			</td>
		</tr>
				
				<tr>
					<td style=" font-family:Palatino Linotype; font-size:'. h(($invoice->invoice->dispatch_font_size)) .';"><br/><b>Regards,</b></td>
				</tr></br>
				
				<tr>
					<td style=" font-family:Palatino Linotype; font-size:'. h(($invoice->invoice->dispatch_font_size)) .';">
					</br><img src='.ROOT . DS  . 'webroot' . DS  .'signatures/'.$invoice->invoice->creator->signature.' height="50px" style="height:50px;"/>
					<br/>'.h($invoice->invoice->creator->name).'
					<br><span>'.h($invoice->invoice->creator->designation->name).'</span>
					</td>
				</tr>
				
	</table>

</body>
</html>';

	//echo $html; exit; 

$name='Invoice-'.h(($invoice->invoice->in1.'_IN'.str_pad($invoice->invoice->in2, 3, '0', STR_PAD_LEFT).'_'.$invoice->invoice->in3.'_'.$invoice->invoice->in4));

$dompdf->loadHtml($html);
//$dompdf->set_paper('letter', 'landscape');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$output = $dompdf->output(); //echo $name; exit;
file_put_contents('Invoice_email/'.$name.'.pdf', $output);
$dompdf->stream($name,array('Attachment'=>0));
exit(0);
?>
