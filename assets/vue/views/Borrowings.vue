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
                        <div class="row col-12">
                            <div class=" col-6">
                                <label :for="date_start" class="mr-2">{{ labels.date_start }}</label>
                                <input v-model="date_start" type="date" class="form-control">
                            </div>
                            <div class="col-6">
                                <label :for="date_end" class="mr-2">{{ labels.date_end }}</label>
                                <input v-model="date_end" type="date" class="form-control">
                            </div>
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
                  <th>Update</th>
                  <th>Delete</th>
              </tr>
          </thead>
          <tbody >
            <tr  v-for="borrowing in borrowings" >
                <td>{{ borrowing.employee.firstname }}&nbsp; {{ borrowing.employee.lastname }}</td>
                <td>{{ borrowing.material.name }}</td>
                <td>{{ borrowing.date_start}}</td>
                <td>{{ borrowing.date_end}}</td>
                    <!-- <td>
                        <button type="button" class="btn btn-danger" data-toggle="modal"  @click="deleteModal(material.id,material.name)" >
                            <i class="fa fa-trash"></i> Supprimer
                        </button>
                    </td> -->

                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal"  @click="openModal(borrowing.id)">
                            <i class="fa fa-edit"></i> Modifier
                        </button>
                    </td>

                    <td>
                        <button type="button" class="btn btn-danger" data-toggle="modal"  @click="deleteModal(borrowing.id)" >
                            <i class="fa fa-trash"></i> Supprimer
                        </button>
                    </td>
                    <!-- <td>
                        <form >
                        <button type="button" class="btn btn-warning" v-if="!material.isActive" @click="activateMaterial(material.id)">Inactif</button>
                        <button type="button" class="btn btn-success" v-else  @click="unactivateMaterial(material.id)">Actif</button>
                        <input type="hidden" id="id" name="id" class="form-control" :value="material.id">
            
                        </form>
                    </td>   -->              
                </tr>
            </tbody>
        </table>
    </div>
    <div v-for="borrowing in borrowings">
     <div class="modal fade bg-dark" tabindex="-1" role="dialog" aria-hidden="true" :id="'bv-modal-example'+borrowing.id">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Modifier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="col-6">
                            <input v-model="borrowing.date_start" type="date" class="form-control">
                        </div>
                        <div class="col-6">
                            <input v-model="borrowing.date_end" type="date" class="form-control">
                        </div>
                       
                        <div class="col-6">
                          <select class="form-control" name="employee" v-model="borrowing.employee" >
                              <option v-for="Otheremployee in employees" v-bind:value="Otheremployee.id" >
                                  {{ Otheremployee.firstname + " " + Otheremployee.lastname }}
                              </option>
                          </select>
                        </div>
                        <div class="col-6">
                          <select class="form-control" name="material" v-model="borrowing.material" >
                              <option v-for="Othermaterial in materials" v-bind:value="Othermaterial.id" >
                                  {{ Othermaterial.name }}
                              </option>
                          </select>
                        </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                @click="editBorrowing(borrowing.id,borrowing.date_start,borrowing.date_end,borrowing.employee,borrowing.material)">
                Modify
            </button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         style="display: none;"
         :id="'delete-material'+borrowing.id">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Delete Borrowing</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
            Are you sure that you want to delete this borrowing?
            <ul>
              <li >
                {{ borrowing.material.name }}
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                    @click.prevent="deleteBorrowing(borrowing.id)">
              Delete
            </button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
style="display: none;"
:id="'delete-borrowing'+borrowing.id">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" >Delete Borrowing</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        Are you sure that you want to delete this borrowing?
        <ul>
          <li >
            Emprunt de : {{ borrowing.material.name }}
        </li>
    </ul>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
    @click.prevent="deleteBorrowing(borrowing.id)">
    Delete
</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
style="display: none;"
id="import">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" >Import Material with csv </h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
                date_start : '',
                date_end : '',
                material: '',
                labels: {
                    employee: 'Employee',
                    material: 'Materiels',
                    date_start: 'Date de début',
                    date_end: 'Date de fin',
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
                let payload = {employee: this.$data.employee, material: this.$data.material, date_start: this.$data.date_start, date_end: this.$data.date_end};

                this.$store.dispatch('borrowing/createBorrowing', payload);
            },    
            editBorrowing(id,employee,material,date_start,date_end){
                let payload = {id: id, employee:employee, material: material, date_start: date_start, date_end: date_end};

                this.$store.dispatch('borrowing/updateBorrowing', payload);
            },    
            deleteBorrowing (id) {
                this.$store.dispatch('borrowing/deleteBorrowing', id);
            }
        },
    }
</script>