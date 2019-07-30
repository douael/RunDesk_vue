<template>
    <div>
    <div class="container-fluid" v-if="isAuthenticated">
      <div class="row" >
        <nav v-if="isAuthenticated" class="col-sm-3 col-md-2 hidden-xs-down bg-faded sidebar" style="display: inline-block;position: fixed; bottom: 0;top: 0; background-color:#A3CDBC">
          <ul  class="navbar-nav">
                    <router-link class="nav-item" tag="li" to="/home" active-class="active" style="text-align: center">
                        <img src="data:image/png;base64,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" alt="RunDesk Logo" />
                    </router-link>     

                    <router-link class="nav-item" tag="li" to="/dashboard" active-class="active">
                        <a class="nav-link">Tableau de bord</a>
                    </router-link>
                    
                    <router-link class="nav-item" tag="li" to="/materials" active-class="active">
                        <a class="nav-link">Matériels</a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/categorys" active-class="active">
                        <a class="nav-link">Catégories</a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/types" active-class="active">
                        <a class="nav-link">Type</a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/employees" active-class="active">
                        <a class="nav-link">Employés</a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/borrowings" active-class="active">
                        <a class="nav-link">Emprunts</a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/profil" active-class="active">
                        <a class="nav-link">Profil</a>
                    </router-link>
                    <li class="nav-item">
                        <a class="nav-link" href="/api/security/logout">Déconnexion</a>
                    </li>
                </ul> 

        </nav>
        <main  class="col-sm-9 offset-sm-3 col-md-10 offset-md-2" style="padding-left:0; padding-right:0">
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
    function idleTimer() {
    var t;
    //window.onload = resetTimer;
    window.onmousemove = resetTimer; // catches mouse movements
    window.onmousedown = resetTimer; // catches mouse movements
    window.onclick = resetTimer;     // catches mouse clicks
    window.onscroll = resetTimer;    // catches scrolling
    window.onkeypress = resetTimer;  //catches keyboard actions

    function logout() {
        window.location.href = '/api/security/logout';  //Adapt to actual logout script
    }

   function reload() {
          window.location = self.location.href;  //Reloads the current page
   }

   function resetTimer() {
        clearTimeout(t);
        t = setTimeout(logout, 18000);  // time is in milliseconds (1000 is 1 second)
        t= setTimeout(reload, 3000);  // time is in milliseconds (1000 is 1 second)
        console.log(t);
    }
}
idleTimer();
</script>