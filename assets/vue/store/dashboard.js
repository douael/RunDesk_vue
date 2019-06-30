import DashboardAPI from '../api/dashboard';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        dashboards: ['bbbbbbb'],
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
        hasDashboards(state) {
            return state.dashboards.length > 0;
        },
        dashboards(state) {
            return state.dashboards;
        },
    },
    mutations: {
        ['FETCHING_DASHBOARDS'](state) {
            state.isLoading = true;
            state.error = null;
            state.dashboards = [];
        },
        ['FETCHING_DASHBOARDS_SUCCESS'](state, dashboards) {
            state.isLoading = false;
            state.error = null;
            state.dashboards = dashboards;
        },
        ['FETCHING_DASHBOARDS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.dashboards = [];
        },
    },
    actions: {
        fetchDashboards({ commit }) {
            commit('FETCHING_DASHBOARDS');
            return DashboardAPI.getAll()
                .then(res => commit('FETCHING_DASHBOARDS_SUCCESS', res.data))
                .catch(err => commit('FETCHING_DASHBOARDS_ERROR', err));
        },
    },
}