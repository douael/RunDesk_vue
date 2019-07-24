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
    editPassword (id,oldPassword,newPassword,confirmPassword) {
        return axios.post(
            '/api/security/editPassword',
            {
                id: id,
                oldPassword: oldPassword,
                newPassword: newPassword,
                confirmPassword: confirmPassword
            }
        );
    },
    getAll() {
        return axios.get('/api/profils');
    },
}