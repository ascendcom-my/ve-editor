<path type="{{ $type }}"
  data-x="{{ $region->x }}"
  data-y="{{ $region->y }}"
  data-w="{{ $region->w }}"
  data-h="{{ $region->h }}"
  fill="{{ $color }}"
  opacity="0.5"
  @if(isset($id)) data-id="{{ $id }}" @endif
  @if(isset($url)) data-url="{{ $url }}" @endif
  @if(isset($name)) data-name="{{ $name }}" @endif
  @if(isset($meta)) data-meta="{{ $meta }}" @endif
  @if(isset($folders)) data-folders="{{ $folders }}" @endif
  @if(isset($medias)) data-medias="{{ $medias }}" @endif
d="
  M {{ $region->x }} {{ $region->y }}
  h {{ $region->w }}
  v {{ $region->h }}
  h {{ -$region->w }}
  Z
"
></path>
