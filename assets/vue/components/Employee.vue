<template>
    <div class="card w-100 mt-2" >
        <div class="card-body" >
            <form >
            Nom : <strong>{{ lastname }}</strong> Prénom : <strong>{{ firtsname }} </strong>Site :  <strong>{{category.site }}</strong>
            
            <button type="button" class="btn btn-danger" data-toggle="modal" style="right: 100px;position: absolute;width:120px;" @click="deleteModal(id,lastname)" 
              >
                <i class="fa fa-trash"></i> Supprimer
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" style="right: 230px;position: absolute;width:110px;" @click="openModal(id)" 
              >
                <i class="fa fa-edit"></i> Modifier
            </button>
            <input type="hidden" id="id" name="id" class="form-control" :value="id">
            <!-- <button type="button" class="btn btn-warning" v-if="!isActive" style="right: 20px;position: absolute;width:70px;" @click="activateEmployee(id)">Inactif
            </button>
            <button type="button" class="btn btn-success" v-else style="right: 20px;position: absolute;width:70px;" @click="unactivateEmployee(id)">Actif</button> -->
            
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
                               <!--  <div class="col-6">
                                    <select class="form-control" name="category" v-model="category" >
                                      <option v-for="Otheruser in users" v-bind:value="Otheruser.id" >
                                      {{ Otheruser.id }}
                                      </option>
                                      
                                    </select>
                                </div> -->
                                <!-- <input type="hidden" id="isActive" name="isActive" class="form-control" :value="isActive"> -->

                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                            @click="editEmployee(id,lastname,firstname,site)">
                    Modify
                    </button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         style="display: none;"
         :id="'delete-employee'+id">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Delete Employee</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
            Are you sure that you want to delete this employee ?
            <ul>
              <li >
                {{ name }}
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                    @click.prevent="deleteEmployee(id)">
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
        name: 'employee',
        props: ['id','lastname','firstname','site'],
        // created () {
        //     this.$store.dispatch('category/fetchCategorys');
        // },
        // computed:{
        //   categorys () {
        //         return this.$store.getters['category/categorys'];
        //     },
        // },
        methods : {
            // activateEmployee (id) {
            //     //let id=document.getElementById("id").value; 
            //     let payload = {id: id, isActive: 1};

            //     this.$store.dispatch('employee/editEmployee', payload);
            // },
            // unactivateEmployee (id) {
            //     //let id=document.getElementById("id").value; 

            //     let payload = {id: id, isActive: 0};

            //     this.$store.dispatch('employee/editEmployee', payload);
            // },
            openModal(id){
                $('#bv-modal-example'+id).modal();
            },
            deleteModal(id,lastname){
                $('#delete-employee'+id).modal();
            },
            editEmployee(id,lastname,firstname,site){
                let payload = {id: id, lastname:lastname, firstname:firstname, site:site};

                this.$store.dispatch('employee/updateEmployee', payload);
            },
            deleteEmployee (id) {
                this.$store.dispatch('employee/deleteEmployee', id);
            }
        }
    }

</script>