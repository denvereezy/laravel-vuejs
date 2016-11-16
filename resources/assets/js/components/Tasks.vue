<template>
<br/>
<form action="#" @submit.prevent="edit ? updateTask(task.id) : createTask()" method="POST">
    <div class="input-group">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input id="task-name" v-model="task.name" v-el:taskinput type="text" name="name" class="form-control" autofocus>
        <span class="input-group-btn">
      <button v-show="!edit" type="submit" class="btn btn-primary">New Task</button>
      <button v-show="edit" type="submit" class="btn btn-primary">Update Task</button>
    </span>
    </div>
</form>
<br/>

<div class="panel panel-default">
    <div v-show="!table" class="panel-heading">
        All Tasks
    </div>

    <div class="panel-body">
        <table v-show="!table" class="table table-striped task-table">

            <!-- Table Headings -->
            <thead>
                <th>Task</th>
                <th>Created at</th>
                <th>&nbsp;</th>
            </thead>

            <!-- Table Body -->
            <tbody>
                <tr v-for="task in list">
                    <td>
                        {{ task.name }}
                    </td>
                    <td>{{ task.created_at}}</td>
                    <td>
                        <button @click="showTask(task.id)" class="btn btn-warning">Edit</button>
                        <button @click="deleteTask(task.id)" class="btn btn-danger">Delete</button>
                        <button @click="fetchEmailDetails(task.id)" class="btn btn-success">Email</button>
                    </td>
                </tr>
            </tbody>
    </div>
    </table>

    <form class="col-md-8" v-show="emailForm" action="#" @submit.prevent="edit ? updateTask(task.id) : sendEmail(email.id)" method="POST">
        <div class="panel-heading">
            <h4>Email to</h4>
        </div>
        <div class="form-group">
            <label>Subject</label>
            <input class="form-control" id="subject" v-model="email.subject" type="text" name="subject" placeholder="enter subject">
        </div>
        <div class="form-group">
            <label>Task</label>
            <input class="form-control" id="task-name" v-model="email.name" v-el:taskname class="form-control" autofocus disabled>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input class="form-control" id="email" v-model="email.email" type="email" name="email" placeholder="enter email">
        </div>
        <button type="submit" class="btn btn-success">Send</button>
        <button @click="fetchTaskList()" class="btn btn-primary pull-right">Cancel</button>
    </form>
</div>
</template>

<script>
export default {
    data: function() {
        return {
            edit: false,
            table: false,
            emailForm: false,
            list: [],
            task: {
                name: ''
            },
            email: {
                id: '',
                email: '',
                subject: '',
                name: ''
            },
        };
    },

    ready: function() {
        this.fetchTaskList();
    },

    methods: {
        fetchTaskList: function() {
            this.$http.get('tasks').then(function(response) {
                this.list = response.data;
            });
        },

        createTask: function() {
            this.$http.post('/addTask', this.task).then(function(post) {
                this.task.name = '';
                this.edit = false;
                router.go('/tasks');
                this.fetchTaskList();
            });
        },

        updateTask: function(id) {
            this.$http.patch('task/' + id, this.task);
            this.task.name = '';
            this.edit = false;
            this.fetchTaskList();
        },

        showTask: function(id) {
            this.$http.get('task/' + id + '/edit').then(function(response) {
                this.task.id = response.data.id;
                this.task.name = response.data.name;
            });
            this.$els.taskinput.focus();
            this.edit = true;
        },

        fetchEmailDetails: function(id) {
            this.$http.get(id + '/to').then(function(response) {
                this.email.id = response.data.id;
                this.email.email = response.data.email;
                this.email.subject = response.data.subject;
                this.email.name = response.data.name;
            });
            this.$els.taskname.focus();
            this.table = true;
            this.emailForm = true;
        },

        sendEmail: function(id) {
            this.$http.patch('email/send/' + id, this.email);
            this.emailForm = false;
            this.table = false;
        },

        deleteTask: function(id) {
            this.$http.delete('task/' + id);
            this.fetchTaskList();
        }
    },
};
</script>
