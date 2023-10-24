import axios from 'axios'
import { message } from 'laravel-mix/src/Log';

axios.defaults.baseURL = 'https://cfl.sticssoluciones.com/api';

axios.interceptors.response.use(null, error => {
    const expectedError = error.response && error.response.status >= 400 && error.response.status < 500

    if (!expectedError) {
        console.log('Logging the error: ', error.message)
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message
        })
    }

    return Promise.reject(error)
})


export default {
    get: axios.get,
    post: axios.post,
    put: axios.put,
    delete: axios.delete
}