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
    
    delete(id) {
        return axios.post(
            '/api/material/delete', {
                id: id
            }
        );
    },
    
    update(id, name, isActive, serialNumber) {
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