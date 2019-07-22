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
                            <v-select :options="availableemployees"  v-model="employee"></v-select>
                        </div>
                        <div class="col-12">
                            <label :for="material" class="mr-2">{{ labels.material }}</label>

                            <v-select :options="availablematerials" v-if="countAvailableMaterials>0" v-model="material"></v-select>
                            <input v-else-if="!isLoading" type="text" disabled class="form-control" value="Aucun materiel n'est disponible">
                        </div>
                        <div class="row col-12">
                            <div class="col-6">
<!--label :for="date_start" class="mr-2">{{ labels.date_start }}</label>
    <input v-model="date_start" type="date" class="form-control"-->

    <div class="row">
        <div class="col-md-6">

            <label :for="date_start" class="mr-2">{{ labels.date_start }}</label>
            <datepicker disabled
            v-model="date_start"
            :format="DatePickerFormat"
            >
        </datepicker>

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
<div class="well">
    <form class="form-inline">
        <!-- <h1><label>Rechercher</label></h1> -->
        <!-- <input placeholder="Rechercher" type="text" name="recherche" class="form-control" v-model="search"> -->
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
    <error-employee :error="error"></error-employee>
    <error-material :error="error"></error-material>

</div>

<div v-else-if="!hasBorrowings" class="row col">
    Pas d'emprunt enregistré !
</div>

