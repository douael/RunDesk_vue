<template>
    <div>
        <div class="row col darkBlue-bg green no-margin">
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
                                <input placeholder="new Password" v-validate="{ required: true, min: 8 }" type="password" class="form-control" id="newPassword" name="newPassword" ref="newPassword" v-model="newPassword" :class="{ 'is-invalid': submitted && errors.has('newPassword') }">
                                <div v-if="submitted && errors.has('newPassword')" class="invalid-feedback">{{ errors.first('newPassword') }}</div>

                            </div><div class="col-12">
                                <input placeholder="Confirm Password" v-validate="{ required: true, min: 8 }"  class="form-control" type="password" name="confirmPassword"  id="confirmPassword"  v-model="confirmPassword" ref="confirmPassword" >
                            </div></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary waves-effect waves-light" 
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
    import VeeValidate from 'vee-validate';
    import { ValidationProvider } from 'vee-validate';
    import Vue from 'vue';
    import fr from "vee-validate/dist/locale/fr";
    import VueI18n from "vue-i18n";

    Vue.use(VueI18n);

    const i18n = new VueI18n({
        locale: "fr"
    });

    Vue.use(VeeValidate, {
        i18n,
        dictionary: {
            fr
        }
    });


    
    export default {
        name: 'profils',
        components: {
            ErrorMessage,
            ValidationProvider
        },
        data () {
            return {
                newPassword: '',
                oldPassword: '',
                confirmPassword: '',
                labels: {
                    name: 'Mot de passe',
                },
                submitted: false
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
               this.submitted = true;
               console.log("test");
               this.$validator.validate().then(valid => {
                if (valid) {
                    let payload = {id:id,oldPassword:oldPassword,newPassword:newPassword,confirmPassword:confirmPassword};
                    console.log("bla");
                    this.$store.dispatch('security/editPassword', payload);
                }
            });
               
           },
       },
   }
   </script>
