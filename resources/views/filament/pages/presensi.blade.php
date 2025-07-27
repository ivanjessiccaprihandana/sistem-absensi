<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{-- Pilih Matkul --}}
        <div>
            <label for="selectedSubject" class="block text-sm font-medium text-gray-700 mb-1">
                Pilih Matkul
            </label>
            <select wire:model="selectedSubject" wire:change="$refresh" id="selectedSubject"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">- Pilih Matkul -</option>
                @foreach ($this->subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->nama }}</option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Kelas --}}
        <div>
            <label for="selectedClass" class="block text-sm font-medium text-gray-700 mb-1">
                Pilih Kelas
            </label>
            <select wire:model="selectedClass" wire:change="$refresh" id="selectedClass"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">- Pilih Kelas -</option>
                @foreach ($this->classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Pertemuan --}}
        @if ($this->selectedClass)
        <div>
            <label for="selectedMeeting" class="block text-sm font-medium text-gray-700 mb-1">
                Pilih Pertemuan
            </label>
            <select wire:model="selectedMeeting" id="selectedMeeting"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">- Pilih Pertemuan -</option>
                @foreach ($this->meetings->where('class_id', $this->selectedClass) as $meeting)
                <option value="{{ $meeting->id }}">
                    {{ $meeting->subject->nama }} - {{ $meeting->class->name }} (Pertemuan {{ $meeting->meeting_number
                    }})
                </option>
                @endforeach
            </select>
        </div>
        @endif

        {{-- Info Kelas --}}
        @php
        $selectedClassObj = $this->classes->firstWhere('id', $this->selectedClass);
        $selectedMeetingObj = $this->meetings->firstWhere('id', $this->selectedMeeting);
        @endphp
        @if ($selectedClassObj)
        <div class="mb-4">
            <span class="text-sm text-gray-600">
                Menampilkan siswa untuk kelas: <span class="font-semibold">{{ $selectedClassObj->name }}</span>
            </span>
        </div>
        @endif

        {{-- Loading State --}}
        <div wire:loading>
            <p class="text-sm text-gray-400 italic">Memuat data siswa...</p>
        </div>

        {{-- Presensi Siswa --}}
        <div key="kelas-{{ $selectedClass }}">
            @if ($this->selectedClass && $this->students->isNotEmpty())
            <div class="bg-white p-4 rounded-lg shadow border">
                <h3 class="text-lg font-semibold mb-4">Presensi Siswa</h3>
                <div class="space-y-4">
                    @foreach ($this->students as $student)
                    <div wire:key="student-{{ $selectedClass }}-{{ $student->id }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $student->nama ?? $student->name }}
                        </label>
                        <select wire:model="data.presences.{{ $student->id }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
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
            <div class="mt-4">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Simpan Presensi
                </button>
            </div>
            @elseif($this->selectedClass)
            <p class="text-sm text-gray-500 mt-4">Tidak ada siswa untuk kelas ini.</p>
            @endif
        </div>
    </form>
</x-filament::page>