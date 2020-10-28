# Bigmom Virtual Event Editor

VE Editor consists of two major parts:
  - Asset Manager
  - Scene Editor

To use on multiple machines, one machine has to host the ve-editor's main part (UI), while other machines will access the main part's API to pull the data.

### Asset Manager

Asset manager's data structure is basically: `Folder > Asset Template > Asset`.
Folder is just a basic group. There are no nested folders because I'm bad at data structures, and I currently don't need them.
Asset template contains the shared attributes of all the assets. That's basically their job.
Assets are more accurately versions of a particular asset.

### Scene Editor

A scene is a 2d or 3d, video or image with clickable areas on it. Bigmom's scene editor currently only supports image, video, and a three.js binary file.

A hotspot is a clickable area. All the fields are regular text values except for Folder. Putting a folder id in the field will transfer the URLs of all the assets in that folder into that particular hotspot, in sequential order.

A placeholder only stores a url field. Do whatever you want with it really.

## Installation

`composer require bigmom/ve-editor`
`php artisan vendor:publish`
`php artisan migrate`

## config

- `main` = Set whether this machine should be main machine.
- `pull_url` = The host machine to pull the data from.
- `guard` = VE Editor uses its own guard, `ve-editor`. By default, this guard uses `session` driver and `users` provider. You can set different values for the guard in here.
- `restrict_usage` = Only allow certain users to access VE Editor.
- `allowed_users` = Specify the emails of the users that can access VE Editor here.
- `api_username` && `api_password` = Allowed user on the host machine.
- `config` = Set running config here. Must be in an associative array format.

## Commands

`php artisan ve:pull` = Pulls data from main VE Editor as specified in `.env` or `config/ve.php`. Usually used in production.

## Routes

Most routes can be accessed from `/ve-editor`. There is one additional route, which is `/pull`. This route can only be accessed from non-main machines. Its function is similar to the command above, but cannot be accessed in production.

## Models

- Bigmom\VeEditor\Models\Asset
- Bigmom\VeEditor\Models\AssetTemplate
- Bigmom\VeEditor\Models\Folder
- Bigmom\VeEditor\Models\FolderHotspot
- Bigmom\VeEditor\Models\Hotspot
- Bigmom\VeEditor\Models\Placeholder
- Bigmom\VeEditor\Models\Scene