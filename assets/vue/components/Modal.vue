<template>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         style="display: none;"
         :id="'#update-material'+ id">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Modifier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:window.location.reload()">×</button>
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
</template>
<script>
  export default {
    props: ['material']
  };
</script>