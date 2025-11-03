<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pegawai</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #444; padding: 6px 8px; text-align: left; }
        th { background-color: #eaeaea; font-weight: bold; }
        .kop { text-align: center; }
        .garis { border-bottom: 2px solid #000; margin: 6px 0 12px 0; }
        .footer { margin-top: 40px; text-align: right; }
        .footer .ttd { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

<div class="kop">
    <h1>CREDIT UNION SEJAHTERA BERSAMA</h1>
    <p>Jl. Contoh Alamat No. 123, Kota, Provinsi</p>
    <p>Telp: (000) 000000 | Email: info@creditunion.com</p>
</div>

<div class="garis"></div>

<h3 style="text-align:center; margin-bottom:0;">LAPORAN DATA PEGAWAI</h3>
<p style="text-align:center; margin-top:2px;">Per {{ date('d F Y') }}</p>

<table>
    <thead>
        <tr>
            <th>NIP</th>
            <th>Nama Lengkap</th>
            <th>Jabatan</th>
            <th>Departemen</th>
            <th>Status</th>
            <th>No. HP</th>
        </tr>
    </thead>

    <tbody>
    @foreach($data as $p)
        <tr>
            <td>{{ $p->nip }}</td>
            <td>{{ $p->nama_lengkap }}</td>
            <td>{{ $p->jabatan }}</td>
            <td>{{ $p->departemen ?: '-' }}</td>
            <td>{{ $p->status_kepegawaian }}</td>
            <td>{{ $p->no_hp ?: '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<br><br>

<h4>Rekap Pegawai Berdasarkan Status</h4>
<table>
    <thead>
        <tr>
            <th>Status Kepegawaian</th>
            <th>Jumlah Pegawai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapStatus as $status => $jumlah)
        <tr>
            <td>{{ $status }}</td>
            <td>{{ $jumlah }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>

<h4>Rekap Pegawai Berdasarkan Departemen</h4>
<table>
    <thead>
        <tr>
            <th>Departemen</th>
            <th>Jumlah Pegawai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapDepartemen as $departemen => $jumlah)
        <tr>
            <td>{{ $departemen ?: 'Tidak Ditentukan' }}</td>
            <td>{{ $jumlah }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>{{ date('d F Y') }}</p>
    <p>Manajer SDM</p>
    <p class="ttd">_________________________</p>
</div>

</body>
</html>
