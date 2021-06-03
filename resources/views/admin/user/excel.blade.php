<table border="1">
	<thead>
		<tr>
			<td width="5" align="center"><strong>No.</strong></td>
			<td width="25" align="center"><strong>Nama</strong></td>
			<td width="25" align="center"><strong>Tag</strong></td>
			<td width="25" align="center"><strong>Email</strong></td>
			<td width="25" align="center"><strong>Nomor HP</strong></td>
			<td width="25" align="center"><strong>Tanggal Lahir</strong></td>
			<td width="25" align="center"><strong>Jenis Kelamin</strong></td>
			<td width="25" align="center"><strong>Pekerjaan</strong></td>
			<td width="0"></td>
		</tr>
	</thead>
	<tbody>
		@php $i = 1; @endphp
		@foreach($users as $user)
		<tr>
			<td align="center">{{ $i }}</td>
			<td>{{ $user->nama_user }}</td>
			<td align="center">{{ $user->username }}</td>
			<td>{{ $user->email }}</td>
			<td align="center">{{ $user->nomor_hp }}</td>
			<td align="center">{{ date('d/m/Y', strtotime($user->tanggal_lahir)) }}</td>
			<td align="center">{{ $user->jenis_kelamin }}</td>
			<td>{{ $user->nama_pekerjaan }}</td>
			<td>{{ $user->id_user }}</td>
		</tr>
		@php $i++; @endphp
		@endforeach
	</tbody>
</table>