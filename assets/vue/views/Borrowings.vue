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
                            <v-select :options="availableemployees" v-model="employee"></v-select>
                        </div>
                        <div class="col-12">
                            <label :for="material" class="mr-2">{{ labels.material }}</label>

                            <v-select :options="availablematerials" v-if="countAvailableMaterials>0" v-model="material"></v-select>
                            <input v-else-if="!isLoading" type="text" disabled class="form-control" value="Aucun materiel n'est disponible">
                        </div>
                        <div class="row col-12">
                            <div class="col-6">

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

<div v-else class="table-responsive">

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


            <div class="input-group" style="    margin-left: 50%;">
                <form >
                    <input type="number"  placeholder="30 jours par défaut" min='1' max='365' name="date" class="input-md form-control" v-model="date" style="    width: 82% !important;">
                    <button  @click="uploadHistory(date)" :disabled="date>365 || date<1 " type="button" class="btn btn-info" ><i class="fa fa-download"></i> Telecharger un recapitulatif </button>
                </form>
            </div>
        </form>
    </div>
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
                <th>Employés</th>
                <th>Matériels</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody >
            <tr v-for="borrowing in filteredList">
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

</div>


<div class="modal fade"   v-for="borrowing in borrowings" tabindex="-1" role="dialog" aria-hidden="true" :id="'validate-borrowing'+borrowing.id">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Valider la restitution</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
            </div>
            <div class="modal-body">
                Etes-vous sûr de vouloir valider la restitution de l'article suivant ?
                <ul>
                    <li >
                        Nom : {{ borrowing.material.name }} 
                    </li>
                    <li>
                        Numero de serie : {{ borrowing.material.serialNumber }}
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal" onclick="javascript:window.location.reload()">Annuler</button>
                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                @click.prevent="availableMaterial(borrowing.material.id,borrowing.id)">
                Valider
            </button>
        </div>
    </div>
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
                resultCount: 0,
                size: 5,
                pageNumber: 0,  
                employee: '',
                date: '',
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
                    if(array[i]['available'] == true && array[i]['isActive'] == true)
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
            pageCount(){
                let l = this.borrowings.length,
                s = this.size;
                return Math.ceil(l/s);
            },
            totalPages () {
                this.resultCount = this.borrowings.length;
                console.log(this.pageNumber);
                console.log(Math.ceil(this.resultCount / this.size) + " totalPages");
                return Math.ceil(this.resultCount / this.size); 
            },

            filteredList() {
                const start = this.pageNumber * this.size,
                end = start + this.size;
                return this.borrowings.filter(borrowing => {
                    return borrowing.employee.firstname.toLowerCase().includes(this.search.toLowerCase()) ||
                    borrowing.employee.lastname.toLowerCase().includes(this.search.toLowerCase()) ||
                    borrowing.material.category.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    borrowing.material.name.toLowerCase().includes(this.search.toLowerCase());
                }).slice(start, end);
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
            uploadHistory(date){
                if(date == undefined){
                    axios.post('/api/borrowing/uploadHistory')
                    .then(function (res) {
                        console.log(res.data);
                        axios.get('/history/'+res.data+'.csv', { responseType: 'blob' })
                        .then(({ data }) => {
                            let blob = new Blob([data], { type: 'text/csv;charset=utf-8;' })
                            let link = document.createElement('a')
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'history.csv'
                            link.click()
                            .catch(error => {
                                console.error(error)
                            })
                        })
                    })
                }else{
                    axios.post('/api/borrowing/uploadHistory',{date:date})
                    .then(function (res) {
                        console.log(res.data);
                        axios.get('/history/'+res.data+'.csv', { responseType: 'blob' })
                        .then(({ data }) => {
                            let blob = new Blob([data], { type: 'text/csv;charset=utf-8;' })
                            let link = document.createElement('a')
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'history.csv'
                            link.click()
                            .catch(error => {
                                console.error(error)
                            })
                        })
                    })
                }

            }
        }
    };

</script>
