<x-ve-layout>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.2/Sortable.min.js" integrity="sha512-ELgdXEUQM5x+vB2mycmnSCsiDZWQYXKwlzh9+p+Hff4f5LA+uf0w2pOp3j7UAuSAajxfEzmYZNOOLQuiotrt9Q==" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.2/Sortable.js" integrity="sha512-85wMDrEBH6URHnv1YFFaSFnh2Rk1wbQ4LdKrN2Km34DPwbBLJUW/bEraiLfknBXfdd9VQtB6z0DGavH100160A==" crossorigin="anonymous"></script>
  <div x-data="{ showModal: false, showCreateModal: false, showUpdateModal: false, showDeleteModal: false, name: '', templateId: '', type: '', requirement: '' }">
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        VE Editor - {{ $folder->folder_type ? 'Content Asset' : 'Static Asset' }} Template ({{ $folder->name }})
    </h2>
    </x-slot>

    <div class="flex justify-between">
      <a href="{{ route('ve-editor.folder.getIndex', ['folder-type' => $folder->folder_type]) }}" class="cursor-pointer mx-2 my-2 w-auto bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Back</a>
      <button class="cursor-pointer mx-2 my-2 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" @click="showModal = true; showCreateModal = true;">Create Asset Template</button>
    </div>
    @if(count($folder->assetTemplates) > 1)
    <button id="sort-btn" class="cursor-pointer mx-2 my-2 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Sort assets</button>
    @endif
    <table class="table-auto w-full">
      <thead>
        <tr>
          <th class="px-4 py-2 w-1/5">Name</th>
          <th class="px-4 py-2 w-1/8">Type</th>
          <th class="px-4 py-2 w-1/6">Requirement</th>
          <th class="px-4 py-2 w-auto">Action</th>
        </tr>
      </thead>
      <tbody class="sortable">
        @forelse($folder->assetTemplates()->orderBy('sequence', 'asc')->get() as $template)
        <tr data-id="{{ $template->id }}">
          <td class="border px-4 py-2">{{ $template->name }}</td>
          <td class="border px-4 py-2">{{ $template->typeName }}</td>
          <td class="border px-4 py-2">{{ $template->requirement }}</td>
          <td class="border px-4 py-2">
            <a href="{{ route('ve-editor.asset-template.getShow', $template) }}" class="mx-2 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Manage versions</a>
            @if (count($template->assets))
            <a href="{{ $template->url }}" target="_blank" class="mx-2 w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">View</a>
            <button data-content="{{ $template->url }}" class="click-copy mx-2 w-auto bg-yellow-300 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Copy</button>
            @endif
            <button @click="showModal = true; showUpdateModal = true; name = '{{ $template->name }}'; templateId = {{ $template->id }}; type = {{ $template->file_type }}; requirement = '{{ $template->requirement }}';" class="mx-2 w-auto bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
            @unless (count($template->assets))
            <button @click="showModal = true; showDeleteModal = true; name = '{{ $template->name }}'; templateId = {{ $template->id }}" class="mx-2 w-auto bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
            @endunless
          </td>
        </tr>
        @empty
        <tr>
          <td class="border px-4 py-2" colspan="4">No templates yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="absolute w-screen h-screen left-0 flex justify-center top-0 items-center bg-gray-500 bg-opacity-25" x-show="showModal">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" @click.away="showDeleteModal = false; showCreateModal = false; showUpdateModal = false; showModal = false">
          <form class="px-4 py-2 text-center container" x-show="showCreateModal" action="{{ route('ve-editor.asset-template.postCreate') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex-1 flex items-center my-2">
              <label for="create-name" class="w-28">Template Name</label>
              <input type="text" id="create-name" name="name" class="mx-4 my-2 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex-1 flex items-center my-2 relative">
              <label for="create-type" class="w-28">Type</label>
              <select id="create-type" name="type" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select Type</option>
                <option value="0">Image</option>
                <option value="1">Video</option>
                <option value="2">PDF</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
            <div class="flex-1 flex items-center my-2">
              <label for="create-requirement" class="w-28">Requirements</label>
              <input type="text" id="create-requirement" name="requirement" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex-1 flex items-center my-2">
              <label for="create-file" class="w-28">File (Optional)</label>
              <input type="file" id="create-file" name="file" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex-1 flex items-center my-2">
              <label for="create-dummy" class="w-28">Dummy (Optional)</label>
              <input type="checkbox" id="create-dummy" name="dummy" class="mx-4 shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <input type="hidden" name="folder-id" value="{{ $folder->id }}">
            <input type="submit" value="Create" class="cursor-pointer mx-2 my-2 w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
          <form class="px-4 py-2 text-center container" x-show="showUpdateModal" action="{{ route('ve-editor.asset-template.postUpdate') }}" method="POST">
            @csrf
            <p>Warning: Changing the name after developer has started using this asset will break the program!</p>
            <div class="flex-1 flex items-center my-2">
              <label for="name" class="w-28">Template Name</label>
              <input type="text" id="update-name" name="name" class="mx-4 my-2 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="name">
            </div>
            <div class="flex-1 flex items-center my-2 relative">
              <label for="type" class="w-28">Type</label>
              <select id="update-type" name="type" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="type">
                <option value="">Select Type</option>
                <option value="0">Image</option>
                <option value="1">Video</option>
                <option value="2">PDF</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
            <div class="flex-1 flex items-center my-2">
              <label for="requirement" class="w-28">Requirements</label>
              <input type="text" id="update-requirement" name="requirement" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="requirement">
            </div>
            <input type="hidden" name="template-id" x-model="templateId">
            <input type="submit" value="Update" class="cursor-pointer mx-2 w-auto bg-yellow-300 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
          <form class="px-4 py-2 text-center container" x-show="showDeleteModal" action="{{ route('ve-editor.asset-template.postDelete') }}" method="POST">
            @csrf
            <p>Are you sure you want to delete this folder?</p>
            <input type="text" class="mx-4 flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="name" disabled>
            <input type="hidden" name="template-id" x-model="templateId">
            <input type="submit" value="Delete" class="cursor-pointer mx-2 w-auto bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    let copy;
    var sortable;
    window.addEventListener('load', function () {
      Array.from(document.querySelectorAll('.click-copy')).forEach(el => {
        el.addEventListener('click', function (e) {
          let text = this.dataset.content;
          copy(text);
          alert('Asset link copied to clipboard.');
        });
      });
      let sortBtn = document.querySelector('#sort-btn');
      sortBtn.addEventListener('click', (evt) => {
        sortBtn = evt.currentTarget;
        if (sortBtn.classList.contains('sorting')) {
          sortBtn.innerHTML = 'Sort assets';
          sortBtn.classList.remove('bg-green-500');
          sortBtn.classList.remove('hover:bg-green-700');
          sortBtn.classList.add('bg-blue-500');
          sortBtn.classList.add('hover:bg-blue-700');
          sortBtn.classList.remove('sorting');
          let sequence = sortable.toArray();
          let formData = new FormData();
          formData.append('sequence', sequence);
          const url = "{{ route('ve-editor.asset-template.postSort') }}";
          fetch(url, {
            method : "POST",
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body : formData,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          }).then(
            response => response.text()
          ).then(
            html => console.log(html)
          ).catch(error => {
            console.error('Error:', error);
          });
          sortable.destroy();
        } else {
          sortBtn.innerHTML = 'Save';
          sortBtn.classList.remove('bg-blue-500');
          sortBtn.classList.remove('hover:bg-blue-700');
          sortBtn.classList.add('bg-green-500');
          sortBtn.classList.add('hover:bg-green-700');
          sortBtn.classList.add('sorting');
          sortable = Sortable.create(document.querySelector('.sortable'));
        }
      });
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
</x-ve-layout>