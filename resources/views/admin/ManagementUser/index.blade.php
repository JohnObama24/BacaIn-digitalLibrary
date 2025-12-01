@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('header', 'Manajemen User')
@section('subheader', 'Daftar semua User')

@section('page_title', 'Manajemen User')
@section('content')

    <div class="space-y-6">
          <div class="md:flex md:justify-between gap-5 items-center">
             <div class="relative w-full">
                <input type="text" id="searchInput" placeholder="Cari User ..."
                    class="w-full pl-10 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                </div>
             <button type="button"   class="md:w-96 px-6 py-4 bg-primary-blue text-white rounded-lg hover:bg-primary-blue/80 transition flex items-center shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah User Baru
                </button>
          </div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                  <table class="w-full" id="booksTable">
                      <thead class="bg-secondary-gray text-black">
                         <tr>
                                <th class="px-6 py-4 text-center font-semibold">Name</th>
                                <th class="px-6 py-4 text-center font-semibold">Email</th>
                                <th class="px-6 py-4 text-center font-semibold">Role</th>
                                <th class="px-6 py-4 text-center font-semibold">Status</th>
                                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                      </thead>
                         <tbody class="divide-y divide-gray-200">
                                @forelse($users as  $user)
                                    <tr class="hover:bg-gray-50 transition book-row">
                                        <td class="px-6 py-4 text-center ">
                                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center ">
                                            <p class="font-light text-gray-700">{{ $user->email }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center ">
                                            @if ($user->role == 'admin')
                                                <span class=" px-3 bg-[#B200FF] rounded-full ">
                                                    <p class="text-xs font-medium text-[#9800BE]">  {{ $user->role }} </p>
                                                </span>
                                            @elseif ($user->role == 'user')
                                                <span class="px-3  bg-[#0099FF]  rounded-full">
                                                    <p class="text-xs font-medium text-[#0016BE]">  {{ $user->role }} </p>
                                                </span>
                                             @else
                                                 <span class="px-3 bg-[#797979] rounded-full">
                                                    <p class="text-xs font-medium text-[#515151]">  {{ $user->role }} </p>
                                                </span>
                                            @endif

                                        </td>
                                        <td class="px-6 py-4 text-center ">
                                            @if ($user->is_active)
                                                <span class=" px-3 bg-green-100 rounded-full ">
                                                    <p class="text-xs font-medium text-green-700"> Active </p>
                                                </span>
                                            @else
                                                <span class="px-3  bg-red-100  rounded-full">
                                                    <p class="text-xs font-medium text-red-700"> Inactive </p>
                                                </span>
                                            @endif
                                        </td>

                                         <td class="px-6 py-4 text-center ">
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline-block"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm font-medium">
                                                    Hapus user
                                                </button>
                                            </form>
                                         </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <p class="text-gray-500 text-lg">Belum ada User</p>
                                            <button type="button"
                                                class="mt-4 text-indigo-600 hover:text-indigo-700 font-medium">
                                                Tambah User Baru
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse

                         </tbody>
                  </table>
            </div>
    </div>
@endsection
