import SecurityAPI from '../api/security';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        isAuthenticated: false,
        roles: [],
        profils: []
    },
    getters: {
        isLoading(state) {
            return state.isLoading;
        },
        hasError(state) {
            return state.error !== null;
        },
        error(state) {
            return state.error;
        },
        isAuthenticated(state) {
            return state.isAuthenticated;
        },
        hasRole(state) {
            return role => {
                return state.roles.indexOf(role) !== -1;
            }
        },

        profils(state) {
            return state.profils;
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
            //document.location.reload(true);

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
        ['FETCHING_PROFIL'](state) {
            state.isLoading = true;
            state.error = null;
            state.profils = [];
        },
        ['FETCHING_PROFIL_SUCCESS'](state, profils) {
            state.isLoading = false;
            state.error = null;
            state.profils = profils;
        },
        ['FETCHING_PROFIL_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.profils = [];
        },
    },
    actions: {
        login({ commit }, payload) {
            commit('AUTHENTICATING');
            return SecurityAPI.login(payload.login, payload.password)
                .then(res => commit('AUTHENTICATING_SUCCESS', res.data))
                .catch(err => commit('AUTHENTICATING_ERROR', err));
        },
        editPassword({ commit }, payload) {
            commit('EDITING_PASSWORD');
            return SecurityAPI.editPassword(payload.id, payload.oldPassword, payload.newPassword, payload.confirmPassword)
                .then(res => commit('EDITING_PASSWORD_SUCCESS'))
                .catch(err => commit('EDITING_PASSWORD_ERROR', err));
        },
        onRefresh({ commit }, payload) {
            commit('PROVIDING_DATA_ON_REFRESH_SUCCESS', payload);
        },
        fetchProfils({ commit }) {
            commit('FETCHING_PROFIL');
            return SecurityAPI.getAll()
                .then(res => commit('FETCHING_PROFIL_SUCCESS', res.data))
                .catch(err => commit('FETCHING_PROFIL_ERROR', err));
        },
    },
}