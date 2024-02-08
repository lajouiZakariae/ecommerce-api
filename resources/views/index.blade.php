<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    axios.defaults.withCredentials = true;
    axios.defaults.withXSRFToken = true;
    axios.defaults.baseURL = "http://127.0.0.1:8000";

    axios.defaults.headers = {
        'Accept': 'application/json'
    }

    function login() {
        axios.get("/sanctum/csrf-cookie").then(response => {
            console.log(response); //This is one success but it did set cookie in application cookie
            axios
                .post("/login", {
                    email: "test@example.com",
                    password: "password"
                })
                .then(res => {
                    console.log(res);
                    axios.get('/api/user').then(data => console.log(data))
                });
        });
    }
    login()
</script>
