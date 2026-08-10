<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 1cm 1.5cm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-gray {
            color: #718096;
        }

        .text-red {
            color: #e53e3e;
        }

        /* Judul Dokumen */
        .pdf-header {
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .identity-container {
            width: 100%;
            position: relative;
            margin-bottom: 10px;
        }

        /* Foto Box: Dibuat pas dengan aspek rasio 3x4 */
        .foto-box {
            width: 100px;
            height: 130px;
            border: 1px solid #cbd5e0;
            position: absolute;
            left: 0;
            top: 0;
            background-color: #f7fafc;
            overflow: hidden;
        }

        /* Fix: Menggunakan width: 100% tanpa object-fit agar tidak ditarik jika aspek rasio benar */
        .foto-box img {
            width: 100%;
            height: auto;
            min-height: 100%;
        }

        /* Name Box: Menggunakan padding-left sebagai ganti margin agar rata kiri absolut */
        .name-box {
            padding-left: 115px;
            min-height: 130px;
        }

        .name-box h1 {
            margin: 0;
            font-size: 18px;
            color: #1a202c;
            line-height: 1;
        }

        .table-info {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Kunci agar simetris */
            margin-bottom: 10px;
        }

        .table-info td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 11px; /* Ukuran standar PDF agar tidak meluber */
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #2d3748;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #e6f2ff;
            padding: 4px 8px;
            margin-top: 10px;
            margin-bottom: 10px;
            border-left: 4px solid #dc2626;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .data-table th {
            background-color: #f7fafc;
            color: #4a5568;
            font-size: 11px;
            text-align: left;
            padding: 5px;
            border: 1px solid #edf2f7;
        }

        .data-table th.nilai {
           text-align: center;
        }

        .data-table td {
            padding: 5px;
            border: 1px solid #edf2f7;
            font-size: 11px;
        }

        .card-pengalaman {
            border: 1px solid #e2e8f0;
            padding: 8px;
            margin-bottom: 6px;
        }

        .badge {
            padding: 1px 5px;
            font-size: 9px;
            border-radius: 3px;
            background: #e53e3e;
            color: white;
            display: inline-block;
            vertical-align: middle;
            margin-top: -4px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Penyeragaman Lebar Kolom */
    .col-label { width: 18%; }
    .col-isi   { width: 32%; }
    
    .text-gray { color: #4a5568; }
    .text-red { color: #dc2626; }
    .font-bold { font-weight: bold; }
    </style>
</head>

<body>

    <div class="pdf-header">
        @php
            // Persiapan Logo Kiri
            $logo_left_path = public_path('assets/images/SGS Logo-Color.webp');
            $logo_left_base = base64_encode(file_get_contents($logo_left_path));

            // Persiapan Logo Kanan (Sesuaikan path-nya)
            $logo_right_path = public_path('assets/images/Logo Rekso.webp');
            $logo_right_base = file_exists($logo_right_path)
                ? base64_encode(file_get_contents($logo_right_path))
                : null;
        @endphp

        <table class="header-table">
            <tr>
                <td width="25%">
                    <img src="data:image/webp;base64,{{ $logo_left_base }}" style="width: 120px; height: auto;">
                </td>

                <td width="50%" class="text-center">
                    <h2 style="margin:0; font-size: 16px;">BIODATA CALON KARYAWAN</h2>
                </td>

                <td width="25%" style="text-align: right;">
                    @if ($logo_right_base)
                        <img src="data:image/webp;base64,{{ $logo_right_base }}" style="width: 50px; height: auto;">
                    @else
                        <div style="font-size: 7px; color: #ccc;">LOGO KANAN</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="identity-container clearfix">
        <div class="foto-box">
            @php $docsByType = $applicant->documents->keyBy('type'); @endphp
            @if ($docsByType->get('foto'))
                @php
                    $path = public_path('storage/' . $docsByType->get('foto')->file_path);
                    if (file_exists($path)) {
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    } else {
                        $base64 = null;
                    }
                @endphp
                @if ($base64)
                    <img src="{{ $base64 }}">
                @else
                    <div style="text-align:center; padding-top:55px; color:#ccc; font-size:8px;">FOTO KOSONG</div>
                @endif
            @else
                <div style="text-align:center; padding-top:55px; color:#ccc; font-size:8px;">FOTO KOSONG</div>
            @endif
        </div>

        <div class="name-box">
            <h1 class="text-uppercase">
                {{ $applicant->user->name }}
                @if (strtolower($applicant->profile->ex_employee) == 'ya')
                    <span class="badge">EX-EMPLOYEE</span>
                @endif
            </h1>
            <p class="text-red font-bold" style="margin: 5px 0;">
                <span style="color:#4a5568">Phone:</span> {{ $applicant->profile->phone }} | <span
                    style="color:#4a5568">Email:</span> {{ $applicant->user->email }}
            </p>

            <table class="table-info">
                <tr>
                    <td width="20%" class="text-gray font-bold">Social Media</td>
                    <td>: IG: {{ $applicant->profile->instagram ?? '-' }} | LinkedIn:
                        {{ $applicant->profile->linkedin ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-gray font-bold">Alamat KTP</td>
                    <td>: {{ $applicant->profile->alamat }}</td>
                </tr>
                <tr>
                    <td class="text-gray font-bold">Domisili</td>
                    <td>: {{ $applicant->profile->domisili ?? $applicant->profile->alamat }}</td>
                </tr>
                <tr>
                    <td class="text-gray font-bold">Melamar Posisi</td>
                    <td>:<span class="font-bold"> {{ $application->lowongan->judul_lowongan ?? '-' }} </span></td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Section Informasi Personal & Fisik --}}
    <div class="section-title">Informasi Personal & Fisik</div>
    <table class="table-info">
        <tr>
            <td class="col-label text-gray font-bold">Jenis Kelamin</td>
            <td class="col-isi">: {{ $applicant->profile->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td class="col-label text-gray font-bold">NIK</td>
            <td class="col-isi">: {{ $applicant->profile->nik }}</td>
        </tr>
        <tr>
            <td class="col-label text-gray font-bold">Agama</td>
            <td class="col-isi">: {{ $applicant->profile->agama }}</td>
            <td class="col-label text-gray font-bold">Status</td>
            <td class="col-isi">: {{ $applicant->profile->status_nikah }}</td>
        </tr>
        <tr>
            <td class="col-label text-gray font-bold">Tempat, Tanggal Lahir</td>
            <td class="col-isi">: {{ $applicant->profile->tempat_lahir }}, {{ $applicant->profile->tanggal_lahir?->format('d/m/Y') }}</td>
            <td class="col-label text-gray font-bold">SIM</td>
            <td class="col-isi">: {{ is_array($applicant->profile->jenis_sim) ? implode(', ', $applicant->profile->jenis_sim) : ($applicant->profile->jenis_sim ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label text-gray font-bold">Fisik (TB/BB)</td>
            <td class="col-isi">: {{ $applicant->profile->tinggi_badan }} cm / {{ $applicant->profile->berat_badan }} kg</td>
            <td class="col-label text-gray font-bold">Perokok</td>
            <td class="col-isi">: 
                <span class="{{ strtolower($applicant->profile->perokok) == 'ya' ? 'text-red font-bold' : '' }}">
                    {{ ucfirst($applicant->profile->perokok ?? '-') }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="col-label text-gray font-bold">Bertato</td>
            <td class="col-isi">: 
                <span class="{{ strtolower($applicant->profile->bertato) == 'ya' ? 'text-red font-bold' : '' }}">
                    {{ ucfirst($applicant->profile->bertato ?? '-') }}
                </span>
            </td>
            <td class="col-label text-gray font-bold">Penyakit</td>
            <td class="col-isi">: {{ $applicant->profile->penyakit ?? 'Tidak Ada' }}</td>
        </tr>
        <tr>
            <td class="col-label text-gray font-bold">Ex-Karyawan</td>
            <td colspan="3">: 
                @if(strtolower($applicant->profile->ex_employee) == 'ya')
                    <span class="text-red font-bold">YA</span> 
                    <span style="color: #4a5568;">
                        (<strong>{{ $applicant->profile->ex_company_name ?? '-' }}</strong> | <strong>{{ $applicant->profile->ex_last_position ?? '-' }}</strong>)
                    </span>
                @else
                    Tidak
                @endif
            </td>
        </tr>
    </table>

    {{-- Section Pendidikan Formal --}}
    <div class="section-title">Pendidikan Formal</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Jenjang</th>
                <th>Instansi</th>
                <th>Jurusan</th>
                <th width="12%" class="text-center">Tahun Masuk</th>
                <th width="12%" class="text-center">Tahun Lulus</th>
                <th width="10%" class="text-center nilai">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applicant->profile->formalEducations as $edu)
                @php
                    $masihSekolah = !$edu->tahun_lulus || $edu->is_current_edu;
                @endphp
                <tr>
                    <td class="font-bold">{{ $edu->jenjang ?? '-' }}</td>
                    <td>{{ $edu->sekolah ?? '-' }}</td>
                    <td style="font-style: italic; color: #4a5568;">{{ $edu->jurusan ?? '-' }}</td>
                    <td class="text-center">{{ $edu->tahun_masuk ?? '-' }}</td>
                    <td class="text-center">
                        @if($masihSekolah)
                            <span style="font-size: 8px; color: #2b6cb0; font-weight: bold; text-transform: uppercase;">
                                Sedang Ditempuh
                            </span>
                        @else
                            {{ $edu->tahun_lulus ?? '-' }}
                        @endif
                    </td>
                    <td class="text-center font-bold">{{ $edu->nilai ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray" style="font-style: italic;">Data pendidikan belum diisi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Section Pendidikan Informal --}}
    <div class="section-title">Pendidikan Informal (Pelatihan & Kursus)</div>
    @if ($applicant->profile->informalEducations->isNotEmpty())
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Kursus/Sertifikasi</th>
                    <th>Penyelenggara</th>
                    <th>Tahun</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applicant->profile->informalEducations as $info)
                    <tr>
                        <td>{{ $info->kursus }}</td>
                        <td>{{ $info->penyelenggara }}</td>
                        <td>{{ $info->tahun }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray" style="font-style: italic;">Tidak ada riwayat pelatihan/kursus.</p>
    @endif

    {{-- Section Pengalaman Kerja --}}
    <div class="section-title">Pengalaman Kerja</div>
    @forelse($applicant->profile->workExperiences as $job)
        <div style="background-color: #f8fafc; border: 1px solid #edf2f7; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
            <table width="100%" style="border-bottom: 1px solid #e2e8f0; margin-bottom: 8px; padding-bottom: 5px;">
                <tr>
                    <td>
                        <div style="font-weight: bold; font-size: 11px; text-transform: uppercase; color: #1a202c;">
                            {{ $job->perusahaan ?? '-' }}
                        </div>
                        <div style="font-size: 9px; font-weight: bold; color: #e53e3e; text-transform: uppercase;">
                            {{ $job->jabatan ?? '-' }}
                            <span style="color: #797a7b; font-style: italic; font-weight: normal;">
                                • {{ $job->divisi ?? '-' }}
                            </span>
                        </div>
                    </td>
                    <td align="right" valign="top">
                        <span style="font-size: 9px; font-weight: bold; background-color: #ffffff; padding: 2px 5px; border: 1px solid #edf2f7;">
                            {{ $job->tanggal_masuk?->format('M Y') ?? '?' }}
                            -
                            @if($job->masih_bekerja)
                                <span style="color: #38a169;">Sekarang</span>
                            @else
                                {{ $job->tanggal_keluar?->format('M Y') ?? '?' }}
                            @endif
                        </span>
                    </td>
                </tr>
            </table>

            <table width="100%" style="font-size: 9px;">
                <tr>
                    <td width="25%">
                        <div style="color: #797a7b; font-weight: bold; text-transform: uppercase; font-size: 7px;">Gaji Terakhir</div>
                        <div style="color: #414b5c; font-weight: bold;">{{ $job->gaji ?? '-' }}</div>
                    </td>
                    <td width="25%">
                        <div style="color: #797a7b; font-weight: bold; text-transform: uppercase; font-size: 7px;">Fasilitas</div>
                        <div style="color: #414b5c;">{{ $job->fasilitas ?? '-' }}</div>
                    </td>
                    <td width="25%">
                        <div style="color: #797a7b; font-weight: bold; text-transform: uppercase; font-size: 7px;">Referensi</div>
                        <div style="color: #414b5c;">{{ $job->kontak_referensi ?? '-' }}</div>
                    </td>
                    <td width="25%">
                        <div style="color: #797a7b; font-weight: bold; text-transform: uppercase; font-size: 7px;">Alasan Berhenti</div>
                        <div style="color: #414b5c;">
                            @if($job->masih_bekerja)
                                <span style="font-style: italic; color: #9ca4ac;">Masih Aktif Bekerja</span>
                            @else
                                {{ $job->alasan ?? '-' }}
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    @empty
        <div style="text-align: center; color: #a0aec0; font-style: italic; font-size: 10px; padding: 10px;">
            Belum ada pengalaman kerja yang dicantumkan.
        </div>
    @endforelse

    {{-- Section Keluarga Inti --}}
    <div class="section-title">Keluarga Inti (Istri/Suami/Anak)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="20%">Nama Anggota</th>
                <th width="10%">Hubungan</th>
                <th width="25%">Tempat, Tgl Lahir (Usia)</th>
                <th width="12%" class="text-center">Pendidikan</th>
                <th width="18%">Pekerjaan</th>
                <th width="15%" class="text-center">Kontak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applicant->profile->familyMembers->where('tipe', 'inti') as $fam)
                @php
                    $isDeceased = str_contains($fam->nama ?? '', '+');
                    $cleanName = trim(str_replace('+', '', $fam->nama ?? '-'));
                    $age = $fam->tgl_lahir?->age;
                @endphp
                <tr>
                    <td class="font-bold">{{ $isDeceased ? '(Alm/h) ' : '' }}{{ $cleanName }}</td>
                    <td class="text-red font-bold" style="font-size: 9px; text-transform: uppercase;">{{ $fam->hubungan ?? '-' }}</td>
                    <td>
                        {{ $fam->tempat_lahir ?? '-' }},
                        {{ $fam->tgl_lahir?->format('d/m/Y') ?? '-' }}
                        @if($age) <span class="font-bold text-gray">({{ $age }} Th)</span> @endif
                    </td>
                    <td class="text-center">{{ $fam->pendidikan ?? '-' }}</td>
                    <td>{{ $fam->pekerjaan ?? '-' }}</td>
                    <td class="text-center">{{ $fam->hp ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray italic">Tidak ada data keluarga inti.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Section Keluarga Kandung --}}
    <div class="section-title">Keluarga Kandung (Orang Tua/Saudara)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="20%">Nama Anggota</th>
                <th width="10%">Hubungan</th>
                <th width="25%">Tempat, Tgl Lahir (Usia)</th>
                <th width="12%" class="text-center">Pendidikan</th>
                <th width="18%">Pekerjaan</th>
                <th width="15%" class="text-center">Kontak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applicant->profile->familyMembers->where('tipe', 'kandung') as $fam)
                @php
                    $isDeceased = str_contains($fam->nama ?? '', '+');
                    $cleanName = trim(str_replace('+', '', $fam->nama ?? '-'));
                    $age = $fam->tgl_lahir?->age;
                @endphp
                <tr>
                    <td class="font-bold">{{ $isDeceased ? '(Alm/h) ' : '' }}{{ $cleanName }}</td>
                    <td class="text-red font-bold" style="font-size: 9px; text-transform: uppercase;">{{ $fam->hubungan ?? '-' }}</td>
                    <td>
                        {{ $fam->tempat_lahir ?? '-' }},
                        {{ $fam->tgl_lahir?->format('d/m/Y') ?? '-' }}
                        @if($age) <span class="font-bold text-gray">({{ $age }} Th)</span> @endif
                    </td>
                    <td class="text-center">{{ $fam->pendidikan ?? '-' }}</td>
                    <td>{{ $fam->pekerjaan ?? '-' }}</td>
                    <td class="text-center">{{ $fam->hp ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray italic">Tidak ada data keluarga kandung.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="clearfix" style="margin-bottom: 20px;"></div>

    <div class="section-title">Preferensi Kerja & Minat</div>
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td width="45%" style="vertical-align: top; padding-right: 15px;">
                <table width="100%" class="table-info">
                    <tr>
                        <td class="text-gray font-bold" style="padding-bottom: 5px;">Gaji yang Diharapkan</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #e53e3e; font-weight: bold;">{{ $applicant->profile->expected_salary }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray font-bold" style="padding-top: 8px;">Fasilitas Diharapkan</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #edf2f7; padding-bottom: 5px;">{{ $applicant->profile->expected_facilities ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding-top: 8px;">
                            <span class="text-gray font-bold">Dinas Luar Kota:</span> 
                            <span class="font-bold">{{ $applicant->profile->ready_dinas }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-gray font-bold">Penempatan Luar Kota:</span> 
                            <span class="font-bold">{{ $applicant->profile->ready_placed_out }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray font-bold" style="padding-top: 8px;">Referensi Internal</td>
                    </tr>
                    <tr>
                        <td style="font-style: italic; color: #4a5568;">{{ $applicant->profile->company_reference ?? 'Tidak Ada' }}</td>
                    </tr>
                </table>
            </td>

            <td width="55%" style="vertical-align: top; background-color: #f8fafc; padding: 10px; border-radius: 8px;">
                <p class="text-center font-bold" style="margin-top: 0; margin-bottom: 8px; font-size: 9px; color: #718096; text-transform: uppercase;">Prioritas Minat Bekerja</p>
                
                <table width="100%">
                    <tr>
                        @php
                            $minats = $applicant->profile->jobFieldInterests->sortBy('pivot.rank')->pluck('nama')->all();
                            $total = count($minats);
                            
                            // Inisialisasi awal agar tidak error
                            $half = 0;
                            $chunks = [];

                            // Proteksi: Hanya jalankan logika jika ada data
                            if ($total > 0) {
                                $half = ceil($total / 2);
                                $chunks = array_chunk($minats, $half);
                            }
                        @endphp

                        @forelse($chunks as $chunkIndex => $chunk)
                            <td width="50%" style="vertical-align: top;">
                                <table width="100%" style="border-collapse: collapse;">
                                    @foreach($chunk as $itemIndex => $item)
                                        @php
                                            $originalIndex = ($chunkIndex * $half) + $itemIndex + 1;
                                        @endphp
                                        <tr>
                                            <td width="15" style="font-size: 8px; color: white; background-color: #e53e3e; text-align: center; font-weight: bold; border: 1px solid white;">
                                                {{ $originalIndex }}
                                            </td>
                                            <td style="font-size: 8.5px; padding: 2px 4px; background-color: white; border: 1px solid #f1f5f9; text-transform: uppercase; white-space: nowrap;">
                                                {{ $item == 'R & D' ? 'Research & Development' : $item }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        @empty
                            {{-- Tampilan saat kosong agar tabel tetap valid secara struktur --}}
                            <td colspan="2" style="font-size: 9px; color: #9ca3af; font-style: italic; text-align: center; padding: 10px; border: 1px dashed #e2e8f0;">
                                Data minat kosong
                            </td>
                        @endforelse
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">Lampiran Berkas</div>
    <table class="table-info" style="font-size: 11px;">
        <tr>
            <td>CV: {{ $docsByType->get('cv') ? 'Tersedia' : 'Tidak' }}</td>
            <td>KTP: {{ $docsByType->get('ktp') ? 'Tersedia' : 'Tidak' }}</td>
            <td>Ijazah: {{ $docsByType->get('ijazah') ? 'Tersedia' : 'Tidak' }}</td>
            <td>NPWP: {{ $docsByType->get('npwp') ? 'Tersedia' : 'Tidak' }}</td>
        </tr>
        <tr>
            <td>SIM: {{ $docsByType->get('sim') ? 'Tersedia' : 'Tidak' }}</td>
            <td>BPJS Kes: {{ $docsByType->get('bpjs_kes') ? 'Tersedia' : 'Tidak' }}</td>
            <td>BPJS TK: {{ $docsByType->get('bpjs_tk') ? 'Tersedia' : 'Tidak' }}</td>
            <td>Lainnya: {{ $docsByType->get('lain') ? 'Tersedia' : 'Tidak' }}</td>
        </tr>
    </table>

    <br>
    <div
        style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #a0aec0; border-top: 1px solid #edf2f7; padding-top: 5px;">
        Dokumen ini dihasilkan secara otomatis oleh Recruitment System PT SINAR SOSRO GUNUNG SLAMAT pada
        {{ date('d/m/Y') }}
    </div>

</body>

</html>
