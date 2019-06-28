import axios from 'axios';

export default {
    create(lastname, firstname, site) {
        return axios.post(
            '/api/employee/create', {
                lastname: lastname,
                firstname: firstname,
                site: site
            }
        );
    },
    
    delete(id) {
        return axios.post(
            '/api/employee/delete', {
                id: id
            }
        );
    },

    update(id, firstname, lastname, site) {
        return axios.post(
            '/api/employee/update', {
                id: id,
                firstname: firstname,
                lastname: lastname,
                site: site
            }
        );
    },
    getAll() {
        return axios.get('/api/employees');
    },
}