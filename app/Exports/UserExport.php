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

class UserExport implements FromCollection, WithHeadings, WithEvents
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
        //dd($items);
        $items->transform(function($item) {
            unset($item->id);
            unset($item->email_verified_at);
            unset($item->password);
            unset($item->remember_token);
            unset($item->created_at);
            unset($item->updated_at);

            $item->country_id = optional($item->country)->country_name;
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
            'Name',
            'Email',
            'Country',
            'Phone',
            'Designation',
            'Company',
            'Gender',
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
