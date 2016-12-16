<style scoped>
    #info-bar {
        border-left: 2px solid green;
    }
</style>

<template>
    <div class="panel panel-default">
        <div class="panel-heading">User Data</div>

        <div class="panel-body">
            <div class="well">
                <div class="row">
                    <div class="col-md-6">
                        <tr>
                            <th>Name:</th>
                        </tr>
                        <tr>
                            <th>Email:</th>
                        </tr>
                        <tr>
                            <th>Date Created:</th>
                        </tr>
                        <tr>
                            <th>Last Modified:</th>
                        </tr>
                        <tr>
                            <th>Last Login:</th>
                        </tr>
                    </div>
                    <div class="col-md-6" id="info-bar">
                        <tr>
                            <td>{{ user.name }}</td>
                        </tr>
                        <tr>
                            <td>{{ user.email }}</td>
                        </tr>
                        <tr>
                            <td> {{ user.created_at }}</td>
                        </tr>
                        <tr>
                            <td> {{ user.updated_at }}</td>
                        </tr>
                        <tr>
                            <td>Last Login</td>
                        </tr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user_id: '',
                user: {},
            };
        },

        mounted() {
            console.log('Component ready.');
            this.prepareComponent();
        },

        methods: {
            prepareComponent() {
                this.getUserDetails();
            },

            getUserDetails() {
                this.$http.get('/user')
                    .then(response => {
                        this.user = response.data;
                        console.log(response.data);
                    });
            }
        }
    }
</script>
