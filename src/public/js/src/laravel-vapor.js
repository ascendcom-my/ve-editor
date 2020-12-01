const axios = require('axios')

export default class Vapor
{
    /**
     * Store a file in S3 and return its UUID, key, and other information.
     */
    async store(file, options = {}) {
        const response = await axios.post('/ve-editor/vapor/signed-storage-url', {
            'bucket': options.bucket || '',
            'content_type': options.contentType || file.type,
            'expires': options.expires || '',
            'visibility': options.visibility || '',
            'content_disposition': options.contentDisposition || ''
        }, {
            baseURL: options.baseURL || null,
            headers: options.headers || {},
            ...options.options
        });

        let headers = response.data.headers;

        if ('Host' in headers) {
            delete headers.Host;
        }

        //spent a whole work day tryna figure out how the hell the array works as a header, but gave up and forcibly extracted it out q.q
        for (var key in headers) {
            if (typeof headers[key] === 'object') {
                headers[key] = headers[key][0];
            }
        }

        if (typeof headers === 'object') {
            
        }

        if (typeof options.progress === 'undefined') {
            options.progress = () => {};
        }

        const cancelToken = options.cancelToken || ''

        await axios.put(response.data.url, file, {
            cancelToken: cancelToken,
            headers: headers,
            onUploadProgress: (progressEvent) => {
                options.progress(progressEvent.loaded / progressEvent.total);
            }
        })

        response.data.extension = file.name.split('.').pop()

        return response.data;
    }
}
