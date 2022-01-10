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
    protected $columns = ['branches.uuid','branch_name','branch_code', 'branch_address', 'client_uuid', 'clients.client_name'];

	public function __construct(array $ids){
		$this->ids = $ids;
	}
    /**
    * @return \Illuminate\Support\Collection
    */

		public function query(){
			// if distinct branch is selected
            $query = Branch::query()->select($this->columns)->join("clients", "clients.uuid", "branches.client_uuid");
			if(count($this->ids)){
				$query->whereIn('branches.id',$this->ids);
			}
			return $query->orderBy('branch_name','asc');
		}

		public function styles(Worksheet $sheet){
			return [
				1 => ['font' => ['bold' => true]]
			];
		}

		public function headings() : array {
			return [
                'UUID',
				'Branch Name',
				'Branch Code',
				'Branch Address',
                'Client UUID',
                'Client Name'
			];
		}
}
