<template>
    <div class="card w-100 mt-2" >
        <tr>
            <td>{{lastname }}</td>
            <td>{{firstname }}</td>
            <td>{{site }}</td>
            <td>
                <button type="button" class="btn btn-danger" data-toggle="modal"  @click="deleteModal(id,lastname)" >
                    <i class="fa fa-trash"></i> Supprimer
                </button>
            </td>
            <td>
                <button type="button" class="btn btn-primary" data-toggle="modal"  @click="openModal(id)">
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
                        @click="editEmployee(id,firstname,lastname,site)">
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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
            </div>
            <div class="modal-body">
                Are you sure that you want to delete this employee ?
                <ul>
                    <li >
                        {{ firstname }} {{ lastname }}
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
        methods : {
            openModal(id){
                $('#bv-modal-example'+id).modal();
            },
            deleteModal(id,lastname){
                $('#delete-employee'+id).modal();
            },
            editEmployee(id,firstname,lastname,site){
                let payload = {id: id, firstname:firstname, lastname:lastname, site:site};

                this.$store.dispatch('employee/updateEmployee', payload);
            },
            deleteEmployee (id) {
                this.$store.dispatch('employee/deleteEmployee', id);
            }
        }
    }

</script>