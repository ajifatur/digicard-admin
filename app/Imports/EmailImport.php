<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EmailImport implements ToArray, WithStartRow
{		
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function array(array $array)
    {
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
