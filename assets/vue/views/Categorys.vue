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
                            <label :for="quantity" class="mr-2">{{ labels.quantity }}</label>
                            <input v-model="quantity" type="number" class="form-control">
                        </div>
                        <div class="col-6">
                            <label :for="type" class="mr-2">{{ labels.type }}</label>
                            <input v-model="type" type="text" class="form-control">
                        </div>
                        <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                            <button @click="createCategory()" :disabled="name.length === 0 || isLoading || quantity.length == 0 || type.length == 0"type="button" class="btn btn-primary">Créer</button>
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
            <error-quantity :error="error"></error-quantity>

        </div>
        <div v-else-if="!hasCategorys" class="row col">
            Pas de catégorie !
        </div>
        <div v-else class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Quantité</th>
                        <th>Type</th>
                        <th>Supprimer</th>
                        <th>Modifier</th>
                    </tr>
                </thead>
                <tbody >
                    <tr v-for="category in categorys" v-if="category.id!=3">
                        <td>{{ category.name }}</td>
                        <td>{{ category.quantity }}</td>
                        <td>{{ category.type }}</td>
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
                                        <label>Quantité</label>
                                        <input v-model="category.quantity" type="number" class="form-control">
                                    </div>

                                    <div class="col-6">
                                        <label>Type</label>
                                        <input v-model="category.type" type="text" class="form-control">
                                    </div>
                                    <input type="hidden" id="type" name="type" class="form-control" :value="type">

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
                                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                                @click="editCategory(category.id,category.name,category.type,category.quantity)">
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
                quantity: '',
                id: '',
                labels: {
                    name: 'Nom de la categorie',
                    quantity: 'Quantité',
                    type: 'Type'
                },
            };
        },
        created () {
            this.$store.dispatch('category/fetchCategorys');
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
            }
        },
        methods: {
            createCategory () {
                let payload = {name: this.$data.name, type: this.$data.type,quantity: this.$data.quantity};

                this.$store.dispatch('category/createCategory', payload);
            },
            activateCategory (id) {
//let id=document.getElementById("id").value; 
let payload = {id: id, type: 1};

this.$store.dispatch('category/editCategory', payload);
},
unactivateCategory (id) {
//let id=document.getElementById("id").value; 

let payload = {id: id, type: 0};

this.$store.dispatch('category/editCategory', payload);
},
openModal(id){
    $('#bv-modal-example'+id).modal();
},
deleteModal(id,name){
    $('#delete-category'+id).modal();
},
editCategory(id,name,type,quantity){
    let payload = {id: id,name:name, type: type,quantity: quantity};

    this.$store.dispatch('category/updateCategory', payload);
},
deleteCategory (id) {
    this.$store.dispatch('category/deleteCategory', id);
}

},
}
</script>