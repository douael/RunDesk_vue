<template>
    <div>
        <div class="row col">
            <h1>Profil</h1>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        
                        <th>User Name</th>
                        <th>Edit Password</th>
                    </tr>
                </thead>
                <tbody >
                    <tr v-for="myprofil in profil">
                        <th>{{ myprofil.login }}</th>


                        <td >
                            <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                                <button @click="editMdpModal(myprofil.id)" type="button" class="btn btn-primary">Edit Password</button>
                            </div>
                        </td>       
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-for="myprofil in profil">

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" :id="'editMdp'+myprofil.id">
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
                        <input placeholder="Confirm Password" type="password" id="confirmPassword" ref="confirmPassword" >
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                            @click="editPassword(id,password)">
                            Edit Password
                    </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        </div>
    </div>
</template>

<script>
    import ErrorMessage from '../components/ErrorMessage';
import axios from 'axios';

    export default {
        components: {
            ErrorMessage,
        },
        data () {
            return {
                password: '',
                labels: {
                    name: 'Password',
        },
            };
        },
        created () {
            this.$store.dispatch('material/fetchMaterials');
            this.$store.dispatch('category/fetchCategorys');
            this.$store.dispatch('security/fetchProfil');
        },
        computed: {

            profil () {
                return this.$store.getters['security/profil'];
            },
            
        },
        methods: {
            editMdpModal(id){
                $('#editMdp'+id).modal();
            },
            editPassword(id,password){
                let payload = {password:password};

                this.$store.dispatch('security/editPassword', payload);
            },
            
        },
    }
</script>
