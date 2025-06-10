{{-- resources/views/filament/resources/asisten-resource/pages/view-kelas-asisten.blade.php --}}

<x-filament::page>
    <div class="space-y-4">
        <h2 class="text-xl font-bold">Data Kelas Asisten: {{ $asisten->nama }}</h2>

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full text-sm text-left text-gray-200">
                <thead class="text-xs uppercase bg-gray-800 text-gray-400">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Lab</th>
                        <th class="px-4 py-3">Hari</th>
                        <th class="px-4 py-3">Waktu Masuk</th>
                        <th class="px-4 py-3">Waktu Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelasList as $kelas)
                        <tr class="border-b border-gray-700 bg-gray-900 hover:bg-gray-800">
                            <td class="px-4 py-2">{{ $kelas->id }}</td>
                            <td class="px-4 py-2">{{ $kelas->matkul }}</td>
                            <td class="px-4 py-2">{{ $kelas->kelas }}</td>
                            <td class="px-4 py-2">{{ $kelas->lab }}</td>
                            <td class="px-4 py-2">{{ $kelas->hari }}</td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($kelas->waktu_mulai)->format('H:i') }}
                            </td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($kelas->waktu_selesai)->format('H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-gray-400">Tidak ada kelas yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('filament.admin.resources.asistens.index') }}"
            class="text-primary-500 hover:underline">&larr; Kembali ke Data Asisten</a>
    </div>
</x-filament::page>
