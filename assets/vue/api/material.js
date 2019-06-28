import axios from 'axios';

export default {
    create(name, isActive, serialNumber, category) {
        return axios.post(
            '/api/material/create', {
                name: name,
                isActive: isActive,
                serialNumber: serialNumber,
                category: category
            }
        );
    },
    edit(id, isActive) {
        return axios.post(
            '/api/material/edit', {
                id: id,
                isActive: isActive
            }
        );
    },

    delete(id) {
        return axios.post(
            '/api/material/delete', {
                id: id
            }
        );
    },

    update(id, name, isActive, serialNumber, category) {
        return axios.post(
            '/api/material/update', {
                id: id,
                name: name,
                isActive: isActive,
                serialNumber: serialNumber,
                category: category
            }
        );
    },
    getAll() {
        return axios.get('/api/materials');
    },
}