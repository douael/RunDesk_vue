import axios from 'axios';

export default {
    create(name, isActive, serialNumber) {
        return axios.post(
            '/api/material/create', {
                name: name,
                isActive: isActive,
                serialNumber: serialNumber
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
    getAll() {
        return axios.get('/api/materials');
    },
}