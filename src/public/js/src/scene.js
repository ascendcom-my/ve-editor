import Vapor from './laravel-vapor';
const axios = require('axios');
window.Vapor = new Vapor;

window.addEventListener('load', function () {
    document.getElementById('create-btn').addEventListener('click', function () {
        var headers = {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }

        window.Vapor.store(document.getElementById('create-file').files[0], {
            visibility: 'public-read'
        }).then(response => {
            axios.post(document.getElementById('create-form').action, {
                name: document.getElementById('create-name').value,
                type: document.getElementById('create-type').value,
                uuid: response.uuid,
                key: response.key,
                bucket: response.bucket,
            }, headers).then(response => {
                location.reload();
            }).catch(error => {
                console.log(error);
            })
        }).catch(error => {
        console.log(error);
        });
    });

    document.getElementById('update-btn').addEventListener('click', function () {
        var headers = {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        };

        window.Vapor.store(document.getElementById('update-file').files[0], {
            visibility: 'public-read'
        }).then(response => {
            axios.post(document.getElementById('update-form').action, {
                name: document.getElementById('update-name').value,
                type: document.getElementById('update-type').value,
                "scene-id": document.getElementById('update-scene-id').value,
                uuid: response.uuid,
                key: response.key,
                bucket: response.bucket
            }, headers).then(response => {
                location.reload();
            }).catch(error => {
                console.log(error);
            })
        }).catch(error => {
            console.log(error);
        });
    });
})