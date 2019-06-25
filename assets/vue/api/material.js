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
    edit(id, name, isActive, serialNumber) {
        return axios.post(
            '/api/material/update', {
                id: id,
                name: name,
                isActive: isActive,
                serialNumber: serialNumber
            }
        );
    },
    getAll() {
        return axios.get('/api/materials');
    },
}