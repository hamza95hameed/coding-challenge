<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card bg-dark text-white shadow">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form @submit.prevent="submitForm()">
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" v-model="formData.email" class="bg-dark text-white form-control" name="email" value="" required autocomplete="email" autofocus>                        
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" v-model="formData.password" class=" bg-dark text-white form-control" name="password" required autocomplete="current-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return{
                formData:{
                    email:'',
                    password:'',
                }
            }
        },
        methods:{
            submitForm(){
                let form = new FormData();
                form.append('email', this.formData.email);
                form.append('password', this.formData.password);
                axios.post('http://127.0.0.1:8000/api/login', form)
                .then((response) => {
                    localStorage.setItem('token', response.data.access_token)
                    this.$router.push({ name: 'Index' })
                })
                .catch((error) => {
                     // error.response.status Check status code
                }).finally(() => {
                     //Perform action in always
            });

            }
        }

    }
</script>