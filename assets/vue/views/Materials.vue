<template>
    <div>
        <div class="row col darkBlue-bg green no-margin">
            <h1>Matériels</h1>
        </div>

        <div class="row col" v-if="canCreateMaterial">

            <div class="col-8">
                <form style="width:100%">
                    <div class="form-row">
                        <div class="col-12">
                            <label :for="name" class="mr-2">{{ labels.name }}</label>
                            <input v-model="name" type="text" class="form-control">
                        </div>
                        <div class="col-12">
                            <label :for="serialNumber" class="mr-2">{{ labels.serialNumber }}</label>
                            <input v-model="serialNumber" type="text" class="form-control">
                        </div>
                        <div class="col-12">
                            <label :for="category" class="mr-2">{{ labels.category }}</label>

                            <v-select :options="categorys" v-model="category"></v-select>
                        </div>
                        <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                            <button @click="createMaterial()" :disabled="name.length === 0 || isLoading || serialNumber.length == 0 || category.length == 0" type="button" class="btn btn-primary">Créer</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-4">
                <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                    <button @click="importModal()" type="button" class="btn btn-primary">Importation CSV du matériel</button>
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
            <error-name :error="error"></error-name>
            <error-serialNumber :error="error"></error-serialNumber>

        </div>
        <div v-else-if="!hasMaterials" class="row col">
            Pas de matériel !
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
                        <th>Numéro de série</th>
                        <th>Catégorie</th>
                        <!-- <th>Supprimer</th> -->
                        <th>Modifier</th>
                        <th>Reservé</th>
                    </tr>
                </thead>
                <tbody >
                    <tr  v-for="material in filteredList" >
                        <td>{{ material.name }}</td>
                        <td>{{ material.serialNumber }}</td>
                        <td>{{ material.category.name }}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal"  @click="openModal(material.id)">
                                <i class="fa fa-edit"></i> Modifier
                            </button>
                        </td>
                        <td>
                            <form >
                                <button type="button" class="btn btn-warning" v-if="!material.available && material.isActive" disabled>Reservé</button>
                                <div v-else-if="material.available && material.isActive" >
                                     <button type="button" class="btn btn-success" disabled>Dans le stock</button>
                                     <button type="button" class="btn btn-warning" @click="unactivateMaterial(material.id)">Enlever du stock</button>

                                    </div>
                                <button type="button" class="btn btn-error" v-else @click="activateMaterial(material.id)">Remettre dans le stock</button>

                            </form>
                        </td>                
                    </tr>
                </tbody>
            </table>

        </div>
        <div v-for="material in materials">
            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" :id="'bv-modal-example'+material.id">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" >Modifier</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">
                                <div class="col-6">
                                    <input v-model="material.name" type="text" class="form-control">
                                </div>
                                <div class="col-6">
                                    <input v-model="material.serialNumber" type="text" class="form-control">
                                </div>
                                <div class="col-6">
                                    <select class="form-control" name="category" v-model="material.category" >
                                        <option v-for="Othercategory in categorysC" v-bind:value="Othercategory.id" >
                                            {{ Othercategory.name }}
                                        </option>

                                    </select>
                                </div>
                                <input type="hidden" id="isActive" name="isActive" class="form-control" :value="material.isActive">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                            @click="editMaterial(material.id,material.name,material.isActive,material.serialNumber,material.category)" :disabled="material.name.length === 0 || material.serialNumber.length == 0 || material.category.length == 0">
                            Modifier
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" :id="'delete-material'+material.id">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" >Suppression de Materiel</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr de vouloir supprimer ce matériel ? 
                        <ul>
                            <li >
                                {{ material.name }}
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal" @click.prevent="deleteMaterial(material.id)">
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" id="import">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" >Importer du matériels en CSV</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        Format du csv :<br><br>
                        Nom material 1<strong>,</strong> Numero de serie<br>
                        Nom material 2<strong>,</strong> Numero de serie<br><br>

                        <a type="button" class="btn btn-light waves-effect waves-light" href="/modal_forMaterials.csv">
                            Telecharger modele
                        </a><br><br>

                        <input type="file" id="file" ref="file" accept=".csv" @change="onChangeFileUpload" class="input-file">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal" v-on:click="submitForm()">
                            Importer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import ErrorMessage from '../components/ErrorMessage';
    import axios from 'axios';
    import vSelect from 'vue-select'

    Vue.component('v-select', vSelect)
    export default {
        name: 'materials',
        components: {
            ErrorMessage,
        },
        data () {
            return {
                resultCount: 0,
                size: 10,
                pageNumber: 0, 
                search:'',
                name: '',
                isActive: true,
                serialNumber: '',
                id: '',
                category:'',
                file: '',
                labels: {
                    name: 'Nom du matériel',
                    serialNumber: 'Numéro de série',
                    category: 'Catégorie'
                },
            };
        },
        created () {
            this.$store.dispatch('material/fetchMaterials');
            this.$store.dispatch('category/fetchCategorys');
        },
        computed: {
            totalPages () {
                this.resultCount = this.materials.length;
                console.log(this.pageNumber);
                console.log(Math.ceil(this.resultCount / this.size) + " totalPages");
                return Math.ceil(this.resultCount / this.size); 
            },
            isLoading () {
                return this.$store.getters['material/isLoading'];
            },
            hasError () {
                return this.$store.getters['material/hasError'];
            },
            error () {
                return this.$store.getters['material/error'];
            },
            hasMaterials () {
                return this.$store.getters['material/hasMaterials'];
            },
            materials () {
                return this.$store.getters['material/materials'];
            },
            filteredList() {
                const start = this.pageNumber * this.size,
                end = start + this.size;
                return this.materials.filter(material => {
                    return material.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    material.serialNumber.toLowerCase().includes(this.search.toLowerCase()) ||
                    material.category.name.toLowerCase().includes(this.search.toLowerCase()) ;
                }).slice(start, end);
            },

            categorys () {
                var array= this.$store.getters['category/categorys'];
                var options = [];
                for(var i = 0; i < array.length; ++i){
                    options.push(array[i]['id']+' - '+array[i]['name']);
                }
                return options;
            },
            categorysC () {
                return this.$store.getters['category/categorys'];
            },
            canCreateMaterial () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            canEditMaterial () {
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
                axios.post('/api/material/import',
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
                createMaterial () {
                    let payload = {name: this.$data.name, isActive: this.$data.isActive,serialNumber: this.$data.serialNumber,category: this.$data.category};

                    this.$store.dispatch('material/createMaterial', payload);
                },
                importMaterial (fileMaterial) {

                },
                activateMaterial (id) {
                    let payload = {id: id, isActive: 1};

                    this.$store.dispatch('material/editMaterial', payload);
                },
                unactivateMaterial (id) {

                    let payload = {id: id, isActive: 0};

                    this.$store.dispatch('material/editMaterial', payload);
                },
                openModal(id){
                    $('#bv-modal-example'+id).modal();
                },
                importModal(id){
                    $('#import').modal();
                },
                deleteModal(id,name){
                    $('#delete-material'+id).modal();
                },
                editMaterial(id,name,isActive,serialNumber,category){
                    let payload = {id: id,name:name, isActive: isActive,serialNumber: serialNumber,category: category};

                    this.$store.dispatch('material/updateMaterial', payload);
                },
                deleteMaterial (id) {
                    this.$store.dispatch('material/deleteMaterial', id);
                }

            },
        }
    </script>