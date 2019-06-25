<template>
    <div class="card w-100 mt-2" >
        <div class="card-body" >
            <form >
            Nom de l'objet : <strong>{{ name }}</strong> Numero de série : <strong>{{ serialNumber }}{{id}}</strong>
            <input type="hidden" name="name" class="form-control" :value="name">
            <input type="hidden" name="serialNumber" class="form-control" :value="serialNumber">
            <input type="hidden" name="id" class="form-control" :value="id">
             <button class="btn btn-danger" v-if="!isActive" style="right: 20px;position: absolute;width:100px;" @click="activateMaterial()">Inactif</button>
                <button class="btn btn-success" v-else style="right: 20px;position: absolute;width:100px;" @click="unactivateMaterial()">Actif</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'material',
        props: ['id','name','isActive','serialNumber'],
        methods : {
            activateMaterial () {
                let payload = {id: this.$data.id ,name: this.$data.name, isActive: 1,serialNumber: this.$data.serialNumber};

                this.$store.dispatch('material/editMaterial', payload);
            },
            unactivateMaterial () {
                let payload = {id: this.$data.id ,name: this.$data.name, isActive: 0,serialNumber: this.$data.serialNumber};

                this.$store.dispatch('material/editMaterial', payload);
            },
        }
    }
</script>