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
    editPassword (password) {
        return axios.post(
            '/api/security/editPassword',
            {
                password: password
            }
        );
    },
}