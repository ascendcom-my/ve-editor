import Vapor from './laravel-vapor';
const axios = require('axios');
window.Vapor = new Vapor;

window.addEventListener('load', function () {
    var options = {
        visibility: 'public-read'
    };
  
    document.getElementById('create-btn').addEventListener('click', function () {
        let file = document.getElementById('create-file').files[0];
  
        if (document.getElementById('required-data').dataset.templateType == '2') {
            options.contentDisposition = 'attachment; filename="' + file.name + '"';
        }
        
        window.Vapor.store(file, options).then(function (response) {
            axios.post(document.getElementById('create-form').action, {
                dummy: document.getElementById('create-dummy').checked,
                "template-id": document.getElementById('create-template-id').value,
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
    });
});