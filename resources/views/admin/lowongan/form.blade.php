<div class="grid grid-cols-2 gap-4">

    <div>
        <label>Kode Lowongan</label>
        <input type="text" name="kode_lowongan" class="input"
               value="{{ old('kode_lowongan', $lowongan->kode_lowongan ?? '') }}">
    </div>

    <div>
        <label>Judul Lowongan</label>
        <input type="text" name="judul_lowongan" class="input"
               value="{{ old('judul_lowongan', $lowongan->judul_lowongan ?? '') }}">
    </div>

    <div>
        <label>Kategori</label>
        <input type="text" name="kategori" class="input"
               value="{{ old('kategori', $lowongan->kategori ?? '') }}">
    </div>

    <div>
        <label>Bidang</label>
        <input type="text" name="bidang" class="input"
               value="{{ old('bidang', $lowongan->bidang ?? '') }}">
    </div>

    <div>
        <label>Tipe Lowongan</label>
        <input type="text" name="tipe_lowongan" class="input"
               value="{{ old('tipe_lowongan', $lowongan->tipe_lowongan ?? '') }}">
    </div>

    <div>
        <label>Penempatan Cabang</label>
        <input type="text" name="penempatan_cabang" class="input"
               value="{{ old('penempatan_cabang', $lowongan->penempatan_cabang ?? '') }}">
    </div>

    <div>
        <label>Lokasi Kerja</label>
        <input type="text" name="lokasi_kerja" class="input"
               value="{{ old('lokasi_kerja', $lowongan->lokasi_kerja ?? '') }}">
    </div>

    <div>
        <label>Pendidikan Terakhir</label>
        <input type="text" name="pendidikan_terakhir" class="input"
               value="{{ old('pendidikan_terakhir', $lowongan->pendidikan_terakhir ?? '') }}">
    </div>

    <div>
        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="input"
               value="{{ old('tanggal_mulai', $lowongan->tanggal_mulai ?? '') }}">
    </div>

    <div>
        <label>Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="input"
               value="{{ old('tanggal_akhir', $lowongan->tanggal_akhir ?? '') }}">
    </div>

    <div class="col-span-2">
        <label>Status Lowongan</label>
        <select name="status_lowongan" class="input">
            <option value="aktif">Aktif</option>
            <option value="non-aktif">Non-Aktif</option>
            <option value="selesai">Selesai</option>
            <option value="dihapus">Dihapus</option>
        </select>
    </div>

    <div class="col-span-2">
        <label>Jobdesk</label>
        <textarea name="jobdesk" rows="5" class="input">{{ old('jobdesk', $lowongan->jobdesk ?? '') }}</textarea>
    </div>

    <div class="col-span-2">
        <label>Kualifikasi</label>
        <textarea name="kualifikasi" rows="5" class="input">{{ old('kualifikasi', $lowongan->kualifikasi ?? '') }}</textarea>
    </div>

</div>

<style>
    .input {
        @apply w-full border-gray-300 rounded-lg shadow-sm;
    }
</style>
