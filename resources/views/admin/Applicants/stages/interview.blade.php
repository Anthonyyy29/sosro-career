{{-- Isian tambahan untuk tahap Interview.
     Disertakan otomatis lewat kunci 'form' di config/recruitment.php. --}}
                        <div x-show="nextStatus === 'interview'" x-transition class="p-4 bg-cyan-50 rounded-xl border border-cyan-100 space-y-3" x-data="{ invType: 'initial' }">
                            <p class="text-[11px] font-bold text-cyan-600 uppercase">Informasi Interview</p>
                            <div>
                                <label class="text-xs text-gray-500">Jenis Interview</label>
                                <select name="interview_type" x-model="invType" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1">
                                    <option value="initial">Interview Awal (Online)</option>
                                    <option value="lanjutan">Interview Lanjutan (Online)</option>
                                    <option value="offline">Interview Offline (Tatap Muka)</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500">Tanggal & Waktu Interview</label>
                                <input type="datetime-local" name="interview_date" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1">
                            </div>

                            <div x-show="invType !== 'offline'">
                                <label class="text-xs text-gray-500">Link Interview (Zoom/Gmeet)</label>
                                <input type="url" name="interview_link" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1" placeholder="https://...">
                            </div>

                            <div x-show="invType === 'offline'">
                                <label class="text-xs text-gray-500">Lokasi Interview (Alamat Lengkap)</label>
                                <textarea name="interview_location" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mt-1" placeholder="Gedung Graha Rekso..."></textarea>
                            </div>
                        </div>
