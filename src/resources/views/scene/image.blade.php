<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Document</title>
  <link rel="stylesheet" href="{{ asset('vendor/ve/css/fixed-ratio.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/ve/css/scene-image-editor.css') }}">
  <script src="{{ asset('vendor/ve/js/fixed-ratio.js') }}"></script>
  <script src="{{ asset('vendor/ve/js/scene-image-editor.js') }}"></script>
  <script>
    window.scene = {!! $scene !!};
  </script>
</head>
<body>
  <a class="hidden" id="downloadAnchorElem"></a>
  <div class="full-screen fixed-ratio" style="--width: 1920px; --height: 1080px;">
    <div id="scene-container">
      @if($scene->type == 0)
      <img src="{{ $scene->url }}">
      @else
      <video muted autoplay playsinline controls="off" src="{{ $scene->url }}"></video>
      @endif
      <svg id="svg-layer">
        @foreach ($scene->hotspots as $hotspot)
        {{ $hotspot }}
        @include('veeditor::scene.path', [
          'id' => $hotspot->id ?? '',
          'type' => 'hotspot',
          'color' => 'blue',
          'region' => json_decode($hotspot->position),
          'name' => $hotspot->name ?? '',
          'meta' => $hotspot->meta ?? '',
          'medias' => $hotspot->medias ?? '',
          'folders' => $hotspot->folders ? implode(',', $hotspot->folders->pluck('id')->toArray()) : '',
        ])
        @endforeach
        @foreach($scene->placeholders as $placeholder)
        @include('veeditor::scene.path', [
          'type' => 'placeholder',
          'color' => 'red',
          'region' => json_decode($placeholder->position),
          'url' => $placeholder->url ?? '',
        ])
        @endforeach
      </svg>
      <div id="tools" class="active">
        <div>
          <button id="create-hotspot" class="tool">
            <h1>Create Hotspot</h1>
          </button>
          <button id="create-placeholder" class="tool">
            <h1>Create Placeholder</h1>
          </button>
          <button id="select" class="tool">
            <h1>Select</h1>
          </button>
          <button id="delete" class="tool">
            <h1>Delete</h1>
          </button>
          <button id="download" class="w-full">
            <h1>Download</h1>
          </button>
        </div>
        <form class="w-full" action="{{ route("ve-editor.scene.postManage", $scene) }}" method="POST">
          @csrf
          <input type="hidden" name="hotspots">
          <input type="hidden" name="placeholders">
          <button class="w-full" type="submit" id="save">
            <h1>Save</h1>
          </button>
        </form>
      </div>
      <div id="hotspot-form">
        <label for="name">Name</label>
        <input id="name" type="text" name="name">
        <label for="meta">Meta</label>
        <input id="meta" type="text" name="meta">
        <label for="folders">Folders</label>
        <input id="folders" type="text" name="folders">
        <label for="medias">Medias</label>
        <textarea id="medias" name="medias" cols="30" rows="10"></textarea>
        <button id="save-hotspot">Save Hotspot</button>
      </div>
      <div id="placeholder-form">
        <label for="url">Url</label>
        <input id="url" type="text" name="url">
        <button id="save-placeholder">Save Placeholder</button>
      </div>
    </div>
  </div>
  <div id="toggle-mode">
  </div>
</body>
</html>
