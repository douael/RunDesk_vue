<template>
    <div class="card w-100 mt-2" >
        <div class="card-body" >
            Nom de l'objet : <strong>{{ name }}</strong> Numero de série : <strong>{{ serialNumber }}</strong>
            <button class="btn btn-danger" v-if="!isActive" style="right: 20px;position: absolute;width:100px;" disabled>Inactif</button>
            <button class="btn btn-success" v-else style="right: 20px;position: absolute;width:100px;" disabled>Actif</button>
        </div>
        
    </div>
</template>

<script>
    export default {
        name: 'material',
        props: ['name','isActive','serialNumber'],
    }
</script>