@extends('layouts.admin')

@section('title', 'Tambah User')
@section('header', 'Tambah User')
@section('subheader', 'isi formulir di bawah untuk menambahkan user baru')

@section('page_title', 'Tambah User')
@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Informasi Dasar -->
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Dasar
                </h3>

                <div class="grid grid-cols-1 gap-6">

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama *</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2
                                   focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2
                                   focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2
                                   focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"> confirm Password *</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2
                                   focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role User *</label>
                        <select
                            name="role"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2
                                   focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">-- Pilih Role --</option>

                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="librarian" {{ old('role') == 'librarian' ? 'selected' : '' }}>librarian</option>

                        </select>
                    </div>

                </div>
            </div>

            <!-- Aksi -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('user.index') }}"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-lg hover:shadow-xl">
                    Simpan User
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
