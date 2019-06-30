<template>
    <div>
        <div class="row col">
            <h1>Categories</h1>
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
                        <div class="col-12" style="margin-top:10px;">
                            <button @click="createCategory()" :disabled="name.length === 0 || isLoading || quantity.length == 0 || type.length == 0" type="button" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-name :error="error"></error-name>
            <error-quantity :error="error"></error-quantity>

        </div>
        <div v-else-if="!hasCategorys" class="row col">
            No categorys!
        </div>

        <div v-else v-for="category in categorys" class="row col">
                <category :id="category.id" :name="category.name" :type="category.type" :quantity="category.quantity"></category>
                
               
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
            }
            
        },
    }
</script>