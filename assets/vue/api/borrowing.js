import axios from 'axios';

export default {
    create(employee, material,date_start,date_end) {
        return axios.post(
            '/api/borrowing/create', {
                employee: employee,
                material: material,
                date_start: date_start,
                date_end: date_end
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
    update(id, employee, material,date_start,date_end) {
        return axios.post(
            '/api/borrowing/update', {
                id: id,
                employee: employee,
                material: material,
                date_start: date_start,
                date_end: date_end
            }
        );
    },
    getAll() {
        return axios.get('/api/borrowings');
    },
    
}