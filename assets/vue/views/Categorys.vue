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
                            <select class="form-control" name="type" v-model="type">
                                <option v-for="type in types" v-bind:value="type">
                                    {{ type.name }}
                                </option>
                            </select>
                        </div>
                       
                        <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                            <button @click="createCategory()" :disabled="name.length === 0 || isLoading || type.length == 0"type="button" class="btn btn-primary">Créer</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Chargement...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-name :error="error"></error-name>
            <!-- <error-quantity :error="error"></error-quantity> -->

        </div>
        <div v-else-if="!hasCategorys" class="row col">
            Pas de catégorie !
        </div>
        <div v-else class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Supprimer</th>
                        <th>Modifier</th>
                    </tr>
                </thead>
                <tbody >
                    <tr v-for="category in categorys" v-if="category.id!=3">
                        <td>{{ category.name }}</td>
                        <td>{{ category.type.name }}</td>
                        <td>
                            <button type="button" class="btn btn-danger" data-toggle="modal" @click="deleteModal(category.id,category.name)" >
                                <i class="fa fa-trash"></i> Supprimer
                            </button>
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

                <div class="modal fade bg-dark" tabindex="-1" role="dialog" aria-hidden="true" :id="'bv-modal-example'+category.id">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" >Modifier</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <div class="col-6">
                                        <label>Nom</label>
                                        <input v-model="category.name" type="text" class="form-control">
                                    </div>

                                    <div class="col-6">
                                      <select class="form-control" name="type" v-model="category.type" >
                                          <option v-for="Othertype in types" v-bind:value="Othertype.id" >
                                          {{ Othertype.name }}
                                          </option>
                                          
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
                                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                                @click="editCategory(category.id,category.name,category.type)">
                                Modifier
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
            style="display: none;"
            :id="'delete-category'+category.id">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" >Supprimer la catégorie</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                        @click.prevent="deleteCategory(category.id)">
                        Supprimer
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
</div>
</template>

<script>
    import Category from '../components/Category';
    import ErrorMessage from '../components/ErrorMessage';

    export default {
        name: 'categorys',
        components: {
            Category,
            ErrorMessage,
        },
        data () {
            return {
                name: '',
                type: '',
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
            canCreateCategory () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            canEditCategory () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            types () {
                return this.$store.getters['type/types'];
            }
        },
        methods: {
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