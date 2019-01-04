var app = new Vue({
    el: '#app',
    data: {
        message: 'Hello Vue!',
        message2: 'You loaded this page on ' + new Date().toLocaleString(),
        seen: true,
        todolist: [
            { text: 'Learn JavaScript', status: 'OK' },
            { text: 'Learn Vue', status: 'In progress'},
            { text: 'Build something awesome', status: 'X' }
        ],
        message3: 'Click the button'
    },
    methods:{
        messageAlert: function () {
            alert('SUKA');
        }
    }
});