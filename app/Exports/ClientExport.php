<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class ClientExport  extends DefaultValueBinder implements FromQuery, ShouldAutoSize, WithHeadings, WithCustomValueBinder
{

    use Exportable;

    protected $ids = [];
    protected $columns = ["client_code", "client_name", "client_address"];


    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function query()
    {
        $query = Client::query()->select($this->columns);
        if ($this->ids) {
            $query->whereIn("id", $this->ids);
        }
        return $query->orderBy("client_name", "asc");

    }

    public function bindValue(Cell $cell, $value)
    {
        $cell->setValueExplicit($value, DataType::TYPE_STRING);
        return true;
    }


    public function headings(): array
    {
        return ["client code", "Client name", "client Address"];
    }



}
