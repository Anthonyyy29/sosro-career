{{-- Isian tambahan untuk tahap Rejected.
     Disertakan otomatis lewat kunci 'form' di config/recruitment.php. --}}
                        <div x-show="nextStatus === 'rejected'" x-transition class="p-4 bg-red-50 rounded-xl border border-red-100 space-y-3">
                            <div class="flex items-center gap-2 text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-[11px] font-bold uppercase">Konfirmasi Penolakan</p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-red-600 font-bold uppercase">Alasan Penolakan</label>
                                <select name="rejection_reason" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1 focus:ring-red-500">
                                    <option value="" disabled selected>-- Pilih Alasan --</option>
                                    <option value="Pengalaman kerja tidak sesuai">Pengalaman kerja tidak sesuai</option>
                                    <option value="Latar belakang industri tidak sesuai">Latar belakang industri tidak sesuai</option>
                                    <option value="Latar belakang pendidikan tidak sesuai">Latar belakang pendidikan tidak sesuai</option>
                                    <option value="Belum sesuai dengan kriteria lowongan yang ada saat ini">Kriteria belum sesuai</option>
                                </select>
                            </div>
                            
                            <p class="text-[10px] text-red-500 italic mt-1">* Alasan ini akan tampil di dashboard pelamar sebagai feedback standar.</p>
                        </div>
