<?php

namespace App\Exports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\WorkSheet\WorkSheet;

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
            return Group::query()->select('uuid','group_name')->where('id',$this->ids)->orderBy('group_name','asc');
        }
        return Group::query()->select('uuid','branch_name')->orderBy('group_name','asc');
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
