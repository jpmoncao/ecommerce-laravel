import { Axios } from 'axios';

const api = new Axios({
    baseURL: 'http://127.0.0.1:8000/api/'
});

export default api;