<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Document</title>
  <link rel="stylesheet" href="{{ asset('vendor/ve/css/fixed-ratio.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/ve/css/scene-glb-editor.css') }}">
  <script src="{{ asset('vendor/ve/js/fixed-ratio.js') }}"></script>
  <script src="{{ asset('vendor/ve/js/scene-glb-editor.js') }}"></script>
  <script>
    window.scene = {!! $scene->append('url') !!};
    window.scene.path = '{{ $scene->url }}';
    window.scene.extras = {!! $scene->extras !!};
  </script>
</head>
<body>
  <a class="hidden" id="downloadAnchorElem"></a>
  <div class="full-screen fixed-ratio" style="--width: 1920px; --height: 1080px;">
    <div id="scene-container">
      <div id="scene-canvas"></div>
      <div id="position" class="active">
        <div>
          <label for="scene">Scene</label>
          <input type="text" id="scene" placeholder="x,y,z">
          <label for="camera">camera</label>
          <input type="text" id="camera" placeholder="x,y,z">
          <label for="distance-range">distance-range</label>
          <input type="text" id="distance-range" placeholder="d">
          <label for="polar-range">polar-range</label>
          <input type="text" id="polar-range" placeholder="d">
          <button id="apply" class="w-full">
            <h1>Apply</h1>
          </button>
        </div>
      </div>
      <div id="tools" class="active">
        <div>
          <button id="pan" class="tool">
            <h1>Pan</h1>
          </button>
          <button id="select" class="tool">
            <h1>Select</h1>
          </button>
          <button id="snapshot" class="tool">
            <h1>Snapshot</h1>
          </button>
          <button id="download" class="w-full">
            <h1>Download</h1>
          </button>
        </div>
        <form class="w-full" action="{{ route("ve-editor.scene.postManage", $scene) }}" method="POST">
          @csrf
          <input type="hidden" name="extras">
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
