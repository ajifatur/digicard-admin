<?php

namespace App\Imports;

// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Contracts\Queue\ShouldQueue; //IMPORT SHOUDLQUEUE
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Transaksi;
use App\User;

class TransaksiImport implements ToModel, WithStartRow
{		
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function array(array $array)
    // {
    // }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Mengecek data transaksi berdasarkan id
        $transaksi = Transaksi::find($row[7]);

        // Jika data transaksi tidak ditemukan
        if(!$transaksi){
            // Konversi format tanggal
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]);
            $date = (array)$date;

            // Konversi format jam
            $time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]);
            $time = (array)$time;
            
            // Tambah data transaksi
            $transaksi = new Transaksi;
            $transaksi->waktu_transaksi = date('Y-m-d', strtotime($date['date']))." ".date('H:i:s', strtotime($time['date']));
            $transaksi->nominal_transaksi = str_replace(",", ".", $row[6]);
            $transaksi->transaksi_at = date('Y-m-d H:i:s');
        }
        else{
            $transaksi->waktu_transaksi = generate_date_format($row[3], 'y-m-d')." ".$row[4];
            $transaksi->nominal_transaksi = str_replace(".", "", str_replace(",", "", $row[6]));
        }
        
        // Menyimpan atau mengupdate transaksi
        $user = User::where('username','=',$row[2])->first();
        
        $transaksi->id_user = $user->id_user;
        $transaksi->jenis_transaksi = $row[5] == 'Top Up' || $row[5] == 'Top Up ' ? 1 : 2;
        $transaksi->save();
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
	
    //LIMIT CHUNKSIZE
    public function chunkSize(): int
    {
        return 1000; //ANGKA TERSEBUT PERTANDA JUMLAH BARIS YANG AKAN DIEKSEKUSI
    }
}
