<template>
    <div class="card w-100 mt-2" >
        <div class="card-body" >
            <form >
            Nom de l'objet : <strong>{{ name }}</strong> Numero de série : <strong>{{ serialNumber }}{{id}}</strong>
            <button type="button" class="btn btn-primary m-1" data-toggle="modal" :data-target="'#update-material' + id" 
               @click="action = 'edit'">
                <i class="fa fa-edit"></i> Modifier
            </button>
            <input type="hidden" id="id" name="id" class="form-control" :value="id">
             <button type="button" class="btn btn-danger" v-if="!isActive" style="right: 20px;position: absolute;width:100px;" @click="activateMaterial(id)">Inactif</button>
            <button type="button" class="btn btn-success" v-else style="right: 20px;position: absolute;width:100px;" @click="unactivateMaterial(id)">Actif</button>
            
            </form>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         style="display: none;"
         :id="'#update-material'+ id">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Modifier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                                <div class="col-6">
                                    <input v-model="name" type="text" class="form-control">
                                </div>
                                <div class="col-6">
                                    <input v-model="serialNumber" type="text" class="form-control">
                                </div>
                                <input type="hidden" id="isActive" name="isActive" class="form-control" :value="isActive">

                                <div class="col-12">
                                    <button @click="editMaterial(id)" :disabled="name.length === 0 || serialNumber.length == 0" type="button" class="btn btn-primary">Edit</button>
                                </div>
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                            @click.prevent="updateTag">
                    Modify
                    </button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
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