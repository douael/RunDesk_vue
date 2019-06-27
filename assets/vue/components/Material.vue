<template>
    <div class="card w-100 mt-2" >
        <div class="card-body" >
            <form >
            Nom de l'objet : <strong>{{ name }}</strong> Numero de série : <strong>{{ serialNumber }}</strong>
            
            <button type="button" class="btn btn-danger" data-toggle="modal" style="right: 100px;position: absolute;width:120px;" @click="deleteModal(id,name)" 
              >
                <i class="fa fa-trash"></i> Supprimer
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" style="right: 230px;position: absolute;width:110px;" @click="openModal(id)" 
              >
                <i class="fa fa-edit"></i> Modifier
            </button>
            <input type="hidden" id="id" name="id" class="form-control" :value="id">
             <button type="button" class="btn btn-warning" v-if="!isActive" style="right: 20px;position: absolute;width:70px;" @click="activateMaterial(id)">Inactif</button>
            <button type="button" class="btn btn-success" v-else style="right: 20px;position: absolute;width:70px;" @click="unactivateMaterial(id)">Actif</button>
            
            </form>
        </div>
        <div class="modal fade bg-dark" tabindex="-1" role="dialog" aria-hidden="true" :id="'bv-modal-example'+id">
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

                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                            @click="editMaterial(id,name,isActive,serialNumber)">
                    Modify
                    </button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         style="display: none;"
         :id="'delete-material'+id">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Delete Material</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
            Are you sure that you want to delete this material?
            <ul>
              <li >
                {{ name }}
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                    @click.prevent="deleteMaterial(id)">
              Delete
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
            openModal(id){
                $('#bv-modal-example'+id).modal();
            },
            deleteModal(id,name){
                $('#delete-material'+id).modal();
            },
            editMaterial(id,name,isActive,serialNumber){
                let payload = {id: id,name:name, isActive: isActive,serialNumber: serialNumber};

                this.$store.dispatch('material/updateMaterial', payload);
            },
            deleteMaterial (id) {
                this.$store.dispatch('material/deleteMaterial', id);
            }
        }
    }

</script>