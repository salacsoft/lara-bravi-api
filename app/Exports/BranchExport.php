<?php

namespace App\Exports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\WorkSheet\WorkSheet;

class BranchExport implements FromQuery,WithHeadings,ShouldAutoSize,WithStyles
{

	use Exportable;
	protected $ids = [];

	public function __construct(array $ids){
		$this->ids = $ids;
	}
    /**
    * @return \Illuminate\Support\Collection
    */

		public function query(){
			// if distinct branch is selected
			if(count($this->ids)){
				return Branch::query()->select('branch_name','branch_code', 'branch_address')->where('id',$this->ids)->orderBy('branch_name','asc');
			}
			return Branch::query()->select('branch_name','branch_code','branch_address')->orderBy('branch_name','asc');
		}

		public function styles(Worksheet $sheet){
			return [
				1 => ['font' => ['bold' => true]]
			];
		}

		public function headings() : array {
			return [
				'Branch Name',
				'Branch Code',
				'Branch Address',
			];
		}
}
