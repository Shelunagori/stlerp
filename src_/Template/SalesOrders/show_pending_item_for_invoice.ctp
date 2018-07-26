<?php //pr($pettyCashReceiptVouchers); exit; ?>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-globe font-blue-steel"></i>
            <span class="caption-subject font-blue-steel uppercase">Pending Sales Order Quantity For Invoice</span>
        </div>
		
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12">
                <?php $page_no=$this->Paginator->current('CreditNotes'); $page_no=($page_no-1)*20; ?>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Item Name</th>
                            <th>Current Stock</th>
                            <th>SO Quantity</th>
						</tr>
                    </thead>
                    <tbody>
                      <?php $i=1; foreach($itemSoQty as $key=>$data){ ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo @$itemName[$key]; ?></td>
                            <td><?php echo @$Current_Stock[$key]; ?></td>
                            <td><?php echo @$itemSoQty[$key]; ?></td>
                            
                            
                        </tr>
					  <?php } ?>
                    </tbody>
                </table>
                <div class="paginator">
                    
                </div>
            </div>
        </div>
    </div>
</div>
