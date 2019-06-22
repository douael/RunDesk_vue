<template>
    <div>
        <div class="row col">
            <h1>Materials</h1>
        </div>

        <div class="row col" v-if="canCreateMaterial">
            <form>
                <div class="form-row">
                    <div class="col-8">
                        <input v-model="name" type="text" class="form-control">
                        <input v-model="isActive" type="text" class="form-control">
                        <input v-model="serialNum" type="text" class="form-control">
                    </div>
                    <div class="col-4">
                        <button @click="createMaterial()" :disabled="name.length === 0 || isLoading || serialNum.length == 0" type="button" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-name :error="error"></error-name>
            <error-serialNum :error="error"></error-serialNum>

        </div>
        <div v-else-if="!hasMaterials" class="row col">
            No materials!
        </div>

        <div v-else v-for="material in materials" class="row col">
            <material :name="material.name"></material>
            <material :serialNum="material.serialNum"></material>
        </div>
    </div>
</template>

<script>
    import Material from '../components/Material';
    import ErrorMessage from '../components/ErrorMessage';

    export default {
        name: 'materials',
        components: {
            Material,
            ErrorMessage,
        },
        data () {
            return {
                name: '',
                isActive: '',
                serialNum: '',
            };
        },
        created () {
            this.$store.dispatch('material/fetchMaterials');
        },
        computed: {
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
            canCreateMaterial () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            }
        },
        methods: {
            createMaterial () {
                let payload = {name: this.$data.name, isActive: this.$data.isActive,serialNum: this.$data.serialNum};

                this.$store.dispatch('material/createMaterial', payload);
            },
        },
    }
</script>