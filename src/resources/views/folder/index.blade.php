<x-veeditor::layout>
  <div x-data="{ showModal: false, showUpdateModal: false, showDeleteModal: false, name: '', folderId: '' }">
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('VE Editor - Folder') }} ({{request()->input('folder-type') ? 'Content Asset' : 'Static Asset'}})
    </h2>
    </x-slot>

    <form action="{{ route('ve-editor.folder.getIndex', ['folder-type' => request()->input('folder-type')]) }}" class="w-full flex" method="GET">
      @csrf
      <input type="text" class="flex-1 my-2 mx-2 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="search">
      <button type="submit" class="cursor-pointer mx-2 my-2 w-auto bg-gray-300 hover:bg-gray-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Search</button>
    </form>
    <form method="POST" action="{{ route('ve-editor.folder.postCreate') }}" class="w-full flex px-4 py-2 items-center">
      @csrf
      <div class="flex-1 flex items-center">
        <label for="name" class="w-28">Folder Name</label>
        <input type="text" id="name" name="name" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <input type="hidden" name="folder-type" value="{{ request()->input('folder-type') }}">
      </div>
      <input type="submit" value="Create" class="mx-2 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
    </form>
    <table class="table-auto w-full">
      <thead>
        <tr>
          <th class="px-4 py-2 w-auto">ID</th>
          <th class="px-4 py-2 w-auto">Name</th>
          <th class="px-4 py-2 w-auto">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($folders as $folder)
        <tr>
          <td class="border px-4 py-2">{{ $folder->id }}</td>
          <td class="border px-4 py-2">{{ $folder->name }}</td>
          <td class="border px-4 py-2">
            <a href="{{ route('ve-editor.folder.getShow', $folder) }}" class="mx-2 w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">List assets</a>
            <button @click="showModal = true; showUpdateModal = true; name = '{{ $folder->name }}'; folderId = {{ $folder->id }}" class="mx-2 w-auto bg-yellow-300 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Change name</button>
            @unless(count($folder->assetTemplates))
            <button @click="showModal = true; showDeleteModal = true; name = '{{ $folder->name }}'; folderId = {{ $folder->id }}" class="mx-2 w-auto bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
            @endunless
          </td>
        </tr>
        @empty
        <tr>
          <td class="border px-4 py-2" colspan="3">No folders yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div class="py-3 px-3">
      {{ $folders->withQueryString()->links() }}
    </div>

    <div class="fixed w-screen h-screen left-0 flex justify-center top-0 items-center bg-gray-500 bg-opacity-25" x-show="showModal">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" @click.away="showDeleteModal = false; showUpdateModal = false; showModal = false">
          <form class="px-4 py-2 text-center container" x-show="showUpdateModal" action="{{ route('ve-editor.folder.postUpdate') }}" method="POST">
            @csrf
            <p>Warning: Changing the name after developer has started using this folder will break the program!</p>
            <div class="flex-1 flex items-center my-2">
              <label for="name" class="w-28">Folder Name</label>
              <input type="text" id="name" name="name" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="name">
            </div>
            <input type="hidden" name="folder-id" x-model="folderId">
            <input type="submit" value="Update" class="cursor-pointer mx-2 w-auto bg-yellow-300 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
          <form class="px-4 py-2 text-center container" x-show="showDeleteModal" action="{{ route('ve-editor.folder.postDelete') }}" method="POST">
            @csrf
            <p>Are you sure you want to delete this folder?</p>
            <input type="text" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="name" disabled>
            <input type="hidden" name="folder-id" x-model="folderId">
            <input type="submit" value="Delete" class="cursor-pointer mx-2 w-auto bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
        </div>
      </div>
    </div>
  </div>
</x-veeditor::layout>