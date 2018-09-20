<?php 
require_once(ROOT . DS  .'vendor' . DS  . 'dompdf' . DS . 'autoload.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Lato-Hairline');
$dompdf = new Dompdf($options);

$dompdf = new Dompdf();


//$description =  wordwrap($challan->delivery_description,25,'<br/>');
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
		font-size:'. h($challan->pdf_font_size).' !important;
	}
	.table_rows td{
		border: 1px solid  #000;
		font-size:'. h($challan->pdf_font_size).' !important;
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
				<span style="font-size: 13px;margin:0;"><b>'. h($challan->pdf_to_print) .'</b></span>
				</td>
			</tr>
			<tr>
				<td width="25%" rowspan="2" valign="bottom">
				<img src='.ROOT . DS  . 'webroot' . DS  .'logos/'.$challan->company->logo.' height="80px" />
				</td>
				<td colspan="2" align="right">
				<span style="font-size: 20px;">'. h($challan->company->name) .'</span>
				</td>
			</tr>
			<tr>
				<td width="50%" valign="top"> 
				<div align="center" style="font-size: 28px;font-weight: bold;color: #0685a8;text-align:center;">CHALLAN</div>
				</td>
				<td align="right" width="35%" style="font-size: 12px; ">
				<span >'. $this->Text->autoParagraph(h($challan->company->address)) .'</span>
				<span ><img style="margin-top:3px !important;" src='.ROOT . DS  . 'webroot' . DS  .'img/telephone.gif height="11px" /> '. h($challan->company->mobile_no).'</span> | 
				<span><img style="margin-top:2px !important;" src='.ROOT . DS  . 'webroot' . DS  .'img/email.png height="15px" /> '. h($challan->company->email).'</span>
				</td>
			</tr>
			<!-- <tr>
				<td colspan="3" >
					<div style="border:solid 2px #0685a8;"></div>
				</td>
			</tr>-->
		</table>
  </div>
 

  
  <div id="content"> ';
  
  $html.='
  
<style>	
 

</style>

<table width="100%" class="table_rows itemrow" style="margin-top: -22px;">
	<thead>
		<tr class="show">
			<td align="">';
				$html.='
					<table  valign="center" width="100%"  class="table2">
						<tr>
							<td width="50%" valign="top" text-align="right">
								<span><b>';
								if($challan->customer_id){
									if(!empty($challan->customer->alias)){ 
										$html.= h($challan->customer->customer_name.'('.$challan->customer->alias.')');
									}else{
										$html.= h($challan->customer->customer_name);
									}
								}else {
									$html.= h($challan->vendor->company_name) ;
								}

								$html.='</b></span><br/>';
								if($challan->customer_id){
									$html.= $this->Text->autoParagraph(h($challan->customer_address));
									
								}else{
									$html.= $this->Text->autoParagraph(h($challan->vendor_address));
									
									
									if(!empty($challan->vendor->gst_no))
									{
										$html.='<span> GST No-'. h($challan->vendor->gst_no).  '</span><br/>';
									}
									
									
									
									if(!empty($challan->vendor->pan_no))
									{
										$html.='<span> PAN : '. h($challan->vendor->pan_no) . '</span><br/>';
									}
								
								}
								
                                
								
								
							$html.=' </td>
							<td style="white-space:nowrap;"  width="25%" valign="top">
								<table width="100%">
									<tr>
										<td valign="top" style="vertical-align: top;" >Challan No.</td>
										<td  valign="top">:</td>
										<td  valign="top" width="20%">'.h(($challan->ch1." /  CH-".str_pad($challan->ch2, 3, "0", STR_PAD_LEFT)." / ".$challan->ch3." / ".$challan->ch4)) .'</td>
									</tr>
									<tr>
										<td valign="top" style="vertical-align: top;">Date</td>
										<td valign="top">:</td>
										<td valign="top" >'. h(date("d-m-Y",strtotime($challan->created_on))) .'</td>
									</tr>
									<tr>
										<td valign="top" style="vertical-align: top;">LR No.</td>
										<td valign="top">:</td>
										<td valign="top" style="vertical-align: top;" >'. h($challan->lr_no) .'</td>
									</tr>
									<tr>
										<td valign="top" style="vertical-align: top;">Carrier</td>
										<td valign="top">:</td>
										<td valign="top">'. h($challan->transporter->transporter_name) .'</td>
									</tr>
								
								</table>
							</td>
						</tr>
						
				</table>
			</td>
		</tr>
		
	</thead>
</table>'; 

//echo $igst_hide;
$html.='
<table width="100%" class="table_rows">
		<thead>
			<tr>
				<th width="5%">S No</th>
				<th>Item Description</th>
				<th width="8%">Type</th>
				<th width="8%">Quantity</th>
				<th width="10%">Rate</th>
				<th width="10%">Amount</th>
			</tr>
		</thead>
		<tbody>
';

$sr=0; $h="-"; $total_taxable_value=0; foreach ($challan->challan_rows as  $challanRows){ $sr++;
// pr($challanRows);
$html.='
	<tr>
		<td style="padding-top:8px;padding-bottom:5px;" valign="top" align="center" class="topdata">'. h($sr) .'</td>
		<td class="topdata" style="padding-top:8px;padding-bottom:5px;line-height:20px " valign="top">
		<span class="topdata">'.$challanRows->item->name;
		
		$html.='		
		<div style="height:'.$challanRows->height.'"></div></span>
		<span>'.$challanRows->description .'<div style="height:'.$challanRows->height.'"></div></span></td>
		<td style="padding-top:8px;padding-bottom:5px;" valign="top" align="center">'. h($challanRows->challan_type) .'</td>
		<td style="padding-top:8px;padding-bottom:5px;" valign="top" align="center">'. h($challanRows->quantity) .'</td>
		<td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">'. $this->Money->indianNumberFormat($challanRows->rate) .'</td>
		<td style="padding-top:8px;padding-bottom:5px;" align="right" valign="top">'. $this->Money->indianNumberFormat($challanRows->amount) .'</td>';
		
		$html.='

	</tr>';
}
	$html.='<tr>
							<td colspan="5" style="text-align:right"><b>Total<b></td>
							<td style="text-align:right;" valign="">'. $this->Money->indianNumberFormat($challan->total) .'</td>
						</tr></tbody>';
$html.='</table>';
	
$grand_total=explode('.',$challan->total);
$rupees=$grand_total[0];
$paisa_text='';
if(sizeof($grand_total)==2)
{
	$grand_total[1]=str_pad($grand_total[1], 2, '0', STR_PAD_RIGHT);
	$paisa=(int)$grand_total[1];
	$paisa_text= 'And'.' '.h(ucwords($this->NumberWords->convert_number_to_words($paisa))) .' Paisa';
}else{ $paisa_text=""; }


$html.='

	<table width="100%" class="table_rows ">
		<tr>
			<td valign="top" width="18%">Amount in words</td>
			<td  valign="top" class="topdata"> '. h(ucwords($this->NumberWords->convert_number_to_words($rupees))) .'  Rupees ' .h($paisa_text).'</td>
		</tr>
		<tr>
			<td valign="top" width="18%">Documents</td>
			<td  valign="top" class="topdata">'. $this->Text->autoParagraph($challan->documents).'</td>
		</tr>
		<tr>
			<td valign="top" width="18%">Reference Detail</td>
			<td  valign="top" class="topdata">'. $this->Text->autoParagraph($challan->reference_detail).'</td>
		</tr>
		
		<tr>
				<td colspan="2">
					<table width="100%" class="table2" >
						<tr width="50%">
							<td  >
								
								<table>
									<tr>';
									if($challan->customer_id){
									$html.='
										<td >GST</td>
										<td >: ';
									}else{
										$html.='<td></td>
										<td >';
									}	
									
									if($challan->customer_id){
									
									if(!empty($challan->customer->gst_no))
									{
										$html.='<span>'. h($challan->customer->gst_no).  '</span>&nbsp;&nbsp;&nbsp;';
									}
									
								}
									$html.='</td>
									</tr>
									<tr width="30">';
									if($challan->customer_id){
										$html.='<td >PAN</td>
										<td >: ';
									}else{
										$html.='<td></td>
										<td>';
									}	
									
									if(!empty($challan->customer->pan_no))
									{
										$html.='<span>'. h($challan->customer->pan_no) . '</span><br/>';
									}
								
									$html.='</td>
									</tr>
									<tr>
										<td >CIN</td>
										<td >: '. h($challan->company->cin_no) .'</td>
									</tr>
								</table>
								<table>
									<tr>
										<td style="line-height:20px" >&nbsp;</td>
									</tr>
								</table>
							</td>
							<td align="right" style="width: 30%;">
								<div align="center">
									<span>For <b>'. h($challan->company->name) .'</b></span><br/>
									<img  src='.ROOT . DS  . 'webroot' . DS  .'signatures/'.$challan->creator->signature.' height="50px" style="height:50px; "/>
									<br/>
									<span><b>Authorised Signatory</b></span><br/>
									<span>'. h($challan->creator->name) .'</span><br/>
									
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
	</table>
	
			';

 $html .= '
</body>
</html>';

	//echo $html; exit; 

$name='Challan-'.h(($challan->ch1." /  CH-".str_pad($challan->ch2, 3, "0", STR_PAD_LEFT)." / ".$challan->ch3." / ".$challan->ch4));

$dompdf->loadHtml($html);
$dompdf->set_paper('letter', 'landscape');
//$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$output = $dompdf->output(); //echo $name; exit;
$dompdf->stream($name,array('Attachment'=>0));
exit(0);
?>
