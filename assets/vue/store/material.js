import MaterialAPI from '../api/material';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        materials: [],
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
        hasMaterials(state) {
            return state.materials.length > 0;
        },
        materials(state) {
            return state.materials;
        },
    },
    mutations: {
        ['CREATING_MATERIAL'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_MATERIAL_SUCCESS'](state, material) {
            state.isLoading = false;
            state.error = null;
            state.materials.unshift(material);
        },
        ['CREATING_MATERIAL_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.materials = [];
        },
        ['EDITING_MATERIAL'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['EDITING_MATERIAL_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['EDITING_MATERIAL_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.materials = [];
        },
        ['FETCHING_MATERIALS'](state) {
            state.isLoading = true;
            state.error = null;
            state.materials = [];
        },
        ['FETCHING_MATERIALS_SUCCESS'](state, materials) {
            state.isLoading = false;
            state.error = null;
            state.materials = materials;
        },
        ['FETCHING_MATERIALS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.materials = [];
        },
    },
    actions: {
        createMaterial({ commit }, payload) {
            commit('CREATING_MATERIAL');
            return MaterialAPI.create(payload.name, payload.isActive, payload.serialNumber)
                .then(res => commit('CREATING_MATERIAL_SUCCESS', res.data))
                .catch(err => commit('CREATING_MATERIAL_ERROR', err));
        },
        editMaterial({ commit }, payload, ) {
            commit('EDITING_MATERIAL');
            return MaterialAPI.edit(payload.id, payload.isActive)
                .then(res => commit('EDITING_MATERIAL_SUCCESS'))
                .catch(err => commit('EDITING_MATERIAL_ERROR', err));
        },
        fetchMaterials({ commit }) {
            commit('FETCHING_MATERIALS');
            return MaterialAPI.getAll()
                .then(res => commit('FETCHING_MATERIALS_SUCCESS', res.data))
                .catch(err => commit('FETCHING_MATERIALS_ERROR', err));
        },
    },
}