import axios from 'axios';

export default {
    create(employee, material) {
        return axios.post(
            '/api/borrowing/create', {
                employee: employee,
                material: material
            }
        );
    },
    delete(id) {
        return axios.post(
            '/api/borrowing/delete', {
                id: id
            }
        );
    },
    update(id, employee, material) {
        return axios.post(
            '/api/borrowing/update', {
                id: id,
                employee: employee,
                material: material
            }
        );
    },
    getAll() {
        return axios.get('/api/borrowings');
    },
    
}