<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>RT/RW</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        {{-- ✅ nomor urut benar saat pagination --}}
                        <td>{{ $users->firstItem() + $index }}</td>

                        <td>{{ $user->username }}</td>
                        <td>{{ $user->warga->nama ?? '-' }}</td>
                        <td>{{ $user->warga->rt ?? '-' }}/{{ $user->warga->rw ?? '-' }}</td>
                        <td>{{ $user->warga->alamat ?? '-' }}</td>

                        <td>
                            <button type="button" class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal"
                                data-id="{{ $user->id }}"
                                data-username="{{ $user->username }}"
                                data-nama="{{ $user->warga->nama ?? '' }}"
                                data-rt="{{ $user->warga->rt ?? '' }}"
                                data-rw="{{ $user->warga->rw ?? '' }}"
                                data-alamat="{{ $user->warga->alamat ?? '' }}">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#resetModal"
                                data-id="{{ $user->id }}"
                                data-username="{{ $user->username }}">
                                Reset Password
                            </button>

                            <form action="{{ route('users.destroy', $user->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Tidak ada data warga.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
