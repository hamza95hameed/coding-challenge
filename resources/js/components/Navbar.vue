<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow">
        <div class="container">
            <a></a>
            <router-link class="navbar-brand" :to="{ name: 'Index' }"> Coding Challenge</router-link>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav ms-auto" v-if="currentUser == ''">

                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'Login' }">Login</router-link>
                    </li>

                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'Register' }">Register</router-link>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto" v-else>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                            {{ currentUser.name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" @click="logout">
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
export default {
    data() {
        return {
            currentUser: '',
            token: localStorage.getItem('token')
        }
    },
    methods:{
        logout(){
            const config = {
                headers: { Authorization: `Bearer ${this.token}` }
            };
            axios.get('api/logout', config).then((response) => {
                localStorage.removeItem('token')
                this.authorizeUser()
                this.$router.push({name: 'Login'})
            }).catch((errors) => {
                console.log(errors)
            })
        },
        authorizeUser(){
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            axios.get('api/user').then((response) => {
                this.currentUser = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        }
    },  
    mounted() {
        this.authorizeUser()
    },
}
</script>