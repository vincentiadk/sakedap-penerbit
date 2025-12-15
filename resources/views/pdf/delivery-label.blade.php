<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>

    <style>
        @page {
            margin: 10mm;
            size: A4;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9pt;
            color: #000;
        }

        .section {
            margin-bottom: 12px;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .header {
            background-color: #023BAD;
            color: white;
            padding: 10px 15px;
            text-align: center;
            border-bottom: 3px solid #06732A;
        }

        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }

        .title-section {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 10px;
        }

        .header h1 {
            font-size: 14pt;
            margin: 0 0 3px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 9pt;
            margin: 0;
        }

        .document-type {
            background-color: #06732A;
            color: white;
            padding: 5px;
            margin-top: 6px;
            font-weight: bold;
            font-size: 9.5pt;
        }

        .info-grid {
            display: table;
            width: 100%;
            border: 2px solid #023BAD;
            background-color: white;
        }

        .info-column {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }

        .info-column.sender {
            border-right: 2px solid #023BAD;
            background-color: #eef6ff;
        }

        .info-column.receiver {
            background-color: #ecfff3;
        }

        .info-header {
            background-color: #023BAD;
            color: white;
            padding: 5px 8px;
            margin: -10px -10px 8px -10px;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-row {
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .info-label {
            color: #495057;
            font-weight: bold;
            font-size: 8pt;
        }

        .info-value {
            font-size: 8.5pt;
        }

        .highlight-value {
            background-color: #fff;
            padding: 3px 5px;
            border-left: 3px solid #06732A;
            margin-top: 2px;
            font-weight: bold;
        }

        .cut-line {
            text-align: center;
            margin: 15px 0;
        }

        .cut-line hr {
            border: none;
            border-top: 2px dashed #6c757d;
        }

        .cut-line-text {
            background: #fafafa;
            padding: 1px 12px;
            position: relative;
            top: -11.5px;
            font-weight: bold;
            color: #6c757d;
            border: 1px dashed #6c757d;
            font-size: 8pt;
        }

        .table-section {
            padding: 0 0 10px 0;
        }

        table.collection-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.collection-table thead {
            display: table-header-group;
        }

        table.collection-table th {
            background-color: #023BAD;
            color: white;
            padding: 3;
            border: 1px solid #023BAD;
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
        }

        table.collection-table td {
            border: 1px solid #cfd3d9;
            padding: 3px;
            font-size: 8pt;
        }

        .collection-table tbody tr:nth-child(even) {
            background-color: #f2f4f7;
        }

        .col-no {
            width: 4%;
            text-align: center;
            font-weight: bold;
            color: #023BAD;
        }

        .col-isbn {
            width: 18%;
            font-family: 'Courier New',
            monospace;
            font-weight: bold;
        }

        .col-title {
            width: 60%;
        }

        .col-qty {
            width: 10%;
            text-align: center;
            font-weight: bold;
            color: #06732A;
        }

        .total-row {
            background-color: #06732A;
            color: white;
            font-weight: bold;
        }

        .footer {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #eef1f5;
            border-top: 3px solid #023BAD;
        }

        .legal-notice {
            background-color: #fff3cd;
            border-left: 4px solid #06732A;
            padding: 6px 10px;
            margin-bottom: 10px;
            font-size: 8pt;
        }

        .signature-section {
            display: table;
            width: 100%;
            margin-top: 8px;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 6px;
        }

        .signature-box.left {
            border-right: 1px solid #cfd3d9;
        }

        .signature-label {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 3px;
            color: #023BAD;
        }

        .signature-space {
            height: 45px;
            border-bottom: 1px solid #000;
            margin: 6px 12px;
        }

        .signature-name {
            margin-top: 3px;
            font-size: 8pt;
        }
    </style>
</head>
<body>
    <div class="section avoid-break">
        <div class="header">
            <div class="header-top">
                <div class="title-section">
                    <h1>{{ $letter->BRANCH_NAME }}</h1>
                </div>
            </div>
            <div class="document-type">
                LABEL RESI PENGIRIMAN KOLEKSI FISIK
            </div>
        </div>
    </div>
    <div class="section avoid-break">
        <div class="info-grid">
            <div class="info-column sender">
                <div class="info-header">DATA PENGIRIM</div>
                <div class="info-row">
                    <span class="info-label">Nama :</span>
                    <div class="info-value">{{ session('name') }}</div>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat Lengkap :</span>
                    <div class="info-value">{{ session('address') }}</div>
                </div>
                <div class="info-row">
                    <span class="info-label">Provinsi / Kode Pos :</span>
                    <div class="info-value">{{ session('province_name') }}, {{ session('postal_code') }}</div>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Telepon :</span>
                    <div class="info-value">{{ $letter->PHONE }}</div>
                </div>
            </div>
            <div class="info-column receiver">
                <div class="info-header">DATA PENERIMA</div>
                <div class="info-row">
                    <span class="info-label">Nama :</span>
                    <div class="info-value">{{ $letter->BRANCH_NAME }}</div>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat Lengkap :</span>
                    <div class="info-value">{{ $letter->BRANCH_ALAMAT }}</div>
                </div>

                <div class="info-row">
                    <span class="info-label">Provinsi / Kode Pos :</span>
                    <div class="info-value">{{ $letter->NAMAPROPINSI }}, {{ $letter->BRANCH_KODE_POS }}</div>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Pengiriman :</span>
                    <div class="info-value">{{ Carbon::parse($letter->LETTER_DATE ?: now())->isoFormat('D MMMM Y') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="section avoid-break">
        <div class="cut-line">
            <hr>
            <span class="cut-line-text">&#x2702; POTONG DI SINI - jika diperlukan</span>
        </div>
    </div>
    <div class="section">
        <div class="table-section">
            <table class="collection-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Cek</th>
                        <th>Identifier</th>
                        <th>Judul</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @if($letterDetail)
                        @php $total = 0; @endphp
                        @foreach($letterDetail as $key => $ld)
                            @php $total += $ld->COPY ?: 0; @endphp
                            <tr>
                                <td class="col-no">{{ $key + 1 }}</td>
                                <td class="col-no"></td>
                                <td class="col-isbn">{{ $ld->ISBN }}</td>
                                <td class="col-title">{{ $ld->TITLE }}</td>
                                <td class="col-qty">{{ $ld->COPY }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="4" style="text-align:right;">TOTAL KESELURUHAN EKSEMPLAR :</td>
                            <td style="text-align:center;">
                                <strong>{{ $total }}</strong>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" style="text-align:center;">Tidak ada data</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="section avoid-break">
        <div class="footer">
            <div class="legal-notice">
                <strong>DASAR HUKUM :</strong>
                Label resi ini berlaku sebagai bukti pengiriman sesuai UU No. 13 Tahun 2018.
            </div>
            <div class="signature-section">
                <div class="signature-box left">
                    <div class="signature-label">PETUGAS PENGIRIM</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">( _____________________________________ )</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">PETUGAS PENERIMA</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">( _____________________________________ )</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
