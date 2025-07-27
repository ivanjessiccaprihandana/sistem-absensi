<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{-- Pilih Pertemuan --}}
        <div>
            <label for="selectedMeeting" class="block text-sm font-medium text-gray-700 mb-1">
                Pilih Pertemuan
            </label>
            <select
                wire:model="selectedMeeting"
                id="selectedMeeting"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500"
            >
                @foreach ($this->meetings as $meeting)
                    <option value="{{ $meeting->id }}">
                        {{ $meeting->subject->nama }} - {{ $meeting->class->name }} (Pertemuan {{ $meeting->meeting_number }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Presensi Siswa --}}
        @if ($this->students->count())
            <div class="bg-white p-4 rounded-lg shadow border">
                <h3 class="text-lg font-semibold mb-4">Presensi Siswa</h3>
                <div class="space-y-4">
                    @foreach ($this->students as $student)
                           <div wire:key="student-{{ $student->id }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $student->name }}
                            </label>
                            <select
                                wire:model.defer="data.presences.{{ $student->id }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="">- Pilih Status -</option>
                                @foreach (\App\Models\Attendances::STATUS as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div>
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    Simpan Presensi
                </button>
            </div>
        @else
            <p class="text-sm text-gray-500">Tidak ada siswa untuk pertemuan ini.</p>
        @endif
    </form>
</x-filament::page>
