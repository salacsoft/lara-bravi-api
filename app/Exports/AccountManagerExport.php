<?php

namespace App\Exports;

use App\Models\AccountManager;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\WorkSheet\WorkSheet;

class AccountManagerExport implements FromQuery,WithHeadings,ShouldAutoSize,WithStyles
{
    use Exportable;
	protected $ids = [];

    protected $columns = ['uuid', 'account_code', 'account_pin','first_name','last_name', 'mobile_no', 'photo'];

	public function __construct(array $ids){
		$this->ids = $ids;
	}

    public function query(){
        // if distinct branch is selected
        $query  = AccountManager::query()->select($this->columns);
        if(count($this->ids)){
            $query->whereIn('id',$this->ids);
        }
        return $query->orderBy('first_name','asc');
    }

    public function styles(Worksheet $sheet){
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function headings() : array {
        return [
            'uuid',
            'account code',
            'account pin',
            'first_name',
            'last_name',
            'mobile_no',
            'photo'
        ];
    }
}
