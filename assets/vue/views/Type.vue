<template>
    <div>
        <div class="row col darkBlue-bg green no-margin">
            <h1>Type</h1>
        </div>

        <div class="row col" v-if="canCreateType">
            <form style="width:100%">
                <div class="form-row">
                    <div class="col-12">
                        <div class="col-6">
                            <label :for="name" class="mr-2">{{ labels.name }}</label>
                            <input v-model="name" type="text" class="form-control">
                        </div>
                        
                        <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                            <button @click="createType()" :disabled="name.length === 0 || isLoading" type="button" class="btn btn-primary">Créer</button>
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
<div v-else-if="!hasTypes" class="row col">
    Pas de Type !
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
                <th>Nombre de categories</th>
                <th>Supprimer</th>
                <th>Modifier</th>
            </tr>
        </thead>
        <tbody >
            <tr v-for="type in filteredList">
                <td>{{ type.name }}</td>
                <td v-if='type.count==1'>{{ type.count }} categorie</td>
                <td v-else-if='type.count>1'>{{ type.count }} categories</td>
                <td v-else>Aucune categorie</td>
                <td>
                    <button  v-if='type.count==0' type="button" class="btn btn-danger" data-toggle="modal" @click="deleteModal(type.id)" >
                        <i class="fa fa-trash"></i> Supprimer
                    </button>
                    <button  v-else-if="type.count>0" class="btn btn-warning" disabled>Suppression non autorisé</button>
                </td>

                <td>
                    <button type="button" class="btn btn-primary" data-toggle="modal" @click="openModal(type.id)">
                        <i class="fa fa-edit"></i> Modifier
                    </button>
                </td>                     
            </tr>
        </tbody>
    </table>
</div>
<div v-for="type in types">

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" :id="'bv-modal-example'+type.id">
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
                            <input v-model="type.name" type="text" class="form-control">
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal" onclick="javascript:window.location.reload()">Annuler</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" :disabled="type.name.length === 0 " @click="editType(type.id,type.name)"> 
                        Modifier
                    </button>
                </div>
            </div> 
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" :id="'delete-type'+type.id">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Supprimer le type</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer ce type ?
                    <ul>
                        <li >
                            {{ type.name }}
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal" onclick="javascript:window.location.reload()">Annuler</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                    @click.prevent="deleteType(type.id)">
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
    import ErrorMessage from '../components/ErrorMessage';

    export default {
        resultCount: 0,
        size: 2,
        pageNumber: 0, 
        name: 'types',
        components: {
            ErrorMessage,
        },
        data () {
            return {
                resultCount: 0,
                size: 5,
                pageNumber: 0,
                search:'',
                name: '',
                type: '',
                id: '',
                labels: {
                    name: 'Nom du type'
                },
            };
        },
        created () {
            this.$store.dispatch('type/fetchTypes');
        },
        computed: {
            totalPages () {
                this.resultCount = this.types.length;
                console.log(this.pageNumber);
                console.log(Math.ceil(this.resultCount / this.size) + " totalPages");
                return Math.ceil(this.resultCount / this.size); 
            },
            isLoading () {
                return this.$store.getters['type/isLoading'];
            },
            hasError () {
                return this.$store.getters['type/hasError'];
            },
            error () {
                return this.$store.getters['type/error'];
            },
            hasTypes () {
                return this.$store.getters['type/hasTypes'];
            },
            types () {
                return this.$store.getters['type/types'];
            },
            filteredList() {
                const start = this.pageNumber * this.size,
                end = start + this.size;
                return this.types.filter(type => {
                    return type.name.toLowerCase().includes(this.search.toLowerCase());
                }).slice(start, end);
            },
            canCreateType () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            canEditType () {
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
            createType () {
                let payload = {name: this.$data.name};

                this.$store.dispatch('type/createType', payload);
            },
            activateType (id) {
                let payload = {id: id, type: 1};

                this.$store.dispatch('type/editType', payload);
            },
            unactivateType (id) {

                let payload = {id: id, type: 0};

                this.$store.dispatch('type/editType', payload);
            },
            openModal(id){
                $('#bv-modal-example'+id).modal();
            },
            deleteModal(id,name){
                $('#delete-type'+id).modal();
            },
            editType(id,name,type){
                let payload = {id: id,name:name};

                this.$store.dispatch('type/updateType', payload);
            },
            deleteType (id) {
                this.$store.dispatch('type/deleteType', id);
            }

        },
    }
</script>