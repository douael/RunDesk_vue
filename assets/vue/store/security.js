import SecurityAPI from '../api/security';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        isAuthenticated: false,
        roles: [],
        profil: []
    },
    getters: {
        isLoading (state) {
            return state.isLoading;
        },
        hasError (state) {
            return state.error !== null;
        },
        error (state) {
            return state.error;
        },
        isAuthenticated (state) {
            return state.isAuthenticated;
        },
        hasRole (state) {
            return role => {
                return state.roles.indexOf(role) !== -1;
            }
        },
    },
    mutations: {
        ['AUTHENTICATING'](state) {
            state.isLoading = true;
            state.error = null;
            state.isAuthenticated = false;
            state.roles = [];
        },
        ['AUTHENTICATING_SUCCESS'](state, roles) {
            state.isLoading = false;
            state.error = null;
            state.isAuthenticated = true;
            state.roles = roles;
        },

        ['EDITING_PASSWORD'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['EDITING_PASSWORD_SUCCESS'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['AUTHENTICATING_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.isAuthenticated = false;
            state.roles = [];
        },
        ['EDITING_PASSWORD_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
        },
        ['PROVIDING_DATA_ON_REFRESH_SUCCESS'](state, payload) {
            state.isLoading = false;
            state.error = null;
            state.isAuthenticated = payload.isAuthenticated;
            state.roles = payload.roles;
        },
        ['GET_PROFIL'](state) {
            state.isLoading = true;
            state.error = null;
            state.profil = [];
        },
        ['GET_PROFIL_SUCCESS'](state, profil) {
            state.isLoading = false;
            state.error = null;
            state.profil = profil;
        },
        ['GET_PROFIL_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.profil = [];
        },
    },
    actions: {
        login ({commit}, payload) {
            commit('AUTHENTICATING');
            return SecurityAPI.login(payload.login, payload.password)
                .then(res => commit('AUTHENTICATING_SUCCESS', res.data))
                .catch(err => commit('AUTHENTICATING_ERROR', err));
        },
        editPassword ({commit}, payload) {
            commit('EDITING_PASSWORD');
            return SecurityAPI.editPassword(payload.password)
                .then(res => commit('EDITING_PASSWORD_SUCCESS', res.data))
                .catch(err => commit('EDITING_PASSWORD_ERROR', err));
        },
        onRefresh({commit}, payload) {
            commit('PROVIDING_DATA_ON_REFRESH_SUCCESS', payload);
        },
        onRefresh({commit}, payload) {
            commit('PROVIDING_DATA_ON_REFRESH_SUCCESS', payload);
        },
        fetchProfil({ commit }) {
            commit('GET_PROFIL');
            return SecurityAPI.getProfil()
                .then(res => commit('GET_PROFIL_SUCCESS', res.data))
                .catch(err => commit('GET_PROFIL_ERROR', err));
        },
    },
}