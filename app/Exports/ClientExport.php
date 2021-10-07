<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;


class ClientExport implements FromQuery
{
    // // use Exportable;
    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection()
    // {
    //     return Client::all();
    // }

    protected $ids = [];

    use Exportable;

    public function __construct(array $ids)
    {
        Log::info("uid----------");
        Log::info($ids);
        Log::info("uid----------");
        $this->ids = $ids;
    }

    public function query()
    {
        if ($this->ids) {
            return Client::whereIn("id", $this->ids)->orderBy("client_name", "asc");
        }
        return Client::orderBy("client_name", "asc");

    }


}
