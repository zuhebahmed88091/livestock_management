<?php

namespace App\Exports;

use App\Helpers\CommonHelper;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class CountryExport implements FromCollection, WithHeadings, WithEvents
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
            unset($item->id);
            return $item;
        });
        return $items;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Country Name',
            'Country Code',
            'Currency Code',
            'Capital',
            'Continent Name',
            'Continent Code',
            'Status'
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
