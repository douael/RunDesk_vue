<template>
    <div>
        <div class="row col darkBlue-bg green no-margin">
            <h1>Employés</h1>
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
                            <button @click="createEmployee()" :disabled="lastname.length === 0 || isLoading || firstname.length === 0|| site.length === 0" type="button" class="btn btn-primary">Créer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-4">
                <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                    <button @click="importModal()" type="button" class="btn btn-primary">Importation CSV des employés</button>
                </div>
            </div>
        </div>
        <div class="well">
            <form class="form-inline">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    <input type="text"  placeholder="Rechercher" name="recherche" class="form-control" v-model="search">
                </div>
            </form>
        </div>
        <div v-if="isLoading" class="row col">
            <div class="e-loadholder">
                <div class="m-loader">
                    <span class="e-text">Loading</span>
                </div>
            </div>
            <div id="particleCanvas-Blue"></div>
            <div id="particleCanvas-White"></div>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-lastname :error="error"></error-lastname>

        </div>
        <div v-else-if="!hasEmployees" class="row col">
            Aucun employé !
        </div>

        <div v-else class="table-responsive">
             <!-- Pagination -->
    <nav aria-label="Page navigation example">

  <ul class="pagination">
        <li class="page-item"><a class="page-link" href="#" @click="prevPage()">Précedent</a></li>
        <li v-for="pageNumber in totalPages" class="page-item">

            <a class="page-link" href="#" @click="setPage(pageNumber-1)"> {{ pageNumber }} </a>
        </li>
        <li class="page-item"><a class="page-link" href="#" @click="nextPage()">Suivant</a></li>
    </ul> 
    </nav>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Site</th>
                        <th>Modifier</th>
                    </tr>
                </thead>
                <tbody >
                    <tr v-for="employee in filteredList" >
                        <td>{{employee.lastname }}</td>
                        <td>{{employee.firstname }}</td>
                        <td>{{employee.site }}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal"   @click="openModal(employee.id)">
                                <i class="fa fa-edit"></i> Modifier
                            </button>
                        </td>                      </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" id="import">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" >Importation CSV des employés</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
                        </div>
                        <div class="modal-body">
                            Format du csv :<br><br>
                            Prénom<strong>,</strong> Nom<strong>,</strong> Site d'appartenance<br>
                            Prénom<strong>,</strong> Nom<strong>,</strong> Site d'appartenance<br><br>

                            <a type="button" class="btn btn-light waves-effect waves-light" href="/modal_forEmployees.csv">
                                Telecharger modele
                            </a><br><br>
                            <input type="file" id="file" ref="file" accept=".csv" @change="onChangeFileUpload" class="input-file">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal" onclick="javascript:window.location.reload()">Annuler</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal" v-on:click="submitForm()">
                                Importer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-for="employee in employees">
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" :id="'bv-modal-example'+employee.id">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" >Modifier</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <div class="col-6">
                                        <input v-model="employee.lastname" type="text" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <input v-model="employee.firstname" type="text" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <input v-model="employee.site" type="text" class="form-control">
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal" onclick="javascript:window.location.reload()">Annuler</button>
                                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" :disabled="employee.lastname.length === 0 || employee.firstname.length === 0|| employee.site.length === 0"  @click="editEmployee(employee.id,employee.firstname,employee.lastname,employee.site)">
                                    Modifier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" :id="'delete-employee'+employee.id">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" >Suppression d'employé</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
                            </div>
                            <div class="modal-body">
                                Etes -vous sûr de vouloir supprimer cet employé ?
                                <ul>
                                    <li >
                                        {{ employee.firstname }} {{ employee.lastname }}
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal" onclick="javascript:window.location.reload()">Annuler</button>
                                <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal" @click.prevent="deleteEmployee(employee.id)">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                    resultCount: 0,
                    size: 10,
                    pageNumber: 0,
                    search: '',
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
                totalPages () {
                    this.resultCount = this.employees.length;
                    console.log(this.pageNumber);
                    console.log(Math.ceil(this.resultCount / this.size) + " totalPages");
                    return Math.ceil(this.resultCount / this.size); 
                },
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
                filteredList() {
                    const start = this.pageNumber * this.size,
                    end = start + this.size;
                    return this.employees.filter(employee => {
                        return employee.firstname.toLowerCase().includes(this.search.toLowerCase()) ||
                        employee.lastname.toLowerCase().includes(this.search.toLowerCase()) ||
                        employee.site.toLowerCase().includes(this.search.toLowerCase());
                    }).slice(start, end);
                },
                canCreateEmployee () {
                    return this.$store.getters['security/hasRole']('ROLE_FOO');
                },
                canEditEmployee () {
                    return this.$store.getters['security/hasRole']('ROLE_FOO');
                }
            },
            methods: {
                setPage (pageNum) {
                    this.pageNumber = pageNum; 
                },
                nextPage(){
                    console.log(this.pageNumber); 
                    if (this.pageNumber < this.totalPages-1) {
                        this.pageNumber++;
                    }
                },
                prevPage(){
                    console.log(this.pageNumber); 
                    if (this.pageNumber > 0) {
                        this.pageNumber--;
                    }
                },
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
                    },
                    openModal(id){
                        $('#bv-modal-example'+id).modal();
                    },
                    deleteModal(id,lastname){
                        $('#delete-employee'+id).modal();
                    },
                    editEmployee(id,firstname,lastname,site){
                        let payload = {id: id, firstname:firstname, lastname:lastname, site:site};

                        this.$store.dispatch('employee/updateEmployee', payload);
                    },
                    deleteEmployee (id) {
                        this.$store.dispatch('employee/deleteEmployee', id);
                    }

                },

            }
        </script>