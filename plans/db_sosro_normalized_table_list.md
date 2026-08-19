# List Tabel — db_sosro (skema yang beneran jalan sekarang, 25 tabel)

Sinkron dengan `db_sosro_normalized.dbml` — sumbernya introspeksi langsung ke MySQL dev, bukan dokumen rencana. Kalau ada migration baru, refresh dari DB dulu, jangan edit manual berdasar dugaan.

1. users — akun login pelamar (guard web), sumber nama tampilan
    1. id : PK
    2. name : nama
    3. email : unique, dipakai login
    4. role : default 'user'
    5. email_verified_at : waktu verifikasi email
    6. password
    7. remember_token : fitur remember me
    8. photo : nama file foto profil
    9. created_at / updated_at

2. applicants — status proses lamaran & progress pengisian biodata per akun
    1. id : PK
    2. status : default 'Pending' — nilai aktual dipakai kode tidak konsisten kapitalisasinya (Pending/Reviewed/Accepted dari seeder vs "active"/"draft" dari ProfileController), varchar bebas bukan enum
    3. user_id : FK ke users.id, nullable, **TIDAK unique di DB** (relasi 1 user = 1 applicant cuma dijaga di level kode, bukan constraint)
    4. profile_completed : boolean, default false
    5. biodata_progress : int, default 0 — persen progress isi biodata
    6. consent_accepted : boolean, default false
    7. personal_completed : boolean, default false
    8. family_completed : boolean, default false
    9. education_completed : boolean, default false
    10. experience_completed : boolean, default false
    11. documents_completed : boolean, default false
    12. biodata_submitted : boolean, default false
    13. created_at / updated_at

3. applicant_documents — dokumen yang diupload pelamar (1 baris per dokumen)
    1. id : PK
    2. applicant_id : FK ke applicants.id
    3. type : jenis dokumen (foto, cv, ktp, ijazah, sim, npwp, bpjs_kes, bpjs_tk, lain)
    4. file_path : lokasi file
    5. is_required : boolean, default false
    6. extracted_data : json nullable — hasil OCR
    7. created_at / updated_at

4. applicant_profiles — biodata inti pelamar (identitas, kontak, preferensi kerja)
    1. id : PK
    2. applicant_id : FK ke applicants.id, **TIDAK unique di DB** (relasi 1 applicant = 1 profile cuma dijaga di level kode)
    3. nik : unique, nomor KTP
    4. jk : DB enum('L','P')
    5. tempat_lahir
    6. tanggal_lahir : date
    7. tinggi_badan
    8. berat_badan
    9. alamat : text, alamat KTP
    10. domisili : text
    11. phone
    12. agama
    13. status_nikah
    14. jenis_sim : json — array pilihan (A, B1, B2, C, D)
    15. instagram
    16. linkedin
    17. ex_employee : Ya/Tidak, pernah kerja di Sosro
    18. ex_company_name
    19. ex_last_position
    20. penyakit : text, riwayat penyakit
    21. perokok : DB enum('ya','tidak')
    22. bertato : DB enum('ya','tidak')
    23. expected_salary
    24. expected_facilities : text
    25. ready_dinas : varchar(10) not null, default 'Ya'
    26. ready_placed_out : varchar(10) not null, default 'Ya'
    27. company_reference
    28. created_at / updated_at

5. applicant_family_members — data anggota keluarga (inti & kandung) per pelamar
    1. id : PK
    2. applicant_profile_id : FK ke applicant_profiles.id
    3. tipe : inti/kandung (varchar, satu-satunya kolom wajib selain FK/timestamp)
    4. nama
    5. hubungan : hubungan keluarga
    6. pendidikan
    7. tempat_lahir
    8. tgl_lahir : date
    9. pekerjaan
    10. hp
    11. created_at / updated_at

6. applicant_formal_educations — riwayat pendidikan formal per pelamar (tabel terpisah dari informal, bukan digabung)
    1. id : PK
    2. applicant_profile_id : FK
    3. jenjang
    4. sekolah
    5. jurusan
    6. nilai
    7. tahun_masuk
    8. tahun_lulus
    9. is_current_edu : boolean, default false
    10. created_at / updated_at

7. applicant_informal_educations — riwayat pendidikan informal (kursus/pelatihan) per pelamar
    1. id : PK
    2. applicant_profile_id : FK
    3. kursus
    4. penyelenggara
    5. tahun
    6. created_at / updated_at

8. applicant_work_experiences — riwayat pengalaman kerja per pelamar
    1. id : PK
    2. applicant_profile_id : FK
    3. perusahaan
    4. jabatan
    5. divisi
    6. gaji
    7. fasilitas
    8. masih_bekerja : boolean, default false
    9. tanggal_masuk : date
    10. tanggal_keluar : date nullable
    11. alasan : text, alasan keluar/pindah
    12. kontak_referensi
    13. created_at / updated_at

9. applicant_job_field_interests — ranking minat bidang pekerjaan pelamar (pivot)
    1. applicant_profile_id : FK, bagian PK gabungan
    2. job_field_id : FK, bagian PK gabungan
    3. rank : int — 1 = paling diminati, selalu 13 baris per pelamar

10. admins — akun staf internal (guard admin), dengan scoping per cabang
    1. id : PK
    2. name
    3. email : unique
    4. role : default 'admin' (nilai lain: superadmin)
    5. cabang_id : FK ke cabangs.id, nullable (superadmin tidak terikat 1 cabang)
    6. photo
    7. password
    8. remember_token
    9. created_at / updated_at

