import EmployeeAPI from '../api/employee';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        employees: [],
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
        hasEmployees(state) {
            return state.employees.length > 0;
        },
        employees(state) {
            return state.employees;
        },
    },
    mutations: {
        ['CREATING_EMPLOYEE'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_EMPLOYEE_SUCCESS'](state, employee) {
            state.isLoading = false;
            state.error = null;
            state.employees.unshift(employee);
        },
        ['CREATING_EMPLOYEE_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.employees = [];
        },
        ['EDITING_EMPLOYEE'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['EDITING_EMPLOYEE_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['EDITING_EMPLOYEE_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.employees = [];
        },
        ['DELETING_EMPLOYEE'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['DELETING_EMPLOYEE_SUCCESS'](state) {
            state.isLoading = false;
            state.error = null;
            document.location.reload(true);
        },
        ['DELETING_EMPLOYEE_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.employees = [];
        },
        ['FETCHING_EMPLOYEES'](state) {
            state.isLoading = true;
            state.error = null;
            state.employees = [];
        },
        ['FETCHING_EMPLOYEES_SUCCESS'](state, employees) {
            state.isLoading = false;
            state.error = null;
            state.employees = employees;
        },
        ['FETCHING_EMPLOYEES_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.employees = [];
        },
    },
    actions: {
        createEmployee({ commit }, payload) {
            commit('CREATING_EMPLOYEE');
            return EmployeeAPI.create(payload.firstname, payload.lastname, payload.site)
                .then(res => commit('CREATING_EMPLOYEE_SUCCESS', res.data))
                .catch(err => commit('CREATING_EMPLOYEE_ERROR', err));
        },
        editEmployee({ commit }, payload, ) {
            commit('EDITING_EMPLOYEE');
            return EmployeeAPI.edit(payload.id, payload.isActive)
                .then(res => commit('EDITING_EMPLOYEE_SUCCESS'))
                .catch(err => commit('EDITING_EMPLOYEE_ERROR', err));
        },

        deleteEmployee({ commit }, id, ) {
            commit('DELETING_EMPLOYEE');
            return EmployeeAPI.delete(id)
                .then(res => commit('DELETING_EMPLOYEE_SUCCESS'))
                .catch(err => commit('DELETING_EMPLOYEE_ERROR', err));
        },
        updateEmployee({ commit }, payload, ) {
            commit('EDITING_EMPLOYEE');
            return EmployeeAPI.update(payload.id, payload.firstname, payload.lastname, payload.site)
                .then(res => commit('EDITING_EMPLOYEE_SUCCESS'))
                .catch(err => commit('EDITING_EMPLOYEE_ERROR', err));
        },
        fetchEmployees({ commit }) {
            commit('FETCHING_EMPLOYEES');
            return EmployeeAPI.getAll()
                .then(res => commit('FETCHING_EMPLOYEES_SUCCESS', res.data))
                .catch(err => commit('FETCHING_EMPLOYEES_ERROR', err));
        },
    },
}