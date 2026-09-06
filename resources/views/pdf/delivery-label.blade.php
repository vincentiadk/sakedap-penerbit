<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>{{ $title }}</title>

    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            color: #000;
        }

        /* =========================================================
           UMUM
        ========================================================== */

        .page-break {
            page-break-before: always;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        /* =========================================================
           HALAMAN 1 - LABEL
        ========================================================== */

        .label-header {
            border: 2px solid #000;
            padding: 12px 15px;
            text-align: center;
            margin-bottom: 15px;
        }

        .label-header h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .label-document-type {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #000;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
            border: 2px solid #000;
        }

        .info-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 12px;
        }

        .info-column.sender {
            border-right: 2px solid #000;
        }

        .info-header {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 7px;
            margin-bottom: 12px;
        }

        .info-row {
            margin-bottom: 11px;
            line-height: 1.45;
        }

        .info-label {
            display: block;
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 10pt;
        }

        .label-note {
            margin-top: 12px;
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 8pt;
            line-height: 1.4;
        }

        /* =========================================================
           HALAMAN 2 - SURAT PENGANTAR
        ========================================================== */

        .letter-header {
            text-align: center;
            margin-bottom: 18px;
        }

        .letter-header h1 {
            margin: 0 0 6px 0;
            font-size: 15pt;
            text-decoration: underline;
            font-weight: bold;
        }

        .letter-number {
            font-size: 10pt;
            margin-bottom: 5px;
        }

        .letter-meta {
            width: 100%;
            margin-bottom: 15px;
            font-size: 9pt;
        }

        .letter-meta td {
            border: none;
            padding: 2px 0;
            vertical-align: top;
        }

        .letter-meta-label {
            width: 110px;
        }

        .letter-opening {
            margin-bottom: 12px;
            line-height: 1.5;
            text-align: justify;
        }

        /* =========================================================
           TABEL KOLEKSI
        ========================================================== */

        table.collection-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.collection-table thead {
            display: table-header-group;
        }

        table.collection-table th {
            border: 1px solid #000;
            padding: 5px 4px;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
            font-weight: bold;
            background: #fff;
            color: #000;
        }

        table.collection-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            vertical-align: top;
            font-size: 8pt;
            line-height: 1.35;
        }

        table.collection-table tr {
            page-break-inside: avoid;
        }

        .col-no {
            width: 5%;
            text-align: center;
        }

        .col-identifier {
            width: 22%;
        }

        .col-year {
            width: 10%;
            text-align: center;
        }

        .col-media {
            width: 12%;
            text-align: center;
        }

        .col-qty {
            width: 7%;
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            border-top: 2px solid #000;
        }

        /* =========================================================
           BAGIAN BAWAH SURAT
        ========================================================== */

        .letter-closing {
            margin-top: 15px;
            line-height: 1.5;
            font-size: 9pt;
            text-align: justify;
        }

        .legal-notice {
            margin-top: 15px;
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 8pt;
            line-height: 1.4;
        }

        .signature-section {
            display: table;
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 5px 15px;
        }

        .signature-label {
            font-size: 9pt;
            margin-bottom: 5px;
        }

        .signature-space {
            height: 55px;
        }

        .signature-name {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 180px;
            height: 15px;
        }
    </style>
</head>