<div v-else class="table-responsive" id="demoList">
    <div class="containerTable">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Employés</th>
                    <th>Matériels</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody >
                <tr  v-for="borrowing in filteredList | paginate"> <!-- filterBy search in 'name' -->
                    <td>{{ borrowing.employee.firstname }}&nbsp; {{ borrowing.employee.lastname }}</td>
                    <td>{{ borrowing.material.category.name }} - {{ borrowing.material.name }}</td>
                    <td>{{ borrowing.dateStart | formatDate}}</td>
                    <td>{{ borrowing.dateEnd | formatDate}}</td>

                    <td>
                        <form >
                            <button type="button" class="btn btn-warning" v-if="borrowing.dateRestitution==null" @click="validateModal(borrowing.id)">Valider restitution</button>
                            <span  v-else-if="borrowing.dateRestitution!=null">Restitué le {{borrowing.dateRestitution | formatDate}} à {{borrowing.dateRestitution | formatHour}}
                                <button type="button" target="blank" class="btn btn-primary" @click.prevent="downloadItem('/pdf/borrowing' + borrowing.id + '.pdf')">Telecharger Recu</button>
                            </span>
                            <span  v-else>Inactif</span>
                            <input type="hidden" id="id" name="id" class="form-control" :value="material.id">
                        </form>
                    </td>                
                </tr>
            </tbody>
        </table>
        <ul>
            <li v-for="pageNumber in totalPages">
                <a href="#" @click="setPage(pageNumber)"> {{ pageNumber }} </a>
            </li>
        </ul>
    </div>
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





    <div class="modal fade bg-dark" tabindex="-1" role="dialog" aria-hidden="true" :id="'validate-borrowing'+borrowing.id">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Valider la restitution</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    Etes-vous sûr de vouloir valider la restitution de l'article suivant ?
                    <ul>
                        <li >
                            Nom : {{ borrowing.material.name }}
                            Numero de serie : {{ borrowing.material.serialNumber }}
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                    @click.prevent="availableMaterial(borrowing.material.id,borrowing.id)">
                    Valider
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;"
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
    import axios from 'axios';
    import Autocomplete from 'v-autocomplete'
    import vSelect from 'vue-select'

    Vue.component('v-select', vSelect)

    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(String(value)).format('DD/MM/YYYY')
        }
    });
    Vue.filter('formatHour', function(value) {
        if (value) {
            return moment(String(value)).format('HH:mm')
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
                el: '#demoList',
                data: this.borrowings,
                currentPage: 0,
                itemsPerPage: 5,
                resultCount: 0,
                ccn: null,
                order: 1,
                employee: '',
                search:'',
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
                    to: new Date(Date.now() - 8640000),
                },
                isEditing: false,
            };
        },
        created () {
            this.$store.dispatch('material/fetchMaterials');
            this.$store.dispatch('employee/fetchEmployees');
            this.$store.dispatch('borrowing/fetchBorrowings');
        },
        computed: {
            totalPages () {
                console.log("blabla")
                console.log(Math.ceil(this.resultCount / this.itemsPerPage) + " totalPages");
                return Math.ceil(this.resultCount / this.itemsPerPage); 
            },
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

            availablematerials () {
                var array = this.$store.getters['material/materials'];
                var options = [];
                for(var i = 0; i < array.length; ++i){
                    if(array[i]['available'] == true)
                        options.push(array[i]['id']+' - '+array[i]['name']);
                }
                return options;
            },

            availableemployees () {
                var array = this.$store.getters['employee/employees'];
                var options = [];
                for(var i = 0; i < array.length; ++i){
                    options.push(array[i]['id']+' - '+array[i]['firstname']+' - '+array[i]['lastname']);
                }
                return options;
            },
            borrowings () {
                return this.$store.getters['borrowing/borrowings'];
            },
            filteredList() {
                return this.borrowings.filter(borrowing => {
                    return borrowing.employee.firstname.toLowerCase().includes(this.search.toLowerCase()) ||
                    borrowing.employee.lastname.toLowerCase().includes(this.search.toLowerCase()) ||
                    borrowing.material.category.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    borrowing.material.name.toLowerCase().includes(this.search.toLowerCase());
                });
            },
            paginate (List) {
                console.log("test");
                this.resultCount = this.borrowings.length; 
                console.log(this.resultCount + " Result count"); 
                console.log(this.currentPage + " current page"); 
                console.log(this.itemsPerPage + " items per page"); 
                console.log(this.totalPages + " Total pages 2");
                if (this.currentPage >= this.totalPages) {
                    this.currentPage = Math.max(0, (this.totalPages - 1)); 
                }
                var index = (this.currentPage * this.itemsPerPage); 
                console.log(index + " index"); 
                console.log((index + this.itemsPerPage) + " index 2"); 
                console.log(this.borrowings.slice(index, (index + this.itemsPerPage))); 
                return this.borrowings.slice(index, (index + this.itemsPerPage)); 
            },
            employees () {
                return this.$store.getters['employee/employees'];
            },
            canCreateBorrowing () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            canEditMaterial () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            countAvailableMaterials(){
                var array = this.$store.getters['material/materials'];
                var count = 0;
                for(var i = 0; i < array.length; ++i){
                    if(array[i]['available'] == true)
                        count++;
                }
                return count;
            }
        },

        methods: {
            openModal(id){
                $('#bv-modal-example'+id).modal();
            },
            importModal(id){
                $('#import').modal();
            },
            validateModal(id){
                $('#validate-borrowing'+id).modal();
            },
            createBorrowing () {
                let payload = {employee: this.$data.employee, material: this.$data.material, date_start: this.$data.date_start, date_end: this.$data.date_end};

                this.$store.dispatch('borrowing/createBorrowing', payload);
            },    
            editBorrowing(id,employee,material,date_start,date_end){
                let payload = {id: id, employee:employee, material: material, date_start: date_start, date_end: date_end};

                this.$store.dispatch('borrowing/updateBorrowing', payload);
            },    
            deleteBorrowing (id) {
                this.$store.dispatch('borrowing/deleteBorrowing', id);
            },

            availableMaterial (id,borrowingId) {
                let payload = {id: id, available: 1};
                this.$store.dispatch('material/availableMaterial', payload);
                this.$store.dispatch('borrowing/restituteMaterial', borrowingId);
            },
            downloadItem (url) {
                axios.get(url, { responseType: 'blob' })
                .then(({ data }) => {
                    let blob = new Blob([data], { type: 'application/pdf' })
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'recu.pdf'
                    link.click()
                    .catch(error => {
                        console.error(error)
                    })
                })
            },
            customFilter (item, queryText, itemText) {
                const textOne = item.name.toLowerCase()
                const textTwo = item.abbr.toLowerCase()
                const searchText = queryText.toLowerCase()

                return textOne.indexOf(searchText) > -1 ||
                textTwo.indexOf(searchText) > -1
            },
            setPage (pageNumber) {
                this.currentPage = pageNumber; 
                console.log(pageNumber); 
            },

        },
    };

</script>
