<template>
    <div>
        <div class="row col">
            <h1>Borrowings</h1>
        </div>

        <div class="row col" v-if="canCreateBorrowing">

            <div class="col-8">
                <form style="width:100%">
                    <div class="form-row">
                        <div class="col-12">
                            <label :for="employee" class="mr-2">{{ labels.employee }}</label>
                            <select class="form-control" name="employee" v-model="employee" >
                                <option v-for="employee in employees" v-bind:value="employee">
                                    {{ employee.firstname + ' ' + employee.lastname }}
                                </option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label :for="material" class="mr-2">{{ labels.material }}</label>
                            <select class="form-control" name="material" v-model="material" >
                                <option v-for="material in materials" v-bind:value="material">
                                    {{ material.name }}
                                </option>
                            </select>
                        </div>
                        
                        <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                            <button @click="createBorrowing()" :disabled="isLoading" type="button" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-employee :error="error"></error-employee>
            <error-material :error="error"></error-material>

        </div>
        <div v-else-if="!hasBorrowing" class="row col">
            No borrowing!
        </div>

       



</div>
</template>

<script>
    import ErrorMessage from '../components/ErrorMessage';

    export default {
        name: 'borrowings',
        components: {
            ErrorMessage,
        },
        data () {
            return {
                employee: '',
                material: '',
                labels: {
                    employee: 'Employee',
                    material: 'Materiels',
                },
            };
        },
        created () {
            this.$store.dispatch('material/fetchMaterials');
            this.$store.dispatch('employee/fetchEmployees');
        },
        computed: {
            isLoading () {
                return this.$store.getters['borrowing/isLoading'];
            },
            hasError () {
                return this.$store.getters['borrowing/hasError'];
            },
            error () {
                return this.$store.getters['borrowing/error'];
            },
            hasBorrowing () {
                return this.$store.getters['borrowing/hasBorrowing'];
            },
            materials () {
                return this.$store.getters['material/materials'];
            },
            borrowings () {
                return this.$store.getters['borrowing/borrowings'];
            },
            
            employees () {
                return this.$store.getters['employee/employees'];
            },
            canCreateBorrowing () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            canEditMaterial () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            }
        },

        methods: {
            openModal(id){
                $('#bv-modal-example'+id).modal();
            },
            importModal(id){
                $('#import').modal();
            },
            deleteModal(id,name){
                $('#delete-borrowing'+id).modal();
            },
            createBorrowing () {
                // console.log(this.$data.employee);
                let payload = {employee: this.$data.employee, material: this.$data.material};

                this.$store.dispatch('borrowing/createBorrowing', payload);
            },            
        },
    }
</script>