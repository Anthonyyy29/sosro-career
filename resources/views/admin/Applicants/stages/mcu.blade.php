{{-- Isian tambahan untuk tahap MCU.
     Disertakan otomatis lewat kunci 'form' di config/recruitment.php. --}}
                        <div x-show="nextStatus === 'mcu'" x-transition class="p-4 bg-teal-50 rounded-xl border border-teal-100 space-y-3">
                            <p class="text-[11px] font-bold text-teal-600 uppercase">Informasi Medical Check Up</p>
                            
                            <div>
                                <label class="text-xs text-gray-500">Tanggal & Waktu MCU</label>
                                <input type="datetime-local" name="mcu_date" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1">
                            </div>

                            <div>
                                <label class="text-xs text-gray-500">Nama Rumah Sakit/Klinik</label>
                                <input type="text" name="mcu_location_name" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1" placeholder="Contoh: RS Gading Pluit">
                            </div>

                            <div>
                                <label class="text-xs text-gray-500">Alamat Lengkap Lokasi</label>
                                <textarea name="mcu_location_address" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1" placeholder="Masukkan alamat lengkap RS/Klinik..."></textarea>
                            </div>
                        </div>
