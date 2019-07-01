import axios from 'axios';

export default {
    login (login, password) {
        return axios.post(
            '/api/security/login',
            {
                username: login,
                password: password
            }
        );
    },
    editPassword (id, password) {
        return axios.post(
            '/api/security/editPassword',
            {
                id: id,
                password: password
            }
        );
    },
}