# Konvensi Folder `plans/`

## Folder = status pengerjaan

| Folder | Artinya |
|---|---|
| `belum-dieksekusi/` | sudah ditulis, belum dikerjakan |
| `sedang-dieksekusi/` | sedang jalan |
| `selesai/` | sudah dikerjakan & terverifikasi |

Berkas dipindah antar folder saat statusnya berubah. **Jangan percaya footer "belum dieksekusi" di dalam dokumen** — pernah kejadian isinya basi padahal kerjaannya sudah selesai; verifikasi ke kode, bukan ke klaim dokumennya.

## Awalan nama berkas = asal usul

| Awalan | Artinya |
|---|---|
| `user-request-` | **inisiasi user.** User yang minta fitur/perubahannya, atau pertanyaan user yang memunculkannya. |
| `temuan-` | **inisiasi Claude.** Ditemukan sendiri sambil mengerjakan hal lain, bukan diminta. |

Gunanya: memisahkan mana yang benar-benar kebutuhan user dari mana yang usulan Claude, supaya prioritas tidak tertukar. Kalau ragu, tanya user — jangan menebak, label yang salah lebih buruk daripada tidak ada label.

## Yang bukan plan

`db_sosro_normalized.dbml` dan `db_sosro_normalized_table_list.md` di akar folder ini **bukan** plan — itu dokumentasi skema yang disinkronkan langsung dari DB (introspeksi, bukan tulisan tangan). Regenerasi dari DB saat skema berubah, jangan diedit manual.

## Tingkat urgensi

Ditulis di dalam dokumennya, tepat di bawah judul. Nilai yang dipakai sejauh ini: `RENDAH`, `SEDANG`, `TINGGI`, dengan keterangan singkat kalau perlu (mis. `SEDANG — menunggu konfirmasi user`).
