<x-veeditor::layout>
  <div x-data="{ showModal: false, showCreateModal: false, showUpdateModal: false, showDeleteModal: false, name: '', type: '', sceneId: '' }">
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        VE Editor - Scene  
      </h2>
    </x-slot>

    <form action="{{ route('ve-editor.scene.getIndex') }}" class="w-full flex" method="GET">
      @csrf
      <input type="text" class="flex-1 my-2 mx-2 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="search">
      <button type="submit" class="cursor-pointer mx-2 my-2 w-auto bg-gray-300 hover:bg-gray-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Search</button>
    </form>
    <div class="flex justify-end">
      <button class="cursor-pointer mx-2 my-2 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" @click="showModal = true; showCreateModal = true;">Create Scene</button>
    </div>
    <table class="table-auto w-full">
      <thead>
        <tr>
          <th class="px-4 py-2 w-auto">ID</th>
          <th class="px-4 py-2 w-auto">Name</th>
          <th class="px-4 py-2 w-auto">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($scenes as $scene)
        <tr>
          <td class="border px-4 py-2">{{ $scene->id }}</td>
          <td class="border px-4 py-2">{{ $scene->name }}</td>
          <td class="border px-4 py-2">
            <a href="{{ route('ve-editor.scene.getShow', $scene) }}" target="_blank" class="mx-2 w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Manage scene</a>
            <button @click="showModal = true; showUpdateModal = true; name = '{{ $scene->name }}'; type = '{{ $scene->type }}'; sceneId = {{ $scene->id }}" class="mx-2 w-auto bg-yellow-300 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
            <button @click="showModal = true; showDeleteModal = true; name = '{{ $scene->name }}'; sceneId = {{ $scene->id }}" class="mx-2 w-auto bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
          </td>
        </tr>
        @empty
        <tr>
          <td class="border px-4 py-2" colspan="3">No scenes yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div class="py-3 px-3">
      {{ $templates->withQueryString()->links() }}
    </div>

    <div class="fixed w-screen h-screen left-0 flex justify-center top-0 items-center bg-gray-500 bg-opacity-25" x-show="showModal">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" @click.away="showCreateModal = false; showDeleteModal = false; showUpdateModal = false; showModal = false">
          <form class="px-4 py-2 text-center container" x-show="showCreateModal" action="{{ route('ve-editor.scene.postCreate') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex-1 flex items-center my-2">
              <label for="create-name" class="w-28">Name</label>
              <input type="text" id="create-name" name="name" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex-1 flex items-center my-2 relative">
              <label for="create-type" class="w-28">Type</label>
              <select id="create-type" name="type" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select Type</option>
                <option value="0">Scene 2D</option>
                <option value="1">3D Model</option>
                <option value="2">Scene 2D (Video)</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
            <div class="flex-1 flex items-center my-2">
              <label for="create-file" class="w-28">File</label>
              <input type="file" id="create-file" name="file" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <input type="submit" value="Create" class="cursor-pointer mx-2 my-2 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
          <form class="px-4 py-2 text-center container" x-show="showUpdateModal" action="{{ route('ve-editor.scene.postUpdate') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <p>Warning: Changing anything after developer has started using this scene will break the program!</p>
            <div class="flex-1 flex items-center my-2">
              <label for="name" class="w-28">Scene Name</label>
              <input type="text" id="update-name" name="name" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="name" required>
            </div>
            <div class="flex-1 flex items-center my-2 relative">
              <label for="update-type" class="w-28">Type</label>
              <select id="update-type" name="type" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="type" required>
                <option value="">Select Type</option>
                <option value="0">Scene 2D</option>
                <option value="1">3D Model</option>
                <option value="2">Scene 2D (Video)</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
            <div class="flex-1 flex items-center my-2">
              <label for="create-file" class="w-28">New File (Optional)</label>
              <input type="file" id="update-file" name="file" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <input type="hidden" name="scene-id" x-model="sceneId">
            <input type="submit" value="Update" class="cursor-pointer mx-2 w-auto bg-yellow-300 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
          <form class="px-4 py-2 text-center container" x-show="showDeleteModal" action="{{ route('ve-editor.scene.postDelete') }}" method="POST">
            @csrf
            <p>Are you sure you want to delete this scene?</p>
            <input type="text" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="name" disabled>
            <input type="hidden" name="scene-id" x-model="sceneId">
            <input type="submit" value="Delete" class="cursor-pointer mx-2 w-auto bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
        </div>
      </div>
    </div>
  </div>
</x-veeditor::layout>