<table border="1">
	<thead>
		<tr>
			<td width="10" align="center"><strong>No.</strong></td>
			<td width="35" align="center"><strong>Nama</strong></td>
			<td width="25" align="center"><strong>Tag</strong></td>
			<td width="20" align="center"><strong>Tanggal</strong></td>
			<td width="20" align="center"><strong>Jam</strong></td>
			<td width="15" align="center"><strong>Jenis</strong></td>
			<td width="20" align="center"><strong>Transaksi</strong></td>
			<td width="0"></td>
		</tr>
	</thead>
	<tbody>
		@php $i = 1; @endphp
		@foreach($transaksi as $data)
		<tr>
			<td align="center">{{ $i }}</td>
			<td>{{ $data->nama_user }}</td>
			<td align="center">{{ $data->username }}</td>
			<td align="center">{{ date('d/m/Y', strtotime($data->waktu_transaksi)) }}</td>
			<td align="center">{{ date('H:i:s', strtotime($data->waktu_transaksi)) }}</td>
			<td align="center">{{ $data->jenis_transaksi == 1 ? 'Top Up' : 'TRX' }}</td>
			<td align="right">{{ number_format($data->nominal_transaksi,0,',',',') }}</td>
			<td>{{ $data->id_transaksi }}</td>
		</tr>
		@php $i++; @endphp
		@endforeach
	</tbody>
</table>