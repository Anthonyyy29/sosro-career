{{-- Isian tambahan untuk tahap Offering Letter.
     Disertakan otomatis lewat kunci 'form' di config/recruitment.php. --}}
                        <div x-show="nextStatus === 'offering'" x-transition class="p-4 bg-pink-50 rounded-xl border border-pink-100 space-y-2">
                            <div class="flex items-center gap-2 text-pink-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-[11px] font-bold uppercase">Konfirmasi Tahap Offering</p>
                            </div>
                            <p class="text-xs text-gray-600">Sistem akan mengirimkan email pemberitahuan bahwa kandidat lolos seleksi dan akan segera dihubungi oleh tim HC untuk proses Offering Letter.</p>
                        </div>
