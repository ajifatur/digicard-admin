<?php

namespace App\Exports;

use App\Transaksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class TransaksiExport implements FromView
{
	use Exportable;

    /**
     * Create a new message instance.
     *
     * int id transaksi
     * @return void
     */
    public function __construct($transaksi)
    {
        $this->transaksi = $transaksi;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	// View
    	return view('admin/transaksi/excel', [
    		'transaksi' => $this->transaksi
    	]);
    }
}
