<template>
<br/>
<div class="panel panel-default">
    <div v-show="!table" class="panel-heading">
        All Users
    </div>

    <div class="panel-body">
        <h1 v-show="!message">{{msg}}</h1>
        <table v-show="!table" class="table table-striped task-table">

            <!-- Table Headings -->
            <thead>
                <th>User</th>
                <th>Created at</th>
            </thead>

            <!-- Table Body -->
            <tbody>
                <tr v-for="user in list">
                    <td>
                        {{ user.name }}
                    </td>
                    <td>{{ user.created_at}}</td>
                    <td>
                        <button @click="deleteUser(user.id)" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
    </div>
    </table>
</div>
</template>

<script>
export default {
    data: function() {
        return {
            table: false,
            message: false,
            list: [],
            msg: ''
        };
    },

    ready: function() {
        this.fetchUserList();
    },

    methods: {
        fetchUserList: function() {
            this.$http.get('users').then(function(response) {
                if (this.list.length === 0) {
                    this.msg = 'No new users yet. Just you.';
                    this.table = true;
                    this.message = false;
                }
                this.list = response.data;
                this.table = false;
                this.message = true;
            });
        },
        
        deleteUser: function(id) {
            this.$http.delete('user/' + id);
            this.fetchUserList();
        },
    },
};
</script>
