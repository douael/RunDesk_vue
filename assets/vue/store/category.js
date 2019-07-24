import CategoryAPI from '../api/category';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        categorys: [],
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
        hasCategorys(state) {
            return state.categorys.length > 0;
        },
        categorys(state) {
            return state.categorys;
        },
    },
    mutations: {
        ['CREATING_CATEGORY'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_CATEGORY_SUCCESS'](state, category) {
            state.isLoading = false;
            state.error = null;
            state.categorys.unshift(category);
        },
        ['CREATING_CATEGORY_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.categorys = [];
        },
        ['EDITING_CATEGORY'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['EDITING_CATEGORY_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['EDITING_CATEGORY_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.categorys = [];
        },
        ['DELETING_CATEGORY'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['DELETING_CATEGORY_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['DELETING_CATEGORY_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.categorys = [];
        },
        ['FETCHING_CATEGORYS'](state) {
            state.isLoading = true;
            state.error = null;
            state.categorys = [];
        },
        ['FETCHING_CATEGORYS_SUCCESS'](state, categorys) {
            state.isLoading = false;
            state.error = null;
            state.categorys = categorys;
        },
        ['FETCHING_CATEGORYS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.categorys = [];
        },
    },
    actions: {
        createCategory({ commit }, payload) {
            commit('CREATING_CATEGORY');
            return CategoryAPI.create(payload.name, payload.type)
                .then(res => commit('CREATING_CATEGORY_SUCCESS', res.data))
                .catch(err => commit('CREATING_CATEGORY_ERROR', err));
        },
        editCategory({ commit }, payload, ) {
            commit('EDITING_CATEGORY');
            return CategoryAPI.edit(payload.id, payload.type)
                .then(res => commit('EDITING_CATEGORY_SUCCESS'))
                .catch(err => commit('EDITING_CATEGORY_ERROR', err));
        },
        
        deleteCategory({ commit }, id, ) {
            commit('DELETING_CATEGORY');
            return CategoryAPI.delete(id)
                .then(res => commit('DELETING_CATEGORY_SUCCESS'))
                .catch(err => commit('DELETING_CATEGORY_ERROR', err));
        },
        updateCategory({ commit }, payload, ) {
            commit('EDITING_CATEGORY');
            return CategoryAPI.update(payload.id,payload.name, payload.type)
                .then(res => commit('EDITING_CATEGORY_SUCCESS'))
                .catch(err => commit('EDITING_CATEGORY_ERROR', err));
        },
        fetchCategorys({ commit }) {
            commit('FETCHING_CATEGORYS');
            return CategoryAPI.getAll()
                .then(res => commit('FETCHING_CATEGORYS_SUCCESS', res.data))
                .catch(err => commit('FETCHING_CATEGORYS_ERROR', err));
        },
    },
}