<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>

    <style>
        @page {
            margin: 12mm;
            size: A4;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            color: #000;
        }

        .page-label {
            width: 100%;
        }

        .page-attachment {
            page-break-before: always;
        }

        .header {
            border: 2px solid #000;
            padding: 12px 15px;
            text-align: center;
            margin-bottom: 14px;
        }

        .header h1 {
            font-size: 16pt;
            margin: 0 0 6px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .document-type {
            border-top: 1px solid #000;
            padding-top: 7px;
            margin-top: 7px;
            font-weight: bold;
            font-size: 11pt;
        }

        .info-grid {
            display: table;
            width: 100%;
            border: 2px solid #000;
            table-layout: fixed;
        }

        .info-column {
            display: table-cell;
            width: 50%;
            padding: 12px;
            vertical-align: top;
        }

        .info-column.sender {
            border-right: 2px solid #000;
        }

        .info-header {
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }

        .info-row {
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .info-label {
            display: block;
            font-weight: bold;
            font-size: 8.5pt;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 10pt;
        }

        .attachment-title {
            text-align: center;
            margin-bottom: 14px;
        }

        .attachment-title h2 {
            font-size: 14pt;
            margin: 0 0 4px 0;
        }

        .attachment-title div {
            font-size: 9pt;
        }

        table.collection-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.collection-table thead {
            display: table-header-group;
        }

        table.collection-table th {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 8.5pt;
            font-weight: bold;
            background: #fff;
            color: #000;
        }

        table.collection-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 8pt;
            vertical-align: top;
        }

        table.collection-table tr {
            page-break-inside: avoid;
        }

        .col-no,
        .col-qty {
            text-align: center;
        }

        .col-isbn {
            font-family: 'Courier New', monospace;
        }

        .total-row td {
            font-weight: bold;
            border-top: 2px solid #000;
        }

        .footer {
            margin-top: 15px;
            border: 1px solid #000;
            padding: 10px;
        }

        .legal-notice {
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            font-size: 8pt;
        }

        .signature-section {
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 8px;
        }

        .signature-box.left {
            border-right: 1px solid #000;
        }

        .signature-label {
            font-weight: bold;
            font-size: 9pt;
        }

        .signature-space {
            height: 45px;
            border-bottom: 1px solid #000;
            margin: 8px 15px;
        }

        .signature-name {
            font-size: 8pt;
        }
    </style>
</head>
<body>

    {{-- =========================================================
         HALAMAN 1 - LABEL PENGIRIMAN
    ========================================================== --}}
    <div class="page-label">

        <div class="header">
            <h1>{{ $letter->BRANCH_NAME }}</h1>

            <div class="document-type">
                LABEL PENGIRIMAN KOLEKSI FISIK
            </div>
        </div>

        <div class="info-grid">

            {{-- PENGIRIM --}}
            <div class="info-column sender">

                <div class="info-header">
                    DATA PENGIRIM
                </div>

                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <div class="info-value">
                        {{ session('name') }}
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-label">Alamat Lengkap</span>
                    <div class="info-value">
                        {{ session('address') }}
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-label">Provinsi / Kode Pos</span>
                    <div class="info-value">
                        {{ session('province_name') }},
                        {{ session('postal_code') }}
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-label">No. Telepon</span>
                    <div class="info-value">
                        {{ $letter->PHONE }}
                    </div>
                </div>

            </div>


            {{-- PENERIMA --}}
            <div class="info-column receiver">

                <div class="info-header">
                    DATA PENERIMA
                </div>

                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <div class="info-value">
                        {{ $letter->BRANCH_NAME }}
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-label">Alamat Lengkap</span>
                    <div class="info-value">
                        {{ $letter->BRANCH_ALAMAT }}
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-label">Provinsi / Kode Pos</span>
                    <div class="info-value">
                        {{ $letter->NAMAPROPINSI }},
                        {{ $letter->BRANCH_KODE_POS }}
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-label">Tanggal Pengiriman</span>
                    <div class="info-value">
                        {{
                            Carbon::parse(
                                $letter->LETTER_DATE ?: now()
                            )->isoFormat('D MMMM Y')
                        }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         HALAMAN 2 - LAMPIRAN KOLEKSI
    ========================================================== --}}
    <div class="page-attachment">

        <div class="attachment-title">
            <h2>LAMPIRAN DAFTAR KOLEKSI</h2>

            <div>
                Label Pengiriman No. {{ $letter->LETTER_ID ?? $letter->letter_id ?? '-' }}
            </div>
        </div>

        <table class="collection-table">

            <thead>
                <tr>
                    <th style="width:5%">No.</th>
                    <th style="width:22%">Identifier</th>
                    <th>Judul</th>
                    <th style="width:10%">Th. Terbit</th>
                    <th style="width:12%">Jenis</th>
                    <th style="width:7%">Jml</th>
                </tr>
            </thead>

            <tbody>

                @if($letterDetail)

                    @php
                        $total = 0;
                    @endphp

                    @foreach($letterDetail as $key => $ld)

                        @php
                            $total += $ld->COPY ?: 0;
                        @endphp

                        <tr>

                            <td class="col-no">
                                {{ $key + 1 }}
                            </td>

                            <td class="col-isbn">

                                @if($ld->ISBN)

                                    ISBN:
                                    {{ $ld->ISBN }}
                                    {{ $ld->NOMORPANGGILJILID }}

                                @elseif($ld->QRCBN)

                                    QRCBN:
                                    {{ $ld->QRCBN }}

                                @elseif($ld->ISSN)

                                    ISSN:
                                    {{ $ld->ISSN }}

                                @elseif($ld->ISMN)

                                    ISMN:
                                    {{ $ld->ISMN }}

                                @elseif($ld->ISRC)

                                    ISRC:
                                    {{ $ld->ISRC }}

                                @else

                                    -

                                @endif

                            </td>

                            <td>
                                {{ $ld->TITLE }}
                                {{ $ld->EDISI_SERIAL }}
                            </td>

                            <td class="col-no">
                                {{ $ld->PUBLISH_YEAR }}
                            </td>

                            <td>
                                {{ $ld->JENIS_MEDIA }}
                            </td>

                            <td class="col-qty">
                                {{ $ld->COPY }}
                            </td>

                        </tr>

                    @endforeach

                    <tr class="total-row">

                        <td colspan="5" style="text-align:right;">
                            TOTAL KESELURUHAN EKSEMPLAR
                        </td>

                        <td class="col-qty">
                            {{ $total }}
                        </td>

                    </tr>

                @else

                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Tidak ada data
                        </td>
                    </tr>

                @endif

            </tbody>

        </table>


        <div class="footer">

            <div class="legal-notice">
                <strong>DASAR HUKUM:</strong>
                Label pengiriman ini merupakan dokumen pengiriman
                koleksi dalam pelaksanaan serah simpan sesuai
                UU Nomor 13 Tahun 2018.
            </div>

            <div class="signature-section">

                <div class="signature-box left">

                    <div class="signature-label">
                        PETUGAS PENGIRIM
                    </div>

                    <div class="signature-space"></div>

                    <div class="signature-name">
                        ( _________________________________ )
                    </div>

                </div>

                <div class="signature-box">

                    <div class="signature-label">
                        PETUGAS PENERIMA
                    </div>

                    <div class="signature-space"></div>

                    <div class="signature-name">
                        ( _________________________________ )
                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>
