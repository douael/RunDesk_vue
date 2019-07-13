import axios from 'axios';

export default {
    create(name) {
        return axios.post(
            '/api/type/create', {
                name: name
            }
        );
    },
    edit(id, name) {
        return axios.post(
            '/api/type/edit', {
                id: id,
                name: name
            }
        );
    },

    delete(id) {
        return axios.post(
            '/api/type/delete', {
                id: id
            }
        );
    },

    update(id, name) {
        return axios.post(
            '/api/type/update', {
                id: id,
                name: name,
            }
        );
    },
    getAll() {
        return axios.get('/api/types');
    },
}