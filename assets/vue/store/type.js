import TypeAPI from '../api/type';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        types: [],
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
        hasTypes(state) {
            return state.types.length > 0;
        },
        types(state) {
            return state.types;
        },
    },
    mutations: {
        ['CREATING_TYPE'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_TYPE_SUCCESS'](state, type) {
            state.isLoading = false;
            state.error = null;
            state.types.unshift(type);
        },
        ['CREATING_TYPE_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.types = [];
        },
        ['EDITING_TYPE'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['EDITING_TYPE_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['EDITING_TYPE_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.types = [];
        },
        ['DELETING_TYPE'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['DELETING_TYPE_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['DELETING_TYPE_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.types = [];
        },
        ['FETCHING_TYPES'](state) {
            state.isLoading = true;
            state.error = null;
            state.types = [];
        },
        ['FETCHING_TYPES_SUCCESS'](state, types) {
            state.isLoading = false;
            state.error = null;
            state.types = types;
        },
        ['FETCHING_TYPES_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.types = [];
        },
    },
    actions: {
        createType({ commit }, payload) {
            commit('CREATING_TYPE');
            return TypeAPI.create(payload.name, payload.type)
                .then(res => commit('CREATING_TYPE_SUCCESS', res.data))
                .catch(err => commit('CREATING_TYPE_ERROR', err));
        },
        editType({ commit }, payload, ) {
            commit('EDITING_TYPE');
            return TypeAPI.edit(payload.id, payload.type)
                .then(res => commit('EDITING_TYPE_SUCCESS'))
                .catch(err => commit('EDITING_TYPE_ERROR', err));
        },
        
        deleteType({ commit }, id, ) {
            commit('DELETING_TYPE');
            return TypeAPI.delete(id)
                .then(res => commit('DELETING_TYPE_SUCCESS'))
                .catch(err => commit('DELETING_TYPE_ERROR', err));
        },
        updateType({ commit }, payload, ) {
            commit('EDITING_TYPE');
            return TypeAPI.update(payload.id,payload.name, payload.type)
                .then(res => commit('EDITING_TYPE_SUCCESS'))
                .catch(err => commit('EDITING_TYPE_ERROR', err));
        },
        fetchTypes({ commit }) {
            commit('FETCHING_TYPES');
            return TypeAPI.getAll()
                .then(res => commit('FETCHING_TYPES_SUCCESS', res.data))
                .catch(err => commit('FETCHING_TYPES_ERROR', err));
        },
    },
}