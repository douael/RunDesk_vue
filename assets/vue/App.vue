<template>
    <div class="container" >
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <router-link class="navbar-brand" to="/home">App</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul v-if="isAuthenticated" class="navbar-nav">

                    <router-link class="nav-item" tag="li" to="/home" active-class="active">
                        <a class="nav-link">Home</a>
                    </router-link>
                    
                    <router-link class="nav-item" tag="li" to="/dashboard" active-class="active">
                        <a class="nav-link">Dashboard</a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/categorys" active-class="active">
                        <a class="nav-link">Categories</a>
                    </router-link>
                    <router-link class="nav-item"tag="li" to="/materials" active-class="active">
                        <a class="nav-link">Materials</a>
                    </router-link>
                    <li class="nav-item">
                        <a class="nav-link" href="/api/security/logout">Logout</a>
                    </li>
                </ul> 
                <ul v-if="!isAuthenticated" class="navbar-nav">
                    <router-link class="nav-item" tag="li" to="/home" active-class="active">
                        <a class="nav-link">Home</a>
                    </router-link>
                    
                    <router-link class="nav-item" tag="li" to="/login" active-class="active">
                        <a class="nav-link">Login</a>
                    </router-link>
                </ul>
            </div>

        </nav>
        <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">

        <router-view></router-view>
         </main>

        <div class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
            Copyright 2019 - Rundesk
        </div>
    </div>
    </div>
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