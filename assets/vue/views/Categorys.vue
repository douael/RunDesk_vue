<template>
    <div>
        <div class="row col darkBlue-bg green no-margin">
            <h1>Catégories</h1>
        </div>

        <div class="row col" v-if="canCreateCategory">
            <form style="width:100%">
                <div class="form-row">
                    <div class="col-12">
                        <div class="col-6">
                            <label :for="name" class="mr-2">{{ labels.name }}</label>
                            <input v-model="name" type="text" class="form-control">
                        </div>
                        <div class="col-6">
                            <label :for="type" class="mr-2">{{ labels.type }}</label>

                            <v-select :options="types" v-model="type"></v-select>
                        </div>

                        <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                            <button @click="createCategory()" :disabled="name.length === 0 || isLoading || type.length == 0" type="button" class="btn btn-primary">Créer</button>
                        </div>
                    </div>
                </div>
            </form>
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

        </div>
        <div v-else-if="!hasCategorys" class="row col">
            Pas de catégorie !
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
                        <th>Type</th>
                        <th>Nombre de materiels</th>
                        <th>Supprimer</th>
                        <th>Modifier</th>
                    </tr>
                </thead>
                <tbody >
                    <tr v-for="category in filteredList" v-if="category.id!=1">
                        <td>{{ category.name }}</td>
                        <td>{{ category.type.name }}</td>
                        <td v-if='category.count==1'>{{ category.count }} matériel</td>
                        <td v-else-if='category.count>1'>{{ category.count }} matériels</td>
                        <td v-else>Aucun matériel</td>
                        <td>
                            <button  v-if='category.count==0 ' type="button" class="btn btn-danger" data-toggle="modal" @click="deleteModal(category.id,category.name)" >
                                <i class="fa fa-trash"></i> Supprimer
                            </button>
                            <button  v-else-if="category.count>0 " class="btn btn-warning" disabled>Suppression non autorisé</button>
                        </td>

                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="openModal(category.id)">
                                <i class="fa fa-edit"></i> Modifier
                            </button>
                        </td>                     </tr>
                    </tbody>
                </table>
            </div>
            <div v-for="category in categorys">

                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" :id="'bv-modal-example'+category.id">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" >Modifier</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <div class="col-6">
                                        <label>Nom</label>
                                        <input v-model="category.name" type="text" class="form-control">
                                    </div>

                                    <div class="col-6">
                                        <label>Type : {{ category.type.name }}</label>
                                        <select class="form-control" name="typeQ" v-model="typeQ"  required>
                                            <option v-for="Othertype in typesC" v-bind:value="Othertype.id" >
                                                {{ Othertype.name }}
                                            </option>

                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal" onclick="javascript:window.location.reload()">Annuler</button>
                                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" :disabled="category.name.length === 0 ||  typeQ.length == 0" @click="editCategory(category.id,category.name,typeQ)">
                                    Modifier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
                style="display: none;"
                :id="'delete-category'+category.id">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" >Supprimer la catégorie</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
                        </div>
                        <div class="modal-body">
                            Êtes-vous sûr de vouloir supprimer cette catégorie ?
                            <ul>
                                <li >
                                    {{ category.name }}
                                </li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal" onclick="javascript:window.location.reload()">Annuler</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal" @click.prevent="deleteCategory(category.id)">
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
    import Category from '../components/Category';
    import ErrorMessage from '../components/ErrorMessage';

    import vSelect from 'vue-select'

    Vue.component('v-select', vSelect)
    export default {
        name: 'categorys',
        components: {
            Category,
            ErrorMessage,
        },
        data () {
            return {
                resultCount: 0,
                size: 10,
                pageNumber: 0, 
                search:'',
                name: '',
                type: '',
                typeQ:'',
                id: '',
                labels: {
                    name: 'Nom de la categorie',
                    type: 'Type'
                },
            };
        },
        created () {
            this.$store.dispatch('category/fetchCategorys');
            this.$store.dispatch('type/fetchTypes');
        },
        computed: {
            totalPages () {
                this.resultCount = this.categorys.length;
                console.log(this.pageNumber);
                console.log(Math.ceil(this.resultCount / this.size) + " totalPages");
                return Math.ceil(this.resultCount / this.size); 
            },
            isLoading () {
                return this.$store.getters['category/isLoading'];
            },
            hasError () {
                return this.$store.getters['category/hasError'];
            },
            error () {
                return this.$store.getters['category/error'];
            },
            hasCategorys () {
                return this.$store.getters['category/hasCategorys'];
            },
            categorys () {
                return this.$store.getters['category/categorys'];
            },
            filteredList() {
                const start = this.pageNumber * this.size,
                end = start + this.size;
                return this.categorys.filter(category => {
                    return category.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    category.type.name.toLowerCase().includes(this.search.toLowerCase()) ;
                }).slice(start, end);
            },
            canCreateCategory () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            canEditCategory () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            types () {
                var array =  this.$store.getters['type/types'];
                var options = [];
                for(var i = 0; i < array.length; ++i){
                    options.push(array[i]['id']+' - '+array[i]['name']);
                }
                return options;
            },
            typesC () {
                return  this.$store.getters['type/types'];
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

            createCategory () {
                let payload = {name: this.$data.name, type: this.$data.type};

                this.$store.dispatch('category/createCategory', payload);
            },
            activateCategory (id) {
                let payload = {id: id, type: 1};

                this.$store.dispatch('category/editCategory', payload);
            },
            unactivateCategory (id) {

                let payload = {id: id, type: 0};

                this.$store.dispatch('category/editCategory', payload);
            },
            openModal(id){
                $('#bv-modal-example'+id).modal();
            },
            deleteModal(id,name){
                $('#delete-category'+id).modal();
            },
            editCategory(id,name,type){
                let payload = {id: id,name:name, type: type};

                this.$store.dispatch('category/updateCategory', payload);
            },
            deleteCategory (id) {
                this.$store.dispatch('category/deleteCategory', id);
            }

        },
    }
</script>