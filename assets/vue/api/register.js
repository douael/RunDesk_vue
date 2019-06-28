import axios from 'axios';

export default {
    create(login, password, roles) {
        return axios.post(
            '/api/register/create', {
                login: login,
                password: password,
                roles: roles
            }
        );
    },
    
    delete(id) {
        return axios.post(
            '/api/register/delete', {
                id: id
            }
        );
    },

    update(id, login, password, roles) {
        return axios.post(
            '/api/register/update', {
                id: id,
                login: login,
                password: password,
                roles: roles
            }
        );
    },
    getAll() {
        return axios.get('/api/register');
    },
}
