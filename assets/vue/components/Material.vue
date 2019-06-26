<template>
    <div class="card w-100 mt-2" >
        <div class="card-body" >
            <form >
            Nom de l'objet : <strong>{{ name }}</strong> Numero de série : <strong>{{ serialNumber }}{{id}}</strong>
            <input type="hidden" id="id" name="id" class="form-control" :value="id">
             <button type="button" class="btn btn-danger" v-if="!isActive" style="right: 20px;position: absolute;width:100px;" @click="activateMaterial(id)">Inactif</button>
            <button type="button" class="btn btn-success" v-else style="right: 20px;position: absolute;width:100px;" @click="unactivateMaterial(id)">Actif</button>
            </form>
        </div>
    </div>
</template>

<script>
    
    export default {
        name: 'material',
        props: ['id','name','isActive','serialNumber'],
        methods : {
            activateMaterial (id) {
                //let id=document.getElementById("id").value; 
                let payload = {id: id, isActive: 1};

                this.$store.dispatch('material/editMaterial', payload);
            },
            unactivateMaterial (id) {
                //let id=document.getElementById("id").value; 

                let payload = {id: id, isActive: 0};

                this.$store.dispatch('material/editMaterial', payload);
            },
        }
    }
</script>