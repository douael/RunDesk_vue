<template>
    <div>
        <div class="row col">
            <h1>Materials</h1>
        </div>

        <div class="row col" v-if="canCreateMaterial">
            <form style="width:100%">
                <div class="form-row">
                    <div class="col-12">
                        <div class="col-6">
                            <label :for="name" class="mr-2">{{ labels.name }}</label>
                            <input v-model="name" type="text" class="form-control">
                        </div>
                        <div class="col-6">
                            <label :for="serialNumber" class="mr-2">{{ labels.serialNumber }}</label>
                            <input v-model="serialNumber" type="text" class="form-control">
                        </div>
                        <div class="col-12">
                            <button @click="createMaterial()" :disabled="name.length === 0 || isLoading || serialNumber.length == 0" type="button" class="btn btn-primary">Create</button>
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
            <error-serialNumber :error="error"></error-serialNumber>

        </div>
        <div v-else-if="!hasMaterials" class="row col">
            No materials!
        </div>

        <div v-else v-for="material in materials" class="row col">
            <material :name="material.name" :isActive="material.isActive" :serialNumber="material.serialNumber"></material>
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
                isActive: false,
                serialNumber: '',
                labels: {
                    name: 'Nom du matériel',
                    serialNumber: 'Numero de serie'
        },
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
                let payload = {name: this.$data.name, isActive: this.$data.isActive,serialNumber: this.$data.serialNumber};

                this.$store.dispatch('material/createMaterial', payload);
            },
        },
    }
</script>