11. lowongan — data lowongan kerja yang dibuka admin
    1. id : PK
    2. kode_lowongan : unique, format LWG-00001
    3. judul_lowongan
    4. kategori : varchar biasa, tidak dinormalisasi (cuma 3 nilai tetap: Profesional, Management Trainee, Magang)
    5. bidang : **varchar biasa** — sempat jadi bidang_id FK ke job_fields, di-revert balik jadi string
    6. tipe_lowongan : varchar biasa, tidak dinormalisasi (cuma 4 nilai tetap: Full-time, Part-time, Freelance, Kontrak)
    7. cabang_id : FK ke cabangs.id
    8. lokasi_kerja
    9. jobdesk : text
    10. kualifikasi : text
    11. tanggal_mulai : date
    12. tanggal_akhir : date
    13. status_lowongan : DB enum aktif/tidak aktif/dihapus/selesai, default aktif
    14. created_by : FK ke admins.id
    15. updated_by : FK ke admins.id
    16. created_at / updated_at

12. applications — lamaran pelamar ke lowongan tertentu, tracking pipeline rekrutmen
    1. id : PK
    2. applicant_id : FK
    3. lowongan_id : FK
    4. status : pending/psikotes/interview/offering/mcu/accepted/rejected (varchar bebas, bukan DB enum)
    5. notes : text nullable
    6. created_at / updated_at
    7. unique(applicant_id, lowongan_id)

13. recruitment_stages — master tahapan seleksi rekrutmen (dulu hardcode array PHP, sekarang data-driven)
    1. id : PK
    2. key : unique — pending, administration, psikotes, interview, study case, panel bod, simulasi, offering, mcu, accepted, rejected
    3. label : label sisi admin
    4. applicant_label : nullable — override label sisi pelamar, null berarti fallback ke label admin
    5. color_classes : kelas Tailwind badge
    6. is_universal : boolean, default false — true cuma untuk pending/accepted/rejected (tersedia di semua kategori, di luar pipeline)
    7. is_bulk_updatable : boolean, default false — true untuk stage yang bisa diubah lewat bulk-update admin
    8. created_at / updated_at

14. recruitment_stage_pipeline — urutan tahapan seleksi per kategori lowongan (pivot dengan kolom order)
    1. id : PK
    2. kategori : harus sama persis dengan lowongan.kategori, TIDAK ada FK (lowongan.kategori tetap varchar bebas)
    3. recruitment_stage_id : FK ke recruitment_stages.id, ON DELETE CASCADE
    4. order : int — urutan tahap dalam pipeline kategori ini, mulai dari 1. Cuma dipakai buat filter dropdown UI admin, **TIDAK ditegakkan** sebagai validasi transisi status di backend
    5. created_at / updated_at
    6. unique(kategori, recruitment_stage_id)
    7. unique(kategori, order)

15. contacts — pesan masuk dari form kontak publik
    1. id : PK
    2. name : nama pengirim
    3. email
    4. city
    5. message : text
    6. status : DB enum pending/forwarded/replied, default pending
    7. admin_id : FK ke admins.id, nullable — sekarang berarti "siapa yang balas", bukan penerima tunggal
    8. cabang_id : FK ke cabangs.id, nullable — **BARU**, cabang tujuan pesan (resolve otomatis dari city), semua admin cabang itu bisa lihat & balas
    9. created_at / updated_at

16. cabangs — master data cabang/kantor Sosro
    1. id : PK
    2. nama : unique (KPW Jakarta Banten, Pabrik Cakung, Kantor Pusat, dst)
    3. kelompok : HO/KPW/Pabrik/Lainnya — varchar biasa di DB, validasi cuma di level aplikasi (bukan DB enum)
    4. created_at / updated_at

17. job_fields — master bidang pekerjaan, dipakai minat pelamar (BUKAN lowongan, lihat poin 11.5)
    1. id : PK
    2. nama : unique (13 bidang: Marketing, Finance & Accounting, dst)
    3. created_at / updated_at

18. cache — bawaan Laravel, key-value cache aplikasi
    1. key : PK
    2. value
    3. expiration

19. cache_locks — bawaan Laravel, lock atomic untuk cache
    1. key : PK
    2. owner
    3. expiration

20. jobs — bawaan Laravel, antrian queue (dipakai kirim email async)
    1. id : PK
    2. queue
    3. payload
    4. attempts
    5. reserved_at
    6. available_at
    7. created_at

21. job_batches — bawaan Laravel, tracking progress batch job
    1. id : PK
    2. name
    3. total_jobs
    4. pending_jobs
    5. failed_jobs
    6. failed_job_ids
    7. options
    8. cancelled_at
    9. created_at
    10. finished_at

22. failed_jobs — bawaan Laravel, log job queue yang gagal
    1. id : PK
    2. uuid : unique
    3. connection
    4. queue
    5. payload
    6. exception
    7. failed_at

23. migrations — bawaan Laravel, tracking migration yang sudah jalan
    1. id : PK
    2. migration
    3. batch

24. password_reset_tokens — bawaan Laravel, fitur lupa password (dipakai pelamar & admin)
    1. email : PK
    2. token
    3. created_at

25. sessions — bawaan Laravel, penyimpanan session (kalau driver session = database)
    1. id : PK
    2. user_id
    3. ip_address
    4. user_agent
    5. payload
    6. last_activity
