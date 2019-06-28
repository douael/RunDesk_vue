import axios from 'axios';

export default {
    create(name, type, quantity) {
        return axios.post(
            '/api/category/create', {
                name: name,
                type: type,
                quantity: quantity
            }
        );
    },
    edit(id, type) {
        return axios.post(
            '/api/category/edit', {
                id: id,
                type: type
            }
        );
    },

    delete(id) {
        return axios.post(
            '/api/category/delete', {
                id: id
            }
        );
    },

    update(id, name, type, quantity) {
        return axios.post(
            '/api/category/update', {
                id: id,
                name: name,
                type: type,
                quantity: quantity
            }
        );
    },
    getAll() {
        return axios.get('/api/categorys');
    },
}