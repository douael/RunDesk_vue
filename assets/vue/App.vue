<template>
    <div >
    <div class="container-fluid" v-if="isAuthenticated">
      <div class="row" >
        <nav v-if="isAuthenticated" class="col-sm-3 col-md-2 hidden-xs-down bg-faded sidebar" style="display: inline-block;position: fixed; bottom: 0;top: 0; background-color:#232732">
          <ul  class="navbar-nav">

                    
                    <router-link class="nav-item" tag="li" to="/dashboard" active-class="active">
                        <a class="nav-link">Dashboard</a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/categorys" active-class="active">
                        <a class="nav-link">Categories</a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/materials" active-class="active">
                        <a class="nav-link">Materials</a>
                    </router-link>
                    
                    <router-link class="nav-item" tag="li" to="/employees" active-class="active">
                        <a class="nav-link">Employees</a>
                    </router-link>
                    <li class="nav-item">
                        <a class="nav-link" href="/api/security/logout">Logout</a>
                    </li>
                </ul> 

        </nav>
        <main  class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
            <router-view></router-view>
         </main>
        

        <div  class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
            Copyright 2019 - Rundesk
        </div>
    </div>
    </div>
    <div v-if="!isAuthenticated">
    <main >

        <router-view></router-view>
         </main></div></div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'app',
        created () {
            let isAuthenticated = JSON.parse(this.$parent.$el.attributes['data-is-authenticated'].value),
                roles = JSON.parse(this.$parent.$el.attributes['data-roles'].value);

            let payload = { isAuthenticated: isAuthenticated, roles: roles };
            this.$store.dispatch('security/onRefresh', payload);

            axios.interceptors.response.use(undefined, (err) => {
                return new Promise(() => {
                    if (err.response.status === 403) {
                        this.$router.push({path: '/login'})
                    } else if (err.response.status === 500) {
                        document.open();
                        document.write(err.response.data);
                        document.close();
                    }
                    throw err;
                });
            });
        },
        computed: {
            isAuthenticated () {
                return this.$store.getters['security/isAuthenticated']
            },
        },
    }
    
</script>