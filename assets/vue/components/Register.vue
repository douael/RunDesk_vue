<template>
    <div class="card w-100 mt-2" >
        <div class="card-body" >
            <form >
            Login : <strong>{{ login }}</strong> Prénom : <strong>{{ firstname }} </strong>Site :  <strong>{{site }}</strong>
            
            <button type="button" class="btn btn-danger" data-toggle="modal" style="right: 100px;position: absolute;width:120px;" @click="deleteModal(id,lastname)" 
              >
                <i class="fa fa-trash"></i> Supprimer
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" style="right: 230px;position: absolute;width:110px;" @click="openModal(id)" 
              >
                <i class="fa fa-edit"></i> Modifier
            </button>
            <input type="hidden" id="id" name="id" class="form-control" :value="id">
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
                                    <input v-model="lastname" type="text" class="form-control">
                                </div>
                                <div class="col-6">
                                    <input v-model="firstname" type="text" class="form-control">
                                </div>
                                <div class="col-6">
                                    <input v-model="site" type="text" class="form-control">
                                </div>
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                            @click="editUser(id,firstname,lastname,site)">
                    Modify
                    </button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         style="display: none;"
         :id="'delete-user'+id">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Delete User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
            Are you sure that you want to delete this user ?
            <ul>
              <li >
                {{ firstname }} {{ lastname }}
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                    @click.prevent="deleteUser(id)">
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
        name: 'user',
        props: ['id','login','password'],
        methods : {
            openModal(id){
                $('#bv-modal-example'+id).modal();
            },
            deleteModal(id,lastname){
                $('#delete-user'+id).modal();
            },
            editUser(id,login,password){
                let payload = {id: id, login:login, password:password};

                this.$store.dispatch('user/updateUser', payload);
            },
            deleteUser (id) {
                this.$store.dispatch('user/deleteUser', id);
            }
        }
    }

</script>