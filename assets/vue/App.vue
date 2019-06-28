<template>
    <div class="container-fluid">
      <div class="row">
        <nav class="col-sm-3 col-md-2 hidden-xs-down bg-faded sidebar" style="display: inline-block;position: fixed; bottom: 0;top: 0; background-color:#232732">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#">Overview <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/home">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/dashboard">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/materials">Materials</a>
            </li>
          </ul>
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