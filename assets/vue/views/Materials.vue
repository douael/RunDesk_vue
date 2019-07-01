<template>
    <div>
        <div class="row col darkBlue-bg green no-margin">
            <h1>Materials</h1>
        </div>

        <div class="row col" v-if="canCreateMaterial">
            
                    <div class="col-8">
                        <form style="width:100%">
                <div class="form-row">
                        <div class="col-12">
                            <label :for="name" class="mr-2">{{ labels.name }}</label>
                            <input v-model="name" type="text" class="form-control">
                        </div>
                        <div class="col-12">
                            <label :for="serialNumber" class="mr-2">{{ labels.serialNumber }}</label>
                            <input v-model="serialNumber" type="text" class="form-control">
                        </div>
                        <div class="col-12">
                            <label :for="category" class="mr-2">{{ labels.category }}</label>
                            <select class="form-control" name="category" v-model="category" >
                                <option v-for="category in categorys" v-bind:value="category">
                                {{ category.name }}
                                </option>
                            </select>
                            </div>
                        <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                            <button @click="createMaterial()" :disabled="name.length === 0 || isLoading || serialNumber.length == 0" type="button" class="btn btn-primary">Create</button>
                        </div>
                         </div>
            </form>
            
                    </div>
                    <div class="col-4">
                        <div class="col-12" style="margin-top:10px;margin-bottom:10px;">
                            <button @click="importModal()" type="button" class="btn btn-primary">Import CSV of material</button>
                        </div>
                    </div>
               
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-name :error="error"></error-name>
            <error-serialNumber :error="error"></error-serialNumber>

        </div>
        <div v-else-if="!hasMaterials" class="row col">
            No materials!
        </div>

            <div v-else class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Serial Number</th>
                  <th>Category</th>
                  <th>Delete</th>
                  <th>Update</th>
                  <th>Change Status</th>
                </tr>
              </thead>
              <tbody >
                  <tr  v-for="material in materials" >
                    <td>{{ material.name }}</td>
                    <td>{{ material.serialNumber }}</td>
                    <td>{{material.category.name }}</td>
                    <td>
                        <button type="button" class="btn btn-danger" data-toggle="modal"  @click="deleteModal(material.id,material.name)" >
                            <i class="fa fa-trash"></i> Supprimer
                        </button>
                    </td>
                  
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal"  @click="openModal(material.id)">
                            <i class="fa fa-edit"></i> Modifier
                        </button>
                    </td>
                    <td>
                        <form >
                        <button type="button" class="btn btn-warning" v-if="!material.isActive" @click="activateMaterial(material.id)">Inactif</button>
                        <button type="button" class="btn btn-success" v-else  @click="unactivateMaterial(material.id)">Actif</button>
                        <input type="hidden" id="id" name="id" class="form-control" :value="material.id">
            
                        </form>
                    </td>                </tr></tbody>
            </table>
            </div>
            <div v-for="material in materials">
               <div class="modal fade bg-dark" tabindex="-1" role="dialog" aria-hidden="true" :id="'bv-modal-example'+material.id">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Modifier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                                <div class="col-6">
                                    <input v-model="material.name" type="text" class="form-control">
                                </div>
                                <div class="col-6">
                                    <input v-model="material.serialNumber" type="text" class="form-control">
                                </div>
                                <div class="col-6">
                                  <select class="form-control" name="category" v-model="material.category" >
                                      <option v-for="Othercategory in categorys" v-bind:value="Othercategory.id" >
                                      {{ Othercategory.name }}
                                      </option>
                                      
                                  </select>
                                </div>
                                <input type="hidden" id="isActive" name="isActive" class="form-control" :value="material.isActive">

                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal"
                            @click="editMaterial(material.id,material.name,material.isActive,material.serialNumber,material.category)">
                    Modify
                    </button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         style="display: none;"
         :id="'delete-material'+material.id">
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
                {{ material.name }}
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                    @click.prevent="deleteMaterial(material.id)">
              Delete
            </button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    
          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
         style="display: none;"
         id="import">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Import Material with csv </h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
          <input type="file" id="file" ref="file" accept=".csv" @change="onChangeFileUpload" class="input-file">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal"
                   v-on:click="submitForm()">
              Import
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
            isLoading () {
                return this.$store.getters['material/isLoading'];
            },
            hasError () {
                return this.$store.getters['material/hasError'];
            },
            error () {
                return this.$store.getters['material/error'];
            },
            hasMaterials () {
                return this.$store.getters['material/hasMaterials'];
            },
            materials () {
                return this.$store.getters['material/materials'];
            },
            
            categorys () {
                return this.$store.getters['category/categorys'];
            },
            canCreateMaterial () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            },
            canEditMaterial () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            }
        },
        methods: {
            submitForm(){
                let formData = new FormData();
                formData.append('file', this.file);
                console.log(formData);
                axios.post('/api/material/import',
                    formData,
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
                ).then(function(data){
                document.location.reload(true);
                })
                .catch(function(){
                console.log('FAILURE!!');
                });
            },
             onChangeFileUpload(){
                this.file = event.target.files[0];
            },
            createMaterial () {
                let payload = {name: this.$data.name, isActive: this.$data.isActive,serialNumber: this.$data.serialNumber,category: this.$data.category};

                this.$store.dispatch('material/createMaterial', payload);
            },
            importMaterial (fileMaterial) {
                //let payload = {name: uploadFieldName, isActive: this.$data.isActive,serialNumber: this.$data.serialNumber,category: this.$data.category};

                //this.$store.dispatch('material/importMaterial', fileMaterial);
            },
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
            importModal(id){
                $('#import').modal();
            },
            deleteModal(id,name){
                $('#delete-material'+id).modal();
            },
            editMaterial(id,name,isActive,serialNumber,category){
                let payload = {id: id,name:name, isActive: isActive,serialNumber: serialNumber,category: category};

                this.$store.dispatch('material/updateMaterial', payload);
            },
            deleteMaterial (id) {
                this.$store.dispatch('material/deleteMaterial', id);
            }
            
        },
    }
</script>