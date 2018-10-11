<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GrossProfitReport Entity
 *
 * @property int $id
 * @property int $financial_year_id
 * @property int $invoice_id
 * @property int $invoice_row_id
 * @property float $taxable_value
 * @property float $inventory_ledger_cost
 * @property float $sales_price
 *
 * @property \App\Model\Entity\FinancialYear $financial_year
 * @property \App\Model\Entity\Invoice $invoice
 * @property \App\Model\Entity\InvoiceRow $invoice_row
 */
class GrossProfitReport extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
