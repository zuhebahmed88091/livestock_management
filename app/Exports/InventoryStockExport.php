<?php

namespace App\Exports;

use App\Helpers\CommonHelper;
use App\Models\InventoryStock;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class InventoryStockExport implements FromCollection, WithHeadings, WithEvents
{
    use RegistersEventListeners;

    protected $query;

    function __construct($query) {
        $this->query = $query;
    }

    /**
    * @return Collection
    */
    public function collection()
    {
        $items = $this->query->get();
        $items->transform(function($item) {
            $this->setStockSummery($item);
            unset($item->id);
            unset($item->inventory_image);
            unset($item->inventory_type_id);
            unset($item->inventory_unit_id);
            unset($item->source);
            unset($item->warranty);
            unset($item->description);
            unset($item->instruction);
            unset($item->created_at);
            unset($item->updated_at);
            unset($item->created_by);

            return $item;
        });
        return $items;
    }

    function setStockSummery($inventory)
    {
        $queryAdd = InventoryStock::where('inventory_id', $inventory->id)->where('quantity', '>', 0);
        $queryConsume = InventoryStock::where('inventory_id', $inventory->id)->where('quantity', '<', 0);

        $unit = $inventory->inventoryUnit->title;
        $totalStockQuantity = $queryAdd->sum('quantity');
        $totalConsumeQuantity = $queryConsume->sum('quantity');
        $currentStockQuantity = $totalStockQuantity + $totalConsumeQuantity;

        $inventory->totalStockQuantity = $totalStockQuantity . ' ' . $unit;
        $inventory->totalConsumeQuantity = ($totalConsumeQuantity * -1) . ' ' . $unit;
        $inventory->currentStockQuantity = $currentStockQuantity . ' ' . $unit;
        $inventory->totalStockCost = $queryAdd->sum('cost');
        $inventory->addedBy = optional($inventory->creator)->name;

        return $inventory;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Inventory',
            'Total Amount',
            'Consume Amount',
            'Stock Amount',
            'Total Cost',
            'Created by'
        ];
    }

    /**
     * @param AfterSheet $event
     * @throws Exception
     */
    public static function afterSheet(AfterSheet $event)
    {
        CommonHelper::setUpExcelSheet($event);
    }
}
