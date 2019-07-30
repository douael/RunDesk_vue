<template>
    <div class="card w-100 mt-2" >
        <tr>
            <td>{{ name }}</td>
            <td>{{ quantity }}</td>
            <td>{{ type }}</td>
            <td>
                <button type="button" class="btn btn-danger" data-toggle="modal" @click="deleteModal(id,name)" >
                    <i class="fa fa-trash"></i> Supprimer
                </button>
            </td>

            <td>
                <button type="button" class="btn btn-primary" data-toggle="modal" @click="openModal(id)">
                    <i class="fa fa-edit"></i> Modifier
                </button>
            </td>        
        </tr>
        <div class="modal fade bg-dark" tabindex="-1" role="dialog" aria-hidden="true" :id="'bv-modal-example'+id">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Modifier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                                <div class="col-6">
                                    <label>Nom</label>
                                    <input v-model="name" type="text" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label>Quantité</label>
                                    <input v-model="quantity" type="number" class="form-control">
                                </div>
                                
                                <div class="col-6">
                                    <label>Type</label>
                                    <input v-model="type" type="text" class="form-control">
                                </div>
                                <input type="hidden" id="type" name="type" class="form-control" :value="type">

                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                            @click="editCategory(id,name,type,quantity)">
                    Modify
                    </button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         style="display: none;"
         :id="'delete-category'+id">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Delete Category</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
          </div>
          <div class="modal-body">
            Are you sure that you want to delete this category?
            <ul>
              <li >
                {{ name }}
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                    @click.prevent="deleteCategory(id)">
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
        name: 'category',
        props: ['id','name','type','quantity'],
        methods : {
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
        }
    }

</script>