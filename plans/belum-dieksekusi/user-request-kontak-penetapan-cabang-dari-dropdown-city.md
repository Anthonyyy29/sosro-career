# Penetapan Cabang pada Pesan Kontak: Dropdown `city` → `cabang_id`

**Urgensi: SEDANG — menunggu konfirmasi user.**
Sistemnya tidak sedang rusak, jadi tidak mendesak. Tapi ada satu bagian (#10) yang **wajib beres sebelum deploy produksi**, dan cacat utamanya bersifat menumpuk — makin lama dibiarkan, makin banyak baris rusak yang harus dibereskan belakangan.

**Status: belum ada keputusan.** Dokumen ini merekam analisis dan pilihan yang tersedia. Jangan dieksekusi sebelum user memilih.

---

## Konteks

Muncul dari pertanyaan user 23 Agustus 2026: kenapa `cabang_id` pesan kontak dicari lewat tabel `cabangs`, bukan diambil dari `admins.cabang_id` yang sudah punya FK ke `cabangs`?

**Jawabannya sudah tuntas dan tidak perlu diubah:** pada saat `ContactController::store()` jalan, **tidak ada admin sama sekali** — form `/kontak` itu publik, pengunjungnya anonim, `Auth::user()` bernilai `null`. Satu-satunya informasi yang tersedia cuma `city` dari dropdown "Wilayah Tujuan". Jadi jalurnya `city (string) → cabangs.nama → cabang_id` bukan karena `cabangs` lebih disukai, tapi karena `admins` memang belum ada di titik itu.

Rute lewat admin juga **sudah pernah dipakai lalu sengaja dibuang**:
- `a2245ac` (3 Agu, ezra) — versi awal, superadmin menugaskan ke satu `admin_id`.
- `1ff51b5` (10 Agu, ezra) — *"Assign contact messages to a cabang instead of a single admin"*. Alasannya: FK-nya `onDelete('set null')`, jadi kalau admin dihapus/resign pesannya jadi yatim dan hilang dari kotak masuk semua orang; rekan satu cabang juga tidak bisa ikut membalas.
- `b8a5644` (10 Agu, ezra) — auto-assign dari `city` pengunjung, bentuk yang berjalan sekarang.

Kedua kolom sekarang hidup berdampingan dengan makna berbeda, dan itu wajar — jangan digabung lagi:

| Kolom | Artinya | Diisi kapan |
|---|---|---|
| `cabang_id` | cabang yang **berwenang** menangani | saat pesan masuk (`store()`), atau saat di-`assign()` superadmin |
| `admin_id` | admin yang **benar-benar membalas** | di `markAsReplied()` |

## Masalah sebenarnya: pencocokan string tanpa penegak

`app/Http/Controllers/ContactController.php:33`

```php
$validated['cabang_id'] = Cabang::where('nama', $validated['city'])->value('id');
```

Ini mencocokkan atribut `value` di `<option>` (`resources/views/pages/kontak.blade.php:64-96`, hardcoded) dengan `cabangs.nama` **murni lewat konvensi** — tidak ada FK, tidak ada validasi, tidak ada test.

**Kondisi saat audit (23 Agu 2026, dicek langsung ke DB dev):** 22 opsi dropdown vs 22 baris `cabangs`, **cocok persis, selisih nol di kedua arah**. Tabel `contacts` masih 0 baris. Jadi sekarang benar-benar tidak ada yang rusak.

Risikonya di masa depan:
- Cabang di-rename di DB, atau satu opsi salah ketik di blade → lookup mengembalikan `null` → pesan masuk dengan `cabang_id = NULL` → **tidak muncul di kotak masuk cabang mana pun**, tanpa error, tanpa log. Baru ketahuan kalau ada yang komplain pesannya tak dibalas.
- `city` cuma divalidasi `required`. POST langsung dengan `city=asdf` diterima, hasilnya juga baris NULL.
- Baris `cabang_id` NULL itu **bahan bakar temuan #11** di `temuan-keamanan-audit-branch.md` — admin ber-`cabang_id` NULL bisa menghapus permanen semua pesan ber-`cabang_id` NULL.

Catatan: sudah ada komentar `{{-- Warning !!! --}}` di blade itu (di atas opsi `KPW Sumut NAD - Sumbar Kepri`) — sepertinya penulisnya sendiri pernah curiga di baris tersebut.

---

## Pilihan yang menunggu keputusan user

### Opsi A — Jalan tengah: validasi `city` terhadap daftar cabang (satu baris)

```php
'city' => ['required', Rule::in(Cabang::pluck('nama'))],
```

Dropdown tetap hardcoded, `city` tetap string, tidak ada migrasi, bentuk data tidak berubah. Yang hilang cuma **gagal-senyap**-nya: begitu dua daftar melenceng, langsung tampil sebagai error validasi, bukan diam-diam jadi NULL.

- **Untung:** biaya paling kecil, menutup kerugian terbesar dari "biarkan saja", nol risiko regresi bentuk data.
- **Rugi:** menambah satu query ke halaman kontak publik; masih belum menegakkan hubungan sebenarnya (tetap cocok-cocokan teks); kalau daftar melenceng, pengunjung yang kena error — bukan admin yang diberi tahu.

### Opsi B — Dropdown mengirim `cabang_id` langsung

```blade
@foreach($cabangs as $c)
    <option value="{{ $c->id }}">{{ $c->nama }}</option>
@endforeach
```
```php
'cabang_id' => 'required|exists:cabangs,id',
```

- **Untung:** tidak bisa melenceng (opsi di-render dari DB); ada validasi server yang menolak nilai ngawur; rename cabang otomatis ikut tanpa sentuh blade; menutup sumber utama baris NULL.
- **Rugi:**
  - Halaman kontak publik jadi bergantung DB — `cabangs` kosong berarti dropdown kosong dan form tak terpakai. Sekarang hardcoded, jadi selalu render.
  - Nasib kolom `city` jadi pertanyaan baru: disimpan terus (redundan dengan `cabang->nama`) atau di-drop? Kalau di-drop butuh migrasi, dan `resources/views/admin/kontak/index.blade.php:30` yang menampilkan `{{ $msg->city }}` harus diganti ke `$msg->cabang->nama`.
  - Kehilangan arsip apa adanya — `city` sekarang menyimpan teks persis yang dipilih pengunjung; kalau cabang di-rename nanti, pesan lama akan menampilkan nama baru, bukan nama saat pesan dikirim.
  - Sentuhannya di 3 tempat: blade kontak, `store()`, dan controller/route yang merender halaman kontak (harus mulai mem-*pass* `$cabangs`).
  - Saat deploy: form lama yang masih terbuka di tab pengunjung akan mengirim nama, bukan id → kena error validasi.

### Opsi C — Biarkan seperti sekarang

- **Untung:** nol perubahan, nol risiko hari ini; halaman publik berdiri sendiri; `city` tetap catatan historis yang tahan rename.
- **Rugi:** kecocokan dijaga disiplin manusia selamanya; gagal-senyap tetap ada; cacatnya menumpuk.

---

## Rekomendasi

1. **Ambil Opsi A sekarang** — murni untung, satu baris, tidak mengunci keputusan apa pun.
2. **Tunda Opsi B**, lalu kerjakan sepaket dengan temuan **#10** dan **#11** dari `temuan-keamanan-audit-branch.md` — ketiganya menyentuh berkas yang sama.
3. **#10 wajib beres sebelum deploy produksi**, terlepas dari keputusan soal dropdown: migrasi `2026_08_10_000021` menambah `contacts.cabang_id` **tanpa backfill dari `admin_id`**, jadi pesan yang sudah diteruskan di sistem lama akan hilang dari kotak masuk penerimanya setelah deploy. Tidak terasa di dev karena `contacts` masih kosong.

## Yang sengaja TIDAK masuk scope

- Mengubah rute penugasan kembali lewat `admins` — sudah dievaluasi dan ditolak, alasannya di bagian Konteks.
- Menggabungkan `admin_id` dan `cabang_id` — dua makna berbeda, harus tetap terpisah.
- Widget Turnstile di `kontak.blade.php:106` yang **tidak divalidasi server sama sekali** (`store()` tidak menyentuh `cf-turnstile-response`) — sudah dipisah jadi `temuan-kontak-turnstile-tidak-divalidasi-server.md`. Menyentuh berkas yang sama, jadi kalau dua-duanya dikerjakan sebaiknya disatukan.

## Verifikasi (kalau nanti dieksekusi)

1. Kirim pesan dari `/kontak` untuk tiap kelompok opsi (Kantor Pusat, KPW, Pabrik, Lainnya) — cek `contacts.cabang_id` terisi benar, tidak ada NULL.
2. POST langsung dengan nilai wilayah ngawur — harus ditolak validasi, bukan tersimpan sebagai NULL.
3. Login sebagai admin cabang terkait — pesan muncul di `/admin/kontak`.
4. Login sebagai admin cabang lain — pesan yang sama **tidak** muncul.
5. Superadmin `assign()` pesan ke cabang lain — pindah kotak masuk dengan benar, `status` jadi `forwarded`.
6. Rename satu baris di `cabangs.nama`, ulangi langkah 1 — pada Opsi A harus muncul error validasi yang jelas; pada Opsi B harus tetap jalan normal.
