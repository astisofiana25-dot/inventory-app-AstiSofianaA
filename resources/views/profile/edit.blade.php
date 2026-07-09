@extends('layouts.app')
@section('title', 'Edit Profil')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ubah Profil</h2>

        <form id="profileForm" method="POST" action="{{ route('profile.update') }}" class="space-y-5" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex flex-col items-center gap-4 mb-4">
                <label id="profilePhotoLabel" for="profile_photo" class="relative cursor-pointer rounded-full p-0.5 bg-white/70 transition">
                    <div id="profilePhotoPreview" class="w-28 h-28 rounded-full bg-gray-200 overflow-hidden border border-gray-200 flex items-center justify-center">
                        @if($user->profile_photo)
                            <img id="profilePhotoPreviewImage" src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil" class="w-full h-full object-cover" />
                        @else
                            <span id="profilePhotoInitial" class="text-2xl font-semibold text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <input id="profile_photo" type="file" name="profile_photo" accept="image/*" class="hidden" />
                </label>
                <p class="text-xs text-gray-400">Klik lingkaran untuk mengubah foto profil</p>
                @error('profile_photo') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama</label>
                <input id="profile_name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-brand-500 focus:ring-brand-500" />
                @error('name') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Karyawan</label>
                <input type="text" value="{{ old('employee_id', $user->employee_id) ?? '-' }}" readonly class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-700" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" value="{{ old('email', $user->email) }}" readonly class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-700" />
            </div>

            <div>
                <button id="profileActionButton" type="button" class="rounded-xl bg-gray-300 px-5 py-3 text-sm font-semibold text-gray-600 cursor-pointer hover:bg-gray-300" >Edit Profil</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('profile_photo');
            let previewImage = document.getElementById('profilePhotoPreviewImage');
            const previewInitial = document.getElementById('profilePhotoInitial');
            const previewContainer = document.getElementById('profilePhotoPreview');
            const profileName = document.getElementById('profile_name');
            const profileLabel = document.getElementById('profilePhotoLabel');
            const profileButton = document.getElementById('profileActionButton');
            const profileForm = document.getElementById('profileForm');

            let editMode = false;
            let formChanged = false;

            function setFormEditable(editable) {
                // fields remain visible and editable; button is the only visual mode toggle
            }

            function updateButtonState() {
                if (!editMode) {
                    profileButton.textContent = 'Edit Profil';
                    profileButton.className = 'rounded-xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-700 cursor-pointer';
                } else {
                    profileButton.textContent = 'Simpan Profil';
                    if (formChanged) {
                        profileButton.className = 'rounded-xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-700';
                    } else {
                        profileButton.className = 'rounded-xl bg-gray-300 px-5 py-3 text-sm font-semibold text-gray-600 cursor-not-allowed';
                    }
                }
            }

            function enableEditMode() {
                editMode = true;
                formChanged = false;
                updateButtonState();
            }

            function markChanged() {
                if (!editMode) {
                    enableEditMode();
                }
                if (!formChanged) {
                    formChanged = true;
                    updateButtonState();
                }
            }

            profileButton.addEventListener('click', function () {
                if (!editMode) {
                    enableEditMode();
                    return;
                }

                if (!formChanged) {
                    return;
                }

                profileForm.submit();
            });

            profileName.addEventListener('input', markChanged);
            fileInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (!previewImage) {
                        previewContainer.innerHTML = '';
                        previewImage = document.createElement('img');
                        previewImage.id = 'profilePhotoPreviewImage';
                        previewImage.className = 'w-full h-full object-cover';
                        previewContainer.appendChild(previewImage);
                    }

                    if (previewInitial) {
                        previewInitial.style.display = 'none';
                    }

                    previewImage.src = e.target.result;
                    markChanged();
                };
                reader.readAsDataURL(file);
            });

            setFormEditable(false);
            updateButtonState();
        });
    </script>

    <div id="password" class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ubah Kata Sandi</h2>

        <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi Saat Ini</label>
                <x-password-input name="current_password" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-brand-500 focus:ring-brand-500" />
                @error('current_password') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi Baru</label>
                <x-password-input name="password" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-brand-500 focus:ring-brand-500" />
                @error('password') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Kata Sandi</label>
                <x-password-input name="password_confirmation" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-brand-500 focus:ring-brand-500" />
            </div>

            <button type="submit" class="rounded-xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-700">Simpan Kata Sandi</button>
        </form>
    </div>
</div>
@endsection
