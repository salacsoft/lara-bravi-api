<?php

namespace App\Exports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\WorkSheet\WorkSheet;
use Illuminate\Support\Facades\Log;

class GroupExport implements FromQuery,WithHeadings,ShouldAutoSize,WithStyles
{

    use Exportable;
	protected $ids = [];

	public function __construct(array $ids){
		$this->ids = $ids;
	}

    public function query(){
        // if distinct branch is selected
        if(count($this->ids)){
            Log::info("selected ids");
            Log::info($this->ids);
            return Group::query()->select('uuid','group_name')->whereIn('id',$this->ids)->orderBy('group_name','asc');
        }
        Log::info("all");
        return Group::query()->select('uuid','group_name')->orderBy('group_name','asc');
    }

    public function styles(Worksheet $sheet){
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function headings() : array {
        return [
            'Group UUID',
            'Group Name'
        ];
    }
}
