{{-- Isian tambahan untuk tahap Psikotes.
     Disertakan otomatis lewat kunci 'form' di config/recruitment.php. --}}
                        <div x-show="nextStatus === 'psikotes'" x-transition class="p-4 bg-blue-50 rounded-xl border border-blue-100 space-y-3">
                            <p class="text-[11px] font-bold text-blue-600 uppercase">Informasi Psikotes</p>

                            <div>
                                <label class="text-xs text-gray-500">Jenis Tes</label>
                                <select name="psikotes_type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1">
                                    <option value="psikotes">Psikotes (Standar)</option>
                                    <option value="tes_kepribadian">Tes Kepribadian (Level Atas)</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500">Tanggal Pelaksanaan</label>
                                <input type="date" name="psikotes_date" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Link Psikotes</label>
                                <input type="url" name="psikotes_link" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1" placeholder="https://...">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Token Access</label>
                                <input type="text" name="psikotes_token" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1" placeholder="XYZ-123">
                            </div>
                            <p class="text-[10px] text-blue-500 mt-2 italic">* Sistem akan otomatis mengirim email template Psikotes ke pelamar.</p>
                        </div>
