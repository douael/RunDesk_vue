<template>
    <div>
        <div class="row col">
            <h1>Employees</h1>
        </div>

        <div class="row col" v-if="canCreateEmployee">

            <div class="col-8">
                <form style="width:100%">
                    <div class="form-row">
                        <div class="col-12">
                            <label :for="firstname" class="mr-2">{{ labels.firstname }}</label>
                            <input v-model="firstname" type="text" class="form-control">
                        </div>
                        <div class="col-12">
                            <label :for="lastname" class="mr-2">{{ labels.lastname }}</label>
                            <input v-model="lastname" type="text" class="form-control">
                        </div>
                        <div class="col-12">
                            <label :for="site" class="mr-2">{{ labels.site }}</label>
                            <input v-model="site" type="text" class="form-control">
                        </div>


                        <div class="col-12" style="margin-top:10px;">
                            <button @click="createEmployee()" :disabled="lastname.length === 0 || isLoading" type="button" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-4">
                <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                    <button @click="importModal()" type="button" class="btn btn-primary">Import CSV of employee</button>
                </div>
            </div>
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

        <div v-else class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Site</th>
                        <th>Delete</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody >
                    <div v-for="employee in employees" >
                        <employee :id="employee.id" :firstname="employee.firstname" :lastname="employee.lastname" :site="employee.site"></employee>
                    </div>
                </tbody>
            </table>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;"
        id="import">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Import employee with csv </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <input type="file" id="file" ref="file" accept=".csv" @change="onChangeFileUpload" class="input-file">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                    v-on:click="submitForm()">
                    Import
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


</div>
</template>

<script>
    import Employee from '../components/Employee';
    import ErrorMessage from '../components/ErrorMessage';
    import axios from 'axios';

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
                file: '',
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

            submitForm(){
                let formData = new FormData();
                formData.append('file', this.file);
                console.log(formData);
                axios.post('/api/employee/import',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                    ).then(function(data){
                        document.location.reload(true);
                    })
                    .catch(function(){
                        console.log('FAILURE!!');
                    });
                },
                onChangeFileUpload(){
                    this.file = event.target.files[0];
                },
                importModal(id){
                    $('#import').modal();
                },
                createEmployee () {
                    let payload = {firstname: this.$data.firstname, lastname: this.$data.lastname, site: this.$data.site};

                    this.$store.dispatch('employee/createEmployee', payload);
                }

            },
        }
    </script>