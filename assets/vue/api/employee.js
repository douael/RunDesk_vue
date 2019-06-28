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

    update(id, lastname, firstname, site) {
        return axios.post(
            '/api/employee/update', {
                id: id,
                lastname: lastname,
                firstname: firstname,
                site: site
            }
        );
    },
    getAll() {
        return axios.get('/api/employees');
    },
}