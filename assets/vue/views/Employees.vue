<template>
    <div>
        <div class="row col">
            <h1>Employees</h1>
        </div>

        <div class="row col" v-if="canCreateEmployee">
            <form style="width:100%">
                <div class="form-row">
                    <div class="col-12">
                        <div class="col-6">
                            <label :for="firstname" class="mr-2">{{ labels.firstname }}</label>
                            <input v-model="firstname" type="text" class="form-control">
                        </div>
                        <div class="col-6">
                            <label :for="lastname" class="mr-2">{{ labels.lastname }}</label>
                            <input v-model="lastname" type="text" class="form-control">
                        </div>
                        <div class="col-6">
                            <label :for="site" class="mr-2">{{ labels.site }}</label>
                            <input v-model="site" type="text" class="form-control">
                        </div>
                        
                        <!-- <div class="col-6">
                            <label :for="user" class="mr-2">{{ labels.user }}</label>
                            <select class="form-control" name="user_id" v-model="user_id" >
                                <option v-for="user in users" v-bind:value="user_id">
                                {{ user.id }}
                                </option>
                            </select>
                        </div> -->
                        <div class="col-12" style="margin-top:10px;">
                            <button @click="createEmployee()" :disabled="lastname.length === 0 || isLoading" type="button" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-lastname :error="error"></error-lastname>

        </div>
        <div v-else-if="!hasEmployees" class="row col">
            No employees!
        </div>

        <div v-else v-for="employee in employees" class="row col">
                <employee :id="employee.id" :firstname="employee.firstname" :lastname="employee.lastname" :site="employee.site"></employee>
                
               
        </div>
    </div>
</template>

<script>
    import Employee from '../components/Employee';
    import ErrorMessage from '../components/ErrorMessage';

    export default {
        name: 'employees',
        components: {
            Employee,
            ErrorMessage,
        },
        data () {
            return {
                firstname: '',
                lastname: '',
                site: '',
                id: '',
                labels: {
                    firstname: 'Prénom',
                    lastname: 'Nom',
                    site: 'Site d\'appartenance'
        },
            };
        },
        created () {
            this.$store.dispatch('employee/fetchEmployees');
        },
        computed: {
            isLoading () {
                return this.$store.getters['employee/isLoading'];
            },
            hasError () {
                return this.$store.getters['employee/hasError'];
            },
            error () {
                return this.$store.getters['employee/error'];
            },
            hasEmployees () {
                return this.$store.getters['employee/hasEmployees'];
            },
            employees () {
                return this.$store.getters['employee/employees'];
            },
            
            canCreateEmployee () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            canEditEmployee () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            }
        },
        methods: {
            createEmployee () {
                let payload = {firstname: this.$data.firstname, lastname: this.$data.lastname, site: this.$data.site};

                this.$store.dispatch('employee/createEmployee', payload);
            }
            
        },
    }
</script>