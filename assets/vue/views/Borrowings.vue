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
        <div v-else-if="!hasBorrowings" class="row col">
            No borrowing!
        </div>
        <div v-else class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Material</th>
                  <th>Date de debut</th>
                  <th>Date de fin</th>
                </tr>
              </thead>
              <tbody >
                  <tr  v-for="borrowing in borrowings" >
                    <td>{{ borrowing.employee.firstname }}&nbsp; {{ borrowing.employee.lastname }}</td>
                    <td>{{ borrowing.material.name }}</td>
                    <td>{{borrowing.date_start}}</td>
                    <td>{{borrowing.date_end}}</td>
                    <!-- <td>
                        <button type="button" class="btn btn-danger" data-toggle="modal"  @click="deleteModal(material.id,material.name)" >
                            <i class="fa fa-trash"></i> Supprimer
                        </button>
                    </td>
                  
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal"  @click="openModal(material.id)">
                            <i class="fa fa-edit"></i> Modifier
                        </button>
                    </td>
                    <td>
                        <form >
                        <button type="button" class="btn btn-warning" v-if="!material.isActive" @click="activateMaterial(material.id)">Inactif</button>
                        <button type="button" class="btn btn-success" v-else  @click="unactivateMaterial(material.id)">Actif</button>
                        <input type="hidden" id="id" name="id" class="form-control" :value="material.id">
            
                        </form>
                    </td>        -->         </tr></tbody>
            </table>
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
            this.$store.dispatch('borrowing/fetchBorrowings');
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
            hasBorrowings () {
                return this.$store.getters['borrowing/hasBorrowings'];
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