<template>
    <div>
        <div class="row col darkBlue-bg green no-margin">
            <h1>Emprunts</h1>
        </div>

        <div class="row col" v-if="canCreateBorrowing">

            <div class="col-8">
                <form style="width:100%">
                    <div class="form-row">
                        <div class="col-12">
                            <label :for="employee" class="mr-2">{{ labels.employee }}</label>
                            <v-select name="employee" :options="employees" label="fistname'"+"' '"+"'lastname" v-model="employee" >
                            </v-select>
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
                            <div class="col-6">
                                <!--label :for="date_start" class="mr-2">{{ labels.date_start }}</label>
                                <input v-model="date_start" type="date" class="form-control"-->
                                
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="label-control mr-2" :for="date_start" >{{ labels.date_start }}</label>
                    <input type="date" class="form-control datetimepicker" v-model="date_start" disabled>
                  </div>
                </div>
              </div>

                            </div>
                            <div class="col-6">
                                
                                <label :for="date_end" class="mr-2">{{ labels.date_end }}</label>
                                <datepicker
                                    v-model="date_end"
                                    :format="DatePickerFormat"
                                    :disabledDates="disabledDates"
                                    >
                                </datepicker>
                                <!-- <input v-model="date_end" type="date" id="datepicker" class="form-control"> -->
                            </div>
                        </div>
                        
                        
                        <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                            <button @click="createBorrowing()" :disabled="date_start.length === 0 ||date_end.length === 0 ||material.length === 0 ||employee.length === 0 ||isLoading" type="button" class="btn btn-primary">Créer</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Chargement...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-employee :error="error"></error-employee>
            <error-material :error="error"></error-material>

        </div>
        <div v-else-if="!hasBorrowings" class="row col">
            Pas d'emprunt enregistré !
        </div>
        <div v-else class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Employés</th>
                  <th>Matériels</th>
                  <th>Date de début</th>
                  <th>Date de fin</th>
                  <th>Supprimer</th>
              </tr>
          </thead>
          <tbody >
            <tr  v-for="borrowing in borrowings" >
                <td>{{ borrowing.employee.firstname }}&nbsp; {{ borrowing.employee.lastname }}</td>
                <td>{{ borrowing.material.name }}</td>
                <td>{{ borrowing.dateStart | formatDate}}</td>
                <td>{{ borrowing.dateEnd | formatDate}}</td>


                <td>
                    <button type="button" class="btn btn-danger" data-toggle="modal"  @click="deleteModal(borrowing.id)" >
                        <i class="fa fa-trash"></i> Supprimer
                    </button>
                </td>            
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

              <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                @click="editBorrowing(borrowing.id,borrowing.date_start,borrowing.date_end,borrowing.employee,borrowing.material)">
                Modifier
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
        <h4 class="modal-title" >Suppression d'emprunt</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        Etes-vous sûr de vouloir supprimer cet emprunt ?
        <ul>
          <li >
            Emprunt de : {{ borrowing.material.name }}
        </li>
    </ul>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
    @click.prevent="deleteBorrowing(borrowing.id)">
    Supprimer
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
    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
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
</div>
</template>

<script>
    import ErrorMessage from '../components/ErrorMessage';
    import moment from 'moment'
    import Vue from 'vue';
    import VueSelect from 'vue-select';
    import Datepicker from "vuejs-datepicker";

    Vue.component('v-select', VueSelect)
    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(String(value)).format('DD/MM/YYYY')
        }
    });
    export default {
        name: 'borrowings',
        components: {
            ErrorMessage,
            Datepicker
        },
        data () {
            return {
                employee: '',
                date_start : new Date().toISOString().slice(0,10),
                date_end : '',
                material: '',
                labels: {
                    employee: 'Employé',
                    material: 'Materiels',
                    date_start: 'Date de début',
                    date_end: 'Date de fin',
                },
                DatePickerFormat: "dd/MM/yyyy",
                disabledDates: {
                    to: new Date(Date.now() - 8640000)
                }
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

    var dateToday = new Date(); 
$(function() {
    $( "#datepicker" ).datepicker({
        numberOfMonths: 3,
        showButtonPanel: true,
        minDate: dateToday
    });
});
</script>
