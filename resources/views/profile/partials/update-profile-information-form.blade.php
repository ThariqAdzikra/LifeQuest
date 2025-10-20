<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Informasi Profil
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Perbarui informasi profil dan alamat email akun Anda.
        </p>
    </header>

    <style>
        .avatar-upload-container {
            position: relative;
            width: 128px; /* 8rem */
            height: 128px;
            cursor: pointer;
        }
        .avatar-preview {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.3);
            transition: border-color 0.3s ease;
        }
        .avatar-upload-container:hover .avatar-preview {
            border-color: #00d4ff;
        }
        .avatar-upload-overlay {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background-color: rgba(10, 14, 39, 0.6);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .avatar-upload-container:hover .avatar-upload-overlay {
            opacity: 1;
        }
    </style>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <label>Foto Profil</label>
            <div class="mt-2">
                {{-- [BARU] Area upload foto yang dinamis --}}
                <div class="avatar-upload-container" @click="$refs.avatarInput.click()">
                    {{-- Pratinjau Avatar --}}
                    <img id="avatar-preview" 
                         src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://placehold.co/128x128/0a0e27/a78bfa?text=' . strtoupper(substr($user->name, 0, 2)) }}" 
                         alt="Profile Avatar" 
                         class="avatar-preview">
                    
                    {{-- Overlay saat hover --}}
                    <div class="avatar-upload-overlay">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span class="text-sm mt-1">Ganti Foto</span>
                    </div>
                </div>

                {{-- Input file yang asli kita sembunyikan --}}
                <input id="avatar" name="avatar" type="file" class="hidden" x-ref="avatarInput" @change="handleFileChange($event)" accept="image/png, image/jpeg, image/webp"/>
                <p class="mt-2 text-sm text-gray-400">Klik lingkaran untuk mengganti foto.</p>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div>
            <label for="name">Nama</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-200">
                        Alamat email Anda belum terverifikasi.
                        <button form="send-verification" class="underline text-sm text-gray-400 hover:text-gray-100 rounded-md focus:outline-none">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-400">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-400">Tersimpan.</p>
            @endif
        </div>
    </form>
</section>

