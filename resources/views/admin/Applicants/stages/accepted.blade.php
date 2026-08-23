{{-- Isian tambahan untuk tahap Accepted.
     Disertakan otomatis lewat kunci 'form' di config/recruitment.php. --}}
                        <div x-show="nextStatus === 'accepted'" x-transition class="p-4 bg-green-50 rounded-xl border border-green-100 space-y-3">
                            <p class="text-[11px] font-bold text-green-600 uppercase">Informasi Karyawan Baru</p>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500">Tipe Kantor</label>
                                    <select name="office_type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1">
                                        <option value="HO">Head Office (HO)</option>
                                        <option value="KPW">Kantor Wilayah (KPW)</option>
                                        <option value="KPB">Kantor Pabrikan/PKM/Kebun</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Tanggal Mulai Kerja</label>
                                    <input type="date" name="join_date" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500">Nama Penempatan Kerja</label>
                                <input type="text" name="work_location" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1" placeholder="Contoh: KPW Jawa Barat">
                            </div>

                            <div>
                                <label class="text-xs text-gray-500">Alamat Lengkap Kantor Penempatan</label>
                                <textarea name="office_address" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1" placeholder="Masukkan alamat lengkap kantor..."></textarea>
                            </div>
                        </div>
