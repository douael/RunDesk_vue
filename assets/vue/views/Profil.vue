<template>
    <div>
        <div class="row col">
            <h1>Profil</h1>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Login</th>
                        
                        <th></th>
                    </tr>
                </thead>
                <tbody >
                    <tr>
                        <td>test</td>

                        <div class="col-4">
                            <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                                <button @click="editMdpModal()" type="button" class="btn btn-primary">Edit Password</button>
                            </div>
                        </div>       
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" id="editMdp">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" >Edit Password </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <input placeholder="Password" type="password" id="password" ref="password" >
                    </div>
                    <div class="modal-body">
                        <input placeholder="Confirm Password" type="password" id="password" ref="password" >
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal" v-on:click="submitForm()">
                            Edit Password
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
</template>

<script>
    import ErrorMessage from '../components/ErrorMessage';
import axios from 'axios';

    export default {
        name: 'materials',
        components: {
            ErrorMessage,
        },
        data () {
            return {
                name: '',
                isActive: false,
                serialNumber: '',
                id: '',
                category:'',
                file: '',
                labels: {
                    name: 'Nom du matériel',
                    serialNumber: 'Numero de serie',
                    category: 'Categorie'
        },
            };
        },
        created () {
            this.$store.dispatch('material/fetchMaterials');
            this.$store.dispatch('category/fetchCategorys');
        },
        computed: {
            
        },
        methods: {
            editMdpModal(id){
                $('#editMdp').modal();
            },
            editPassword(id,password){
                let payload = {id: id,password:password};

                this.$store.dispatch('security/editPassword', payload);
            },
        },
    }
</script>
