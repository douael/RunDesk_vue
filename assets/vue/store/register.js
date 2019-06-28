import RegisterAPI from '../api/register';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        registers: [],
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
        hasRegisters(state) {
            return state.registers.length > 0;
        },
        registers(state) {
            return state.registers;
        },
    },
    mutations: {
        ['CREATING_USER'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_USER_SUCCESS'](state, register) {
            state.isLoading = false;
            state.error = null;
            state.registers.unshift(register);
        },
        ['CREATING_USER_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.registers = [];
        },
        ['EDITING_USER'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['EDITING_USER_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['EDITING_USER_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.registers = [];
        },
        ['DELETING_USER'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['DELETING_USER_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['DELETING_USER_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.registers = [];
        },
        ['FETCHING_USERS'](state) {
            state.isLoading = true;
            state.error = null;
            state.registers = [];
        },
        ['FETCHING_USERS_SUCCESS'](state, registers) {
            state.isLoading = false;
            state.error = null;
            state.registers = registers;
        },
        ['FETCHING_USERS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.registers = [];
        },
    },
    actions: {
        createRegister({ commit }, payload) {
            commit('CREATING_USER');
            return RegisterAPI.create(payload.login, payload.password)
                .then(res => commit('CREATING_USER_SUCCESS', res.data))
                .catch(err => commit('CREATING_USER_ERROR', err));
        },
        editRegister({ commit }, payload, ) {
            commit('EDITING_USER');
            return RegisterAPI.edit(payload.id, payload.isActive)
                .then(res => commit('EDITING_USER_SUCCESS'))
                .catch(err => commit('EDITING_USER_ERROR', err));
        },

        deleteRegister({ commit }, id, ) {
            commit('DELETING_USER');
            return RegisterAPI.delete(id)
                .then(res => commit('DELETING_USER_SUCCESS'))
                .catch(err => commit('DELETING_USER_ERROR', err));
        },
        updateRegister({ commit }, payload, ) {
            commit('EDITING_USER');
            return RegisterAPI.update(payload.login, payload.password)
                .then(res => commit('EDITING_USER_SUCCESS'))
                .catch(err => commit('EDITING_USER_ERROR', err));
        },
        fetchRegisters({ commit }) {
            commit('FETCHING_USERS');
            return RegisterAPI.getAll()
                .then(res => commit('FETCHING_USERS_SUCCESS', res.data))
                .catch(err => commit('FETCHING_USERS_ERROR', err));
        },
    },
}