<style>
@media print{
    .maindiv{
        width:100% !important;
    }   
    .hidden-print{
        display:none;
    }
}
p{
margin-bottom: 0;
}
</style>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0 5px 0 20px;  /* this affects the margin in the printer settings */
}
</style>
<a class="btn  blue hidden-print margin-bottom-5 pull-right" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>

<div style="border:solid 1px #c7c7c7;background-color: #FFF;padding: 10px;margin: auto;width: 55%;font-size: 12px;" class="maindiv">    
    <table width="100%" class="divHeader">
        <tr>
            <td width="30%"><?php echo $this->Html->image('/logos/'.$nppayment->company->logo, ['width' => '40%']); ?></td>
            <td align="center" width="30%" style="font-size: 12px;"><div align="center" style="font-size: 16px;font-weight: bold;color: #0685a8;">NON PRINT PAYMENT VOUCHER</div></td>
            <td align="right" width="40%" style="font-size: 12px;">
            <span style="font-size: 14px;"><?= h($nppayment->company->name) ?></span>
            <span><?= $this->Text->autoParagraph(h($nppayment->company->address)) ?></span>
			<span> <i class="fa fa-phone" aria-hidden="true"></i> <?= h($nppayment->company->landline_no) ?></span> |
            <?= h($nppayment->company->mobile_no) ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div style="border:solid 2px #0685a8;margin-bottom:5px;margin-top: 5px;"></div>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="50%" valign="top" align="left">
                <table>
					 <tr>
                        <td>Bank/Cash Account</td>
                        <td width="20" align="center">:</td>
                        <td><?= h($nppayment->BankCash->name) ?></td>
                    </tr>
                    <tr>
                        <td>Voucher No</td>
                        <td width="20" align="center">:</td>
                        <td><?= h('#'.str_pad($nppayment->voucher_no, 4, '0', STR_PAD_LEFT)) ?></td>
                    </tr>
                </table>
            </td>
            <td width="50%" valign="top" align="right">
                <table>
                    <tr>
                        <td>Transaction Date</td>
                        <td width="20" align="center">:</td>
                        <td><?= h(date("d-m-Y",strtotime($nppayment->transaction_date))) ?></td>
                    </tr>
					<tr>
						<td>Created On</td>
						<td width="20" align="center">:</td>
						<td><?= h(date("d-m-Y",strtotime($nppayment->created_on))) ?></td>
					</tr>
                </table>
            </td>
        </tr>
    </table>
	
    <br/>
    <table width="100%" class="table" style="font-size:12px">
        <tr>
            <th><?= __('Paid to') ?></th>
			<?php if($aval==1){ ?>
            <th><?= __('Grn/Invoice') ?></th>
			<?php } ?>
            <th><?= __('Amount') ?></th>
            <th><?= __('Narration') ?></th>
        </tr>
        <?php $total_cr=0; $total_dr=0; foreach ($nppayment->nppayment_rows as $nppayment_row): ?>
        <tr>
            <td style="white-space: nowrap;">
				<?php $name=""; if(empty($nppayment_row->ReceivedFrom->alias)){
				 echo $nppayment_row->ReceivedFrom->name;
				} else{
					 echo $nppayment_row->ReceivedFrom->name.'('; echo $nppayment_row->ReceivedFrom->alias.')'; 
				}?>
			</td>	
			<?php if($aval==1){ ?>
			<td style="white-space: nowrap;">
			<?php  if(!empty($nppayment_row->grn_ids)){ 
			 foreach($petty_cash_grn_data[@$nppayment_row->id] as $petty_cash_voucher_row1){
				echo $petty_cash_voucher_row1->grn1.'/GRN-'.str_pad($petty_cash_voucher_row1->grn2, 3, '0', STR_PAD_LEFT).'/'.$petty_cash_voucher_row1->grn3.'/'.$petty_cash_voucher_row1->grn4;
					echo "<br/>"; 
				}
			 } 
			 if(!empty($nppayment_row->invoice_ids)){ 
			 foreach($petty_cash_invoice_data[@$nppayment_row->id] as $petty_cash_voucher_row2){
				echo $petty_cash_voucher_row2->in1.'/IN-'.str_pad($petty_cash_voucher_row2->in2, 3, '0', STR_PAD_LEFT).'/'.$petty_cash_voucher_row2->in3.'/'.$petty_cash_voucher_row2->in4;
				echo "<br/>"; 
				}
			 }
			 ?></td>
			 <?php } ?>
            <td style="white-space: nowrap;"><?= h($this->Number->format($nppayment_row->amount,[ 'places' => 2])) ?> <?= h($nppayment_row->cr_dr) ?></td>
            <td><?= h($nppayment_row->narration) ?></td>
        </tr>
        <?php if($nppayment_row->cr_dr=="Cr"){
            $total_cr=$total_cr+$nppayment_row->amount;
        }else{
            $total_dr=$total_dr+$nppayment_row->amount;
        }
        $total=$total_dr-$total_cr; endforeach; ?>
    </table>
    
    
    
    <div style="border:solid 1px ;"></div>
    <table width="100%" class="divFooter">
        <tr>
            <td align="left" valign="top">
                <table>
                    <tr>
                        <td style="font-size: 16px;font-weight: bold;">
                        Rs: <?= h($this->Number->format($total,[ 'places' => 2])) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;">Rupees<?php echo ucwords($this->NumberWords->convert_number_to_words($total)) ?> Only </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;">
                        via <?= h($nppayment->payment_mode) ?> 
                        <?php if($nppayment->payment_mode=="Cheque"){
                            echo ' ('.$nppayment->cheque_no.')';
                        } ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td align="right" valign="top" width="35%">
                <table style="margin-top:3px;">
                    <tr>
                       <td width="15%" align="center"> 
                        <?php 
                         echo $this->Html->Image('/signatures/'.$nppayment->creator->signature,['height'=>'40px','style'=>'height:40px;']); 
                         ?></br>
                         </hr>
                         <span><b>Prepared By</b></span><br/>
                         <span><?= h($nppayment->company->name) ?></span><br/>
                        </td>
                    </tr>
                </table>
             </td>
        </tr>
    </table>
</div>

