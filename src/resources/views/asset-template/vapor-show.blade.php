<x-veeditor::layout>
  @push('script')
  <script src="{{ asset('vendor/ve/js/asset-template-show.js') }}" defer></script>
  @endpush
  <input type="hidden" id="required-data" data-template-type="{{ $template->folder->folder_type }}">
  <div x-data="{ showModal: false, showCreateModal: false, showDeleteModal: false, assetId: '' }">
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        VE Editor - {{ $template->folder->type_name }} ({{ $template->name }})
    </h2>
    </x-slot>

    <div class="flex justify-between">
      <a href="{{ route('ve-editor.folder.getShow', $template->folder_id) }}" class="cursor-pointer mx-2 my-2 w-auto bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Back</a>
      <button class="cursor-pointer mx-2 my-2 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" @click="showModal = true; showCreateModal = true;">Create Asset</button>
    </div>
    <table class="table-auto w-full">
      <thead>
        <tr>
          <th class="px-4 py-2 w-1/4">Created at</th>
          <th class="px-4 py-2 w-1/4">Dummy</th>
          <th class="px-4 py-2 w-auto">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($assets as $asset)
        <tr>
          <td class="border px-4 py-2">{{ $asset->updated_at }}@if($loop->first) (Active) @endif</td>
          <td class="border px-4 py-2">@if($asset->dummy) Yes @else No @endif</td>
          <td class="border px-4 py-2">
            <a href="{{ $asset->url }}" target="_blank" class="mx-2 w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">View</a>
            <button data-content="{{ $asset->url }}" class="click-copy mx-2 w-auto bg-yellow-300 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Copy</button>
            <button @click="showModal = true; showDeleteModal = true; assetId = {{ $asset->id }}" class="mx-2 w-auto bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
          </td>
        </tr>
        @empty
        <tr>
          <td class="border px-4 py-2" colspan="4">No assets yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div class="py-3 px-3">
      {{ $assets->withQueryString()->links() }}
    </div>

    <div class="fixed w-screen h-screen left-0 flex justify-center top-0 items-center bg-gray-500 bg-opacity-25" x-show="showModal">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" @click.away="showDeleteModal = false; showCreateModal = false; showUpdateModal = false; showModal = false">
          <form id="create-form" class="px-4 py-2 text-center container" x-show="showCreateModal" action="{{ route('ve-editor.asset.postCreate') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex-1 flex items-center my-2">
              <label for="create-file" class="w-28">File</label>
              <input type="file" id="create-file" name="file" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex-1 flex items-center my-2">
              <label for="create-dummy" class="w-28">Dummy</label>
              <input type="checkbox" id="create-dummy" name="dummy" class="mx-4 shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="1">
            </div>
            <input id="create-template-id" type="hidden" name="template-id" value="{{ $template->id }}">
            <input id="create-btn" type="button" value="Create" class="cursor-pointer mx-2 my-2 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
          <form class="px-4 py-2 text-center container" x-show="showDeleteModal" action="{{ route('ve-editor.asset.postDelete') }}" method="POST">
            @csrf
            <p>Are you sure you want to delete this asset?</p>
            <label for="delete-id">
            <input type="text" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="assetId" disabled>
            <input type="hidden" name="asset-id" x-model="assetId">
            <input type="submit" value="Delete" class="cursor-pointer mx-2 w-auto bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    let copy;
    window.addEventListener('load', function () {
      Array.from(document.querySelectorAll('.click-copy')).forEach(el => {
        el.addEventListener('click', function (e) {
          let text = this.dataset.content;
          copy(text);
          alert('Asset link copied to clipboard.');
        });
      })
    });

    copy = (str) => {
      let el = document.createElement('textarea');
      el.value = str;
      el.style.position = 'absolute';
      el.style.left = '-9999px';
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
    }
  </script>
</x-veeditor::layout>