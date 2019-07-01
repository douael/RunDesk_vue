<template>
    <div>
        <div class="row col">
            <h1>Profil</h1>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        
                        <th>Identifiant</th>
                        <th>Modifier le mot de passe</th>
                    </tr>
                </thead>
                <tbody >
                    <tr v-for="myprofil in profils">
                        <th>{{ myprofil.login }}</th>


                        <td >
                            <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                                <button @click="editMdpModal(myprofil.id)" type="button" class="btn btn-primary">Modifier le mot de passe</button>
                            </div>
                        </td>       
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-for="myprofil in profils">

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" :id="'editMdp'+myprofil.id">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" >Modifier le mot de passe </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                                            <div class="col-12">

                                <div class="col-12">
                        <input placeholder="old Password" type="hidden" class="form-control" v-model="oldPassword">
                        </div><div class="col-12">
                        <input placeholder="new Password" type="password" class="form-control" id="password" ref="password" v-model="newPassword">
                 </div><div class="col-12">
                        <input placeholder="Confirm Password" class="form-control" type="password" id="confirmPassword"  v-model="confirmPassword" ref="confirmPassword" >
                        </div></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal"
                            @click="editPassword(myprofil.id,oldPassword,newPassword,confirmPassword)" :disabled="newPassword != confirmPassword">
                            Modifier le mot de passe
                    </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        </div>
    </div>
</template>

<script>
    // console.log(profil);
    import ErrorMessage from '../components/ErrorMessage';
    import axios from 'axios';

    export default {
        name: 'profils',
        components: {
            ErrorMessage,
        },
        data () {
            return {
                password: '',
                oldPassword: '',
                newPassword: '',
                confirmPassword: '',
                labels: {
                    name: 'Mot de passe',
                },
            };
        },
        created () {
            this.$store.dispatch('security/fetchProfils');
        },
        computed: {
            profils () {
                return this.$store.getters['security/profils'];
                // return this.$store.getters['material/materials'];
            },
        },
        methods: {
            editMdpModal(id){
                $('#editMdp'+id).modal();
            },
            editPassword(id,oldPassword,newPassword,confirmPassword){
                let payload = {id:id,oldPassword:oldPassword,newPassword:newPassword,confirmPassword:confirmPassword};

                this.$store.dispatch('security/editPassword', payload);
            },
        },
    }
</script>
