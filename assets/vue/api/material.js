import axios from 'axios';

export default {
    create (name,isActive,serialNum) {
        return axios.post(
            '/api/material/create',
            {
                name: name,
                isActive: isActive,
                serialNum: serialNum
            }
        );
    },
    getAll () {
        return axios.get('/api/materials');
    },
}