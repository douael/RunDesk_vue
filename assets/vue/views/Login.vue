<template>
<div class="authentication-bg authentication-bg-pattern">
    <div class="account-pages mt-5 mb-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
          <div class="card bg-pattern">

            <div class="card-body p-4">
                <div class="text-center w-75 m-auto">
                    <img src="../public/img/logo.jpg" alt="" height="100">
                <p class="text-muted mb-4 mt-3">Identifiez-vous pour accéder à votre tableau de bord.</p>
              </div>
            <form method="POST">

                <div class="form-group mb-3">
                  <label for="email">Nom d'utilisateur</label>
                  <input v-model="login"  type="text" class="form-control">
                </div>
                <div class="form-group mb-3">
                  <label for="password">Mot de passe</label>
                  <input v-model="password" class="form-control" type="password" >
                </div>
                    <div class="form-group mb-0 text-center">
                        <button @click="performLogin()" :disabled="login.length === 0 || password.length === 0 || isLoading" type="button" class="btn btn-primary btn-block">Se connecter</button>
                </div>
                </form>
                </div> <!-- end card-body -->
          </div>

        <div v-if="isLoading" class="row col">
            <p>Chargement...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-message :error="error"></error-message>
        </div>
    </div></div></div></div></div>
</template>

<script>
    import ErrorMessage from '../components/ErrorMessage';

    export default {
        name: 'login',
        components: {
            ErrorMessage,
        },
        data () {
            return {
                login: '',
                password: '',
            };
        },
        created () {
            let redirect = this.$route.query.redirect;

            if (this.$store.getters['security/isAuthenticated']) {
                if (typeof redirect !== 'undefined') {
                    this.$router.push({path: redirect});
                } else {
                    this.$router.push({path: '/dashboard'});
                }
            }
        },
        computed: {
            isLoading () {
                return this.$store.getters['security/isLoading'];
            },
            hasError () {
                return this.$store.getters['security/hasError'];
            },
            error () {
                return this.$store.getters['security/error'];
            },
        },
        methods: {
            performLogin () {
                let payload = {login: this.$data.login, password: this.$data.password},
                    redirect = this.$route.query.redirect;

                this.$store.dispatch('security/login', payload)
                    .then(() => {
                        if (!this.$store.getters['security/hasError']) {
                            if (typeof redirect !== 'undefined') {
                                this.$router.push({path: redirect});
                            } else {
                                this.$router.push({path: '/dashboard'});
                            }
                        }
                    });
            },
        },
    }
</script>
