import BorrowingAPI from '../api/borrowing';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        borrowings: [],
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
        hasBorrowings(state) {
            return state.borrowings.length > 0;
        },
        borrowings(state) {
            return state.borrowings;
        },
    },
    mutations: {
        ['CREATING_BORROWING'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_BORROWING_SUCCESS'](state, borrowing) {
            state.isLoading = false;
            state.error = null;
            state.borrowings.unshift(borrowing);
            document.location.reload(true);
        },
        ['CREATING_BORROWING_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.borrowings = [];
        },
        ['EDITING_BORROWING'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['EDITING_BORROWING_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['EDITING_BORROWING_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.borrowings = [];
        },
        ['DELETING_BORROWING'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['DELETING_BORROWING_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['DELETING_BORROWING_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.borrowings = [];
        },
        ['FETCHING_BORROWINGS'](state) {
            state.isLoading = true;
            state.error = null;
            state.borrowings = [];
        },
        ['FETCHING_BORROWINGS_SUCCESS'](state, borrowings) {
            state.isLoading = false;
            state.error = null;
            state.borrowings = borrowings;
        },
        ['FETCHING_BORROWINGS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.borrowings = [];
        },
    },
    actions: {
        createBorrowing({ commit }, payload) {
            commit('CREATING_BORROWING');
            // console.log(payload.employee);
            return BorrowingAPI.create(payload.employee, payload.material, payload.date_start, payload.date_end)
                .then(res => commit('CREATING_BORROWING_SUCCESS', res.data))
                .catch(err => commit('CREATING_BORROWING_ERROR', err));
        },
        editBorrowing({ commit }, payload, ) {
            commit('EDITING_BORROWING');
            return BorrowingAPI.edit(payload.id, payload.isActive)
                .then(res => commit('EDITING_BORROWING_SUCCESS'))
                .catch(err => commit('EDITING_BORROWING_ERROR', err));
        },

        deleteBorrowing({ commit }, id, ) {
            commit('DELETING_BORROWING');
            return BorrowingAPI.delete(id)
                .then(res => commit('DELETING_BORROWING_SUCCESS'))
                .catch(err => commit('DELETING_BORROWING_ERROR', err));
        },
        updateBorrowing({ commit }, payload, ) {
            commit('EDITING_BORROWING');
            return BorrowingAPI.update(payload.id, payload.employee, payload.material, payload.date_start, payload.date_end)
                .then(res => commit('EDITING_BORROWING_SUCCESS'))
                .catch(err => commit('EDITING_BORROWING_ERROR', err));
        },
        fetchBorrowings({ commit }) {
            commit('FETCHING_BORROWINGS');
            return BorrowingAPI.getAll()
                .then(res => commit('FETCHING_BORROWINGS_SUCCESS', res.data))
                .catch(err => commit('FETCHING_BORROWINGS_ERROR', err));
        },
        restituteMaterial({ commit }, borrowingId) {
            commit('EDITING_BORROWING');
            return BorrowingAPI.restitute(borrowingId)
                .then(res => commit('EDITING_BORROWING_SUCCESS'))
                .catch(err => commit('EDITING_BORROWING_ERROR', err));
        }
    },
}