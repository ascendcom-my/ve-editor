import Vapor from './laravel-vapor';
const axios = require('axios');
window.Vapor = new Vapor;

window.addEventListener('load', function () {
    var options = {
        visibility: 'public-read'
    };
  
    document.getElementById('create-btn').addEventListener('click', function () {
        axios.post(document.getElementById('create-form').action, {
            name: document.getElementById('create-name').value,
            type: document.getElementById('create-type').value,
            requirement: document.getElementById('create-requirement').value,
            "folder-id": document.getElementById('create-folder-id').value
        }).then(function (response) {
            var templateId = response['data']['template-id'];
            let file = document.getElementById('create-file').files[0];
            if (file) {
                if (document.getElementById('required-data').dataset.templateType == '2') {
                    options.contentDisposition = 'attachment; filename="' + file.name + '"';
                }
                window.Vapor.store(file, options).then(function (response) {
                    axios.post(document.getElementById('required-data').dataset.createAssetRoute, {
                        dummy: document.getElementById('create-dummy').checked,
                        "template-id": templateId,
                        uuid: response.uuid,
                        key: response.key,
                        bucket: response.bucket
                    }).then(function (response) {
                        location.reload();
                    }).catch(function (error) {
                        console.log(error);
                    });
                }).catch(function (error) {
                    console.log(error);
                });
            } else {
                location.reload();
            }
        }).catch(function (error) {
            console.log(error);
        });
    });
});