<body>

    {{-- =========================================================
         HALAMAN 1
         LABEL PENGIRIMAN
    ========================================================== --}}

    <div class="label-header">

        <h1>
            {{ $letter->BRANCH_NAME }}
        </h1>

        <div class="label-document-type">
            Label Pengiriman Koleksi Fisik
        </div>

    </div>


    <div class="info-grid">

        {{-- =====================================================
             DATA PENGIRIM
        ====================================================== --}}

        <div class="info-column sender">

            <div class="info-header">
                Data Pengirim
            </div>


            <div class="info-row">

                <span class="info-label">
                    Nama Pengirim
                </span>

                <div class="info-value">
                    {{ session('name') }}
                </div>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Alamat Lengkap
                </span>

                <div class="info-value">
                    {{ session('address') }}
                </div>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Provinsi / Kode Pos
                </span>

                <div class="info-value">
                    {{ session('province_name') }},
                    {{ session('postal_code') }}
                </div>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Nomor Telepon
                </span>

                <div class="info-value">
                    {{ $letter->PHONE }}
                </div>

            </div>

        </div>


        {{-- =====================================================
             DATA PENERIMA
        ====================================================== --}}

        <div class="info-column">

            <div class="info-header">
                Data Penerima
            </div>


            <div class="info-row">

                <span class="info-label">
                    Nama Penerima
                </span>

                <div class="info-value">
                    {{ $letter->BRANCH_NAME }}
                </div>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Alamat Lengkap
                </span>

                <div class="info-value">
                    {{ $letter->BRANCH_ALAMAT }}
                </div>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Provinsi / Kode Pos
                </span>

                <div class="info-value">
                    {{ $letter->NAMAPROPINSI }},
                    {{ $letter->BRANCH_KODE_POS }}
                </div>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Tanggal Pengiriman
                </span>

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


    <div class="label-note">

        <strong>Dokumen Pengiriman Koleksi</strong><br>

        Harap tempelkan label ini pada bagian luar paket.
        Surat pengantar dan daftar koleksi terdapat pada halaman berikutnya.

    </div>



    {{-- =========================================================
         HALAMAN 2
         SURAT PENGANTAR
    ========================================================== --}}

    <div class="page-break">


        <div class="letter-header">

            <h1>
                SURAT PENGANTAR
            </h1>

            <div class="letter-number">

                Nomor:
                <strong>
                    {{ $letter->LETTER_NUMBER ?? $letter->letter_number ?? '-' }}
                </strong>

            </div>

        </div>


        {{-- =====================================================
             INFORMASI SURAT
        ====================================================== --}}

        <table class="letter-meta">

            <tr>

                <td class="letter-meta-label">
                    Tanggal
                </td>

                <td style="width:15px;">
                    :
                </td>

                <td>
                    {{
                        Carbon::parse(
                            $letter->LETTER_DATE ?: now()
                        )->isoFormat('D MMMM Y')
                    }}
                </td>

            </tr>


            <tr>

                <td class="letter-meta-label">
                    Tujuan
                </td>

                <td>
                    :
                </td>

                <td>
                    {{ $letter->BRANCH_NAME }}
                </td>

            </tr>


            <tr>

                <td class="letter-meta-label">
                    Alamat
                </td>

                <td>
                    :
                </td>

                <td>
                    {{ $letter->BRANCH_ALAMAT }},
                    {{ $letter->NAMAPROPINSI }}
                    {{ $letter->BRANCH_KODE_POS }}
                </td>

            </tr>

        </table>


        {{-- =====================================================
             PENGANTAR
        ====================================================== --}}

        <div class="letter-opening">

            Bersama surat pengantar ini disampaikan koleksi fisik
            untuk pelaksanaan kewajiban serah simpan karya cetak
            sebagaimana daftar berikut:

        </div>


        {{-- =====================================================
             DAFTAR KOLEKSI
        ====================================================== --}}

        <table class="collection-table">

            <thead>

                <tr>

                    <th class="col-no">
                        No.
                    </th>

                    <th class="col-identifier">
                        Identifier
                    </th>

                    <th>
                        Judul
                    </th>

                    <th class="col-year">
                        Tahun Terbit
                    </th>

                    <th class="col-media">
                        Jenis Koleksi
                    </th>

                    <th class="col-qty">
                        Jml.
                    </th>

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


                            <td class="col-identifier">

                                @if($ld->ISBN)

                                    ISBN:
                                    {{ $ld->ISBN }}

                                    @if($ld->NOMORPANGGILJILID)
                                        {{ $ld->NOMORPANGGILJILID }}
                                    @endif


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

                                @if($ld->EDISI_SERIAL)
                                    {{ $ld->EDISI_SERIAL }}
                                @endif

                            </td>


                            <td class="col-year">
                                {{ $ld->PUBLISH_YEAR }}
                            </td>


                            <td class="col-media">
                                {{ $ld->JENIS_MEDIA }}
                            </td>


                            <td class="col-qty">
                                {{ $ld->COPY }}
                            </td>

                        </tr>

                    @endforeach


                    <tr class="total-row">

                        <td
                            colspan="5"
                            class="text-right"
                        >
                            TOTAL KESELURUHAN EKSEMPLAR
                        </td>

                        <td class="col-qty">
                            {{ $total }}
                        </td>

                    </tr>


                @else

                    <tr>

                        <td
                            colspan="6"
                            class="text-center"
                        >
                            Tidak ada data koleksi.
                        </td>

                    </tr>

                @endif

            </tbody>

        </table>


        {{-- =====================================================
             PENUTUP
        ====================================================== --}}

        <div class="letter-closing">

            Demikian surat pengantar ini disampaikan.
            Mohon koleksi dapat diterima dan diproses sesuai
            dengan ketentuan yang berlaku.

        </div>


        <div class="legal-notice">

            <strong>Dasar Hukum:</strong>
            Undang-Undang Nomor 13 Tahun 2018
            tentang Serah Simpan Karya Cetak dan Karya Rekam.

        </div>


        {{-- =====================================================
             TANDA TANGAN
        ====================================================== --}}

        <div class="signature-section">

            <div style="width: 55%; display: inline-block;"></div>

            <div style="
                width: 40%;
                display: inline-block;
                text-align: center;
                vertical-align: top;
            ">

                <div>
                    Hormat kami,
                </div>

                <div style="font-weight: bold; margin-top: 3px;">
                    Pimpinan Penerbit
                </div>

                <div style="font-weight: bold;">
                    {{ session('name') }}
                </div>

                <div style="height: 65px;"></div>

                <div style="
                    display: inline-block;
                    width: 190px;
                    border-bottom: 1px solid #000;
                "></div>

                <div style="font-size: 8pt; margin-top: 3px;">
                    Nama Jelas, Tanda Tangan, dan Stempel
                </div>

            </div>

        </div>


    </div>

</body>
</